<?php

namespace App\Services;

use App\Models\admin\Tour\Tour as TourModel;
use App\Models\admin\Tour\TourAttribute;
use App\Services\GetflyService;
use App\Services\EcountService;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\Sheets;
use App\Models\admin\Filter\FilterTable as FilterTableModel;
use App\Models\admin\Filter\Filter as FilterModel;

class TourService
{
    public $api;
    public $tourId;

    protected $getflyService;
    protected $ecountService;

    public function __construct(GetflyService $getflyService, EcountService $ecountService)
    {
        $this->getflyService = $getflyService;
        $this->ecountService = $ecountService;
    }

    public function updatePrice($tourId)
    {
        $tour = TourModel::with('attributes')->where('id', $tourId)->first();
        $success = [];
        $errors = [];

        foreach ($tour->attributes as $attribute) {
            $info = $this->getflyService->getProducts([
                'product_code' => $attribute->code,
            ]);

            if (isset($info['data'][0]) && $info['data'][0]['cover_price'] > 0) {
                $attribute->update([
                    'price' => $info['data'][0]['cover_price'],
                ]);
                $success[] = "Mã $attribute->code: success";
            } else {
                $errors[] = "Mã $attribute->code: failed";
            }
        }

        return redirect()->back()->with('success', implode("<br />", $success))->withErrors($errors);
    }

    public function updateQuantity($tourId)
    {
        $tour = TourModel::with('attributes')->where('id', $tourId)->first();

        $success = [];
        $errors = [];

        foreach ($tour->attributes as $attribute) {
            try {
                $info = $this->ecountService->getQuantityProduct($attribute->code);
                $quantity = !empty($info) ? $info[0]['BAL_QTY'] : NULL;

                if ($quantity != NULL) {
                    $attribute->update([
                        'quantity' => $info[0]['BAL_QTY'],
                    ]);
                }

                $success[] = "Mã $attribute->code: success - Tồn: $quantity";
            } catch (\Exception $e) {
                $errors[] = "Mã $attribute->code: failed - {$e->getMessage()}";
            }
        }

        return redirect()->back()->with('success', implode("<br />", $success))->withErrors($errors);
    }

    public function updateAllPrice() {}

    public function updateAllQuantity()
    {
        try {
            $info = $this->ecountService->getQuantityProducts();

            foreach ($info as $item) {
                $attribute = TourAttribute::where('code', $item['PROD_CD'])->first();
                if ($attribute) {
                    $attribute->update([
                        'quantity' => $item['BAL_QTY'],
                    ]);
                }
            }

            return response()->json(['message' => 'success'], 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 200, [], JSON_PRETTY_PRINT);
        }
    }

