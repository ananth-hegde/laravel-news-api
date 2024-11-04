<?php

namespace Database\Seeders;

use Database\Factories\NewsArticleFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsArticleFactory::new()->count(0)->create();
    }
}
