<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'name' => 'コーヒー',
                'memo' => 'ツッカーノブルボン',
                'price' => 300,
                'is_selling' => 0
            ],
            [
                'name' => '紅茶',
                'memo' => 'イタライ産',
                'price' => 300,
                'is_selling' => 0
            ],
            [
                'name' => 'オレンジジュース',
                'memo' => 'オレンジ100%',
                'price' => 200,
                'is_selling' => 0
            ],
        ]);
    }
}
