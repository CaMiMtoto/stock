<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Drinks', 'Food'];
        foreach ($categories as $category) {
            \App\Category::Create([
                'name' => $category
            ]);
        }
    }
}