    public function sendToGoogleMerchant()
    {
        try {
            $client = new Client();
            $client->setApplicationName('prosamoste');
            $client->setScopes([Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setAuthConfig(base_path('google-service-account.json'));
            $service = new Sheets($client);
            $spreadsheetId = '1GIitoionU-Q69zOzyx2u0dXVbImfWHSm8FAmsnfjsMI';
            $range = 'Trang tính1';

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $tours = TourModel::where('gmc', 1)
                ->where('published', 1)
                ->with([
                    'category',
                    'attributes' => function ($query) {
                        $query->where('published', 1)
                            ->where('price', '>', 0)
                            ->orderBy('ordering', 'asc')
                            ->with([
                                'images' => function ($query) {
                                    $query->orderBy('ordering', 'asc');
                                }
                            ]);
                    },
                ])
                ->get();

            foreach ($tours as $item) {
                if ($item->attributes->count() == 0) {
                    continue;
                }

                $hightlights = [];
                $detail = [];

                $filterTable = FilterTableModel::where('id', $item->category->filter_table_id)->with(
                    [
                        'items' => function ($query) {
                            $query->with(['category' => function ($query2) {
                                $query2->with('group');
                            }]);
                        }
                    ]
                )->first();
                if ($filterTable) {
                    $filterCategory_id = $filterTable->items->pluck('filter_category_id');
                    $filterSelectAvailable = FilterModel::whereIn('filter_category_id', $filterCategory_id)->get();

                    $filters = $filterTable->items;
                    $groupFilter = $filters->groupBy('category.group.name');

                    $filterValues = [];

                    foreach ($item->filters as $filter) {
                        $filterValues[$filter->filter_category_id] = $filter->value;
                    }

                    foreach ($groupFilter as $groupName => $group) {
                        foreach ($group as $itemGroup) {
                            if (isset($filterValues[$itemGroup->filter_category_id])) {
                                $itemGroup->value = strip_tags($filterValues[$itemGroup->filter_category_id]);
                            } else {
                                $itemGroup->value = null;
                            }

                            if (@$itemGroup->category->type == 'single') {
                                $itemGroup->value = @$filterSelectAvailable->where('id', $itemGroup->value)->first()->name ?: null;
                            }
                            if (@$itemGroup->category->type == 'multiple') {
                                $itemGroup->value = explode(',', $itemGroup->value);
                                $itemGroup->value = implode(', ', $filterSelectAvailable->whereIn('id', $itemGroup->value)->pluck('name')->toArray());
                            }

                            if ($itemGroup->value || $itemGroup->value != '<p>&nbsp;</p>') {
                                $stripTagValue = strip_tags($itemGroup->value); 
                                if ($stripTagValue && $stripTagValue != '&nbsp;') {
                                    $detail[] = '"' . $groupName . ':' . $itemGroup->name . ':' . $stripTagValue . '"';

                                    if ($itemGroup->is_outstanding) {
                                        $hightlights[] = '"' . $itemGroup->name . ' ' . $stripTagValue . '"';
                                    }
                                }
                            }
                        }
                    }
                } 

                $hightlights = implode(', ', $hightlights);
                $detail = implode(', ', $detail);

                foreach ($item->attributes as $attribute) {
                    $value[] = [
                        0 => @$attribute->code ?: @$item->id, //id
                        1 => @$item->name, // title
                        2 => @$item->seo_description ?: @$item->name, // description
                        3 => 'in_stock', // availability
                        4 => '', // availability_date
                        5 => '', // expiration_date
                        6 => @$item->href, // link
                        7 => @$item->href, // mobile_link
                        8 => @$attribute->images[0]->image ? asset($attribute->images[0]->image) : asset($item->image), // image_link
                        9 => "$attribute->price_old VND", // price
                        10 => "$attribute->price_public VND", // sale_price
                        11 => '', // sale_price_effective_date
                        12 => 'no', // identifier exists
                        13 => '', // gtin
                        14 => '', // mpn
                        15 => $item->brand ?: '', // brand
                        16 => $hightlights, // tour_highlight
                        17 => $detail, // tour_detail
                        18 => @$attribute->images[0]->image ? asset($attribute->images[0]->image) : asset($item->image), // additional_image_link
                        19 => 'new', // condition
                        20 => 'no', // adult
                        21 => $attribute->name ?: '', // color
                    ];
                }
            }

            $service->spreadsheets_values->clear(
                $spreadsheetId,
                'A2:Z',
                $body1 = new Sheets\ClearValuesRequest()
            );

            $body = new Sheets\ValueRange([
                'values' => $value
            ]);

            $params = [
                "valueInputOption" => "RAW"
            ];

            $result = $service->spreadsheets_values->append(
                $spreadsheetId,
                $range,
                $body,
                $params
            );

            if ($result->updates->updatedRows != 0) {
                return redirect()->back()->with('success', 'success');
            } else {
                return redirect()->back()->withErrors('fail');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function sendToGoogleMerchant_API()
    {
        $client = new Client();
        $client->setAuthConfig(base_path('google-service-account.json'));
        $client->addScope(ShoppingContent::CONTENT);

        $service = new ShoppingContent($client);
        $merchantId = '537786305';

        $tours = TourModel::where('status', 1)
            ->get();

        $success = 0;
        $errors = [];
        foreach ($tours as $tour) {
            $googleTour = new \Google\Service\ShoppingContent\Product([
                'offerId' => $tour->id,
                'title' => $tour->name,
                'description' => $tour->description,
                'link' => $tour->href,
                'imageLink' => asset($tour->image),
                'contentLanguage' => 'vi',
                'targetCountry' => 'VN',
                'channel' => 'online',
                'price' => new \Google\Service\ShoppingContent\Price([
                    'value' => $tour->price_public,
                    'currency' => 'VND',
                ]),
                'availability' => $tour->quantity ? 'in stock' : 'out of stock',
            ]);

            try {
                $service->products->insert($merchantId, $googleTour);
                $success++;
            } catch (\Exception $e) {
                $errors[] = $tour->id . ', Error: ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => $success,
            'errors' => $errors,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
