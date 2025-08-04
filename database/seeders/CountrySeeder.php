<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\admin\Country\Country;
use App\Models\admin\City\City;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo sample countries
        $countries = [
            [
                'name' => 'Việt Nam',
                'code' => 'VN',
                'alias' => 'viet-nam',
                'description' => 'Cộng hòa Xã hội chủ nghĩa Việt Nam',
                'status' => 1
            ],
            [
                'name' => 'Thailand',
                'code' => 'TH',
                'alias' => 'thailand',
                'description' => 'Vương quốc Thái Lan',
                'status' => 1
            ],
            [
                'name' => 'Singapore',
                'code' => 'SG',
                'alias' => 'singapore',
                'description' => 'Cộng hòa Singapore',
                'status' => 1
            ],
            [
                'name' => 'Malaysia',
                'code' => 'MY',
                'alias' => 'malaysia',
                'description' => 'Malaysia',
                'status' => 1
            ],
        ];

        foreach ($countries as $countryData) {
            $country = Country::create($countryData);
            
            // Tạo sample cities cho mỗi country
            if ($country->code == 'VN') {
                $cities = [
                    ['name' => 'Hà Nội', 'code' => 'HN', 'alias' => 'ha-noi'],
                    ['name' => 'TP. Hồ Chí Minh', 'code' => 'SGN', 'alias' => 'ho-chi-minh'],
                    ['name' => 'Đà Nẵng', 'code' => 'DN', 'alias' => 'da-nang'],
                    ['name' => 'Hải Phòng', 'code' => 'HP', 'alias' => 'hai-phong'],
                ];
            } elseif ($country->code == 'TH') {
                $cities = [
                    ['name' => 'Bangkok', 'code' => 'BKK', 'alias' => 'bangkok'],
                    ['name' => 'Chiang Mai', 'code' => 'CNX', 'alias' => 'chiang-mai'],
                    ['name' => 'Phuket', 'code' => 'HKT', 'alias' => 'phuket'],
                ];
            } elseif ($country->code == 'SG') {
                $cities = [
                    ['name' => 'Singapore', 'code' => 'SG', 'alias' => 'singapore'],
                ];
            } elseif ($country->code == 'MY') {
                $cities = [
                    ['name' => 'Kuala Lumpur', 'code' => 'KL', 'alias' => 'kuala-lumpur'],
                    ['name' => 'Penang', 'code' => 'PG', 'alias' => 'penang'],
                ];
            } else {
                $cities = [];
            }

            foreach ($cities as $index => $cityData) {
                City::create([
                    'name' => $cityData['name'],
                    'code' => $cityData['code'],
                    'alias' => $cityData['alias'],
                    'country_id' => $country->id,
                    'description' => 'Thành phố ' . $cityData['name'],
                    'status' => 1
                ]);
            }
        }
    }
}
