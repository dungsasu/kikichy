<?php

namespace App\Models\admin\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\Product as ProductModel;
use App\Models\admin\Filter\FilterTable as FilterTableModel;

class ProductCategories extends Model
{
    use HasFactory;

    protected $appends = ['href'];

    public function getHrefAttribute()
    {
        return $this->alias ? route('client.product-categories', ['alias' => $this->alias]) : null;
    }

    public function filter_table()
    {
        return $this->belongsTo(FilterTableModel::class, 'filter_table_id');
    }

    public function products() {
        return $this->hasMany(ProductModel::class, 'category_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategories::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategories::class, 'parent_id');
    }

    public function ancestors() {
        return $this->parentCategory()->with('ancestors');
    }

    public static function indentRows(&$rows,  $type = 1, $rootid = 0)
    {
        $children = array();
        if (count($rows)) {
            foreach ($rows as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        $categories = self::treerecurse($rootid, '', array(), $children, 9999, 0, $type);
        return $categories;
    }
    /* order follow tree
	 * If not found parent => assign parent_id = 0;
	 *
	 */
    public static function indentRows2(&$rows,  $type = 1, $rootid = 0)
    {

        //	 If not found parent => assign parent_id = 0;
        if (count($rows)) {
            foreach ($rows as $row) {
                $count = 0;
                foreach ($rows as $row1) {
                    if (isset($row->parent_id))
                        if ($row->parent_id == $row1->id) {
                            $count++;
                            break;
                        }
                }
                if ($count == 0)
                    $row->parent_id = 0;
            }
        }
        $list = self::indentRows($rows, $type, $rootid);
        return $list;
    }



    public static function treerecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
    {
        if (@$children[$id] && $level <= $maxlevel) {
            foreach ($children[$id] as $v) {
                $id = $v->id;

                switch ($type) {
                    case 2:
                        $pre     = '- ';
                        $spacer = '&nbsp;&nbsp;';
                        break;
                    case 3:
                        $pre     = '  ';
                        $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
                        break;
                    case 4:
                        $pre     = '  ';
                        $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
                        $spacer .= '&nbsp;&nbsp;&nbsp;&nbsp;' . '<input name="categoryid[]" value="' . $id . '" />';
                        break;
                    case 1:
                    default:
                        $pre     = '<sup>|_</sup>&nbsp;';
                        $spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        break;
                }

                if ($v->parent_id == 0) {
                    $txt     = $v->name;
                } else {
                    $txt     = $pre . $v->name;
                }
                $pt = $v->parent_id;
                $list[$id] = $v;
                $list[$id]->treename = "$indent$txt";
                if (@$children[$id]) {
                    $list[$id]->children = count(@$children[$id]);
                } else {
                    $list[$id]->children = 0;
                }

                $list = self::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
            }
        }
        return $list;
    }
}
