<?php

namespace App\Http\Controllers\admin\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Comment\ProductComment as ProductCommentModel;
use Illuminate\Support\Facades\DB;
use App\Models\admin\Product\Product;
use Illuminate\Support\Facades\Redis;

class ProductComment extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->controller = self::class;
        $this->model = ProductCommentModel::class;
        $this->view = 'admin.comment.product';
        $this->prefix = 'product';
    }

    public function index()
    {
        $list = ProductCommentModel::select(
            'product_id',
            DB::raw('AVG(`rate`) as `avg_rate`'),
            DB::raw('MAX(`created_at`) as `latest_comment`'),
            DB::raw('COUNT(`id`) as `total_comment`'),
            DB::raw('SUM(CASE WHEN `helpfull` = 1 THEN 1 ELSE 0 END) as `total_helpfull`'),
            DB::raw('SUM(CASE WHEN `read` = 0 THEN 1 ELSE 0 END) as `total_unread`')
        )
            ->where('parent_id', 0)
            ->where('product_id', '!=', 0)
            ->groupBy('product_id')
            ->orderByDesc('latest_comment')
            ->paginate($this->limit); 

        parent::setData([
            'list' => $list
        ]);

        return parent::index();
    }

    public function edit($id)
    {
        $data = ProductCommentModel::where('product_id', $id)
            ->where('parent_id', 0)
            ->with('comments','images')
            ->orderBy('id', 'desc')
            ->get();

        $product = Product::where('id', $id)->first();

        $avg = $data->avg('rate');
        $total = $data->count();
        $helpfull = $data->where('helpfull', 1)->count();
        $unread = $data->where('read', 0)->count();

        return view($this->view . '.form', array_merge($this->data, [
            'data' => $data,
            'product' => $product,
            'avg' => $avg,
            'total' => $total,
            'helpfull' => $helpfull,
            'unread' => $unread,
            'view' => $this->view
        ]));
    }

    public function save(Request $request)
    {
        $data = $request->all();

        try {
            if ($data['id']) {
                $comment = ProductCommentModel::find($data['id']);
                if ($request->has('published')) {
                    $comment->update([
                        'published' => $data['published']
                    ]);
                } else {
                    $comment->update([
                        'name' => $data['name'],
                        'content' => $data['content'],
                        'user_id' => $data['user_id'],
                    ]);
                }               
            } else {
                $commentParent = ProductCommentModel::find($data['parent_id']);
                $commentParent->update([
                    'read' => 1
                ]);

                $comment = new ProductCommentModel();
                $comment->name = $data['name'];
                $comment->content = $data['content'];
                $comment->user_id = $data['user_id'];
                $comment->parent_id = $data['parent_id'];
                $comment->published = 1;
                $comment->read = 1;
                $comment->save();
            }

            return response()->json([
                'error' => 0,
                'message' => 'Cập nhật thành công!',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Cập nhật không thành công!',
                'message' => $e->getMessage()
            ]);
        }
    }
}
