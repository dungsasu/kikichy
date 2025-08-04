<?php

namespace App\Services\Facebook;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class FacebookPixel
{
    protected $data;
    protected $access_token;
    protected $pixel_id;
    protected $user;

    public function __construct($user)
    {

        $this->data = [
            'event_time' => now()->timestamp,
            'action_source' => 'website',
            'user_data' => [
                'client_ip_address' => request()->ip(),
                'client_user_agent' => request()->header('User-Agent'),
                'fbp' => request()->cookie('_fbp') ? request()->cookie('_fbp') : $this->generate_fbp(),
                'fbc' => request()->cookie('_fbc') ?? '',
            ]
        ];
        if ($user) {
            $this->data['user_data'] = array_merge($this->data['user_data'], [
                'em' => hash('sha256', $user->email),
                'ph' => hash('sha256', $user->phone),
            ]);
        }
        $this->access_token = 'EAAHLiJPMbQoBO6UXVAUx0Wvu4zkZBF8dp17sJ75AnDdbp7wZBjyNefwq4ZCr7Np88lHN2P1x6mJZCxXUvZA0tkHAXPaLgTXppb3uxNsBUHWFX17ZCye4TLbu0cwVOYa3DGn1E43TZAZBnvEP6D5y7wczObIPSFGa8Ue9u8hJHUSHAMd76vkChdkp4qIOMgZBC2qzCvAZDZD';
        $this->pixel_id = '581347057606040';
    }

    function generate_fbp()
    {
        $timestamp = time() * 1000;
        $random_number = mt_rand(100000000, 999999999);
        return "fb.1.$timestamp.$random_number";
    }

    function viewContentProduct($product, $category)
    {
        $this->data['event_name'] = 'ViewContent';
        $this->data['custom_data'] = [
            'content_ids' => [$product->id],
            'content_type' => 'product',
            'value' => $product->priceCampainOrigin,
            'currency' => 'VND',
        ];
        $this->data['event_source_url'] = route('client.product', ['category' => $category, 'alias' => $product->alias]);

        // Log::info($this->data);
        $response = Http::withOptions([
            'verify' => '/etc/ssl/certs/cacert.pem',
        ])->post("https://graph.facebook.com/v13.0/{$this->pixel_id}/events", [
            'data' => [$this->data],
            'access_token' => $this->access_token,
        ]);


        // Log::info('Facebook Pixel ViewContent', ['response' => $response->json()]);
    }

    function addToCartProduct($product, $category, $quantity = 1)
    {
        $this->data['event_name'] = 'AddToCart';
        $this->data['custom_data'] = [
            'content_ids' => [$product->id],
            'content_type' => 'product',
            'value' => $product->priceCampainOrigin,
            'currency' => 'VND',
            'contents' => [
                [
                    'id' => $product->id,
                    'quantity' => $quantity
                ]
            ]
        ];
        $this->data['event_source_url'] = route('client.product', ['category' => $category, 'alias' => $product->alias]);

        // Gửi dữ liệu đến Facebook Pixel
        $response = Http::withOptions([
            'verify' => '/etc/ssl/certs/cacert.pem',
        ])->post("https://graph.facebook.com/v13.0/{$this->pixel_id}/events", [
            'data' => [$this->data],
            'access_token' => $this->access_token,
        ]);
        // Log::info('Facebook Pixel AddToCart', ['response' => $response->json()]);
    }
    function trackLead($value = null, $currency = 'VND')
    {
        $this->data['event_name'] = 'Lead';
        $this->data['custom_data'] = [
            'value' => $value,
            'currency' => $currency,
        ];
        $this->data['event_source_url'] = url()->current();

        // Gửi dữ liệu đến Facebook Pixel
        $response = Http::withOptions([
            'verify' => '/etc/ssl/certs/cacert.pem',
        ])->post("https://graph.facebook.com/v13.0/{$this->pixel_id}/events", [
            'data' => [$this->data],
            'access_token' => $this->access_token,
        ]);

        // Log::info('Facebook Pixel Lead', ['response' => $response->json()]);
    }

    function trackSearch($searchString, $products = [], $currency = 'VND', $value = null)
    {
        $contentIds = [];
        $contents = [];

        foreach ($products as $product) {
            $contentIds[] = $product['id'];
            $contents[] = [
                'id' => $product['id'],
                'quantity' => 1,
            ];
        }

        $this->data['event_name'] = 'Search';
        $this->data['custom_data'] = [
            'content_ids' => $contentIds,
            'content_type' => 'product',
            'contents' => $contents,
            'currency' => $currency,
            'search_string' => $searchString,
            'value' => $value,
        ];

        $this->data['event_source_url'] = url()->current();

        // Gửi dữ liệu đến Facebook Pixel
        $response = Http::withOptions([
            'verify' => '/etc/ssl/certs/cacert.pem',
        ])->post("https://graph.facebook.com/v13.0/{$this->pixel_id}/events", [
            'data' => [$this->data],
            'access_token' => $this->access_token,
        ]);

        // Log::info('Facebook Pixel Search', ['response' => $response->json()]);
    }
    function trackAddPaymentInfo($products = [], $currency = 'VND', $value = null)
    {
        $contentIds = [];
        $contents = [];

        foreach ($products as $product) {
            $contentIds[] = $product->id;
            $contents[] = [
                'id' => $product->id,
                'quantity' => 1,
            ];
        }

        $this->data['event_name'] = 'AddPaymentInfo';
        $this->data['custom_data'] = [
            'content_ids' => $contentIds,
            'content_type' => 'product',
            'contents' => $contents,
            'currency' => $currency,
            'value' => $value,
        ];
        $this->data['event_source_url'] = url()->current();

        // Gửi dữ liệu đến Facebook Pixel
        $response = Http::withOptions([
            'verify' => '/etc/ssl/certs/cacert.pem',
        ])->post("https://graph.facebook.com/v13.0/{$this->pixel_id}/events", [
            'data' => [$this->data],
            'access_token' => $this->access_token,
        ]);

        // Log::info('Facebook Pixel AddPaymentInfo', ['response' => $response->json()]);
    }

    function trackPurchase($orderProducts, $total_price, $currency = 'VND')
    {
        $contentIds = [];
        $contents = [];
        $numItems = 0;

        foreach ($orderProducts as $product) {
            $contentIds[] = $product->id;
            $contents[] = [
                'id' => $product->id,
                'quantity' => $product->quantity,
            ];
            $numItems += $product->quantity;
        }

        $this->data['event_name'] = 'Purchase';
        $this->data['custom_data'] = [
            'content_ids' => $contentIds,
            'content_type' => 'product',
            'contents' => $contents,
            'currency' => $currency,
            'num_items' => $numItems,
            'value' => $total_price,
        ];
        $this->data['event_source_url'] = url()->current();

        // Gửi dữ liệu đến Facebook Pixel
        $response = Http::withOptions([
            'verify' => '/etc/ssl/certs/cacert.pem',
        ])->post("https://graph.facebook.com/v13.0/{$this->pixel_id}/events", [
            'data' => [$this->data],
            'access_token' => $this->access_token,
        ]);

        // Log::info('Purchase Items', ['orderProducts' => json_encode($orderProducts)]);
        // Log::info('Facebook Pixel Purchase', ['response' => $response->json()]);
    }
}
