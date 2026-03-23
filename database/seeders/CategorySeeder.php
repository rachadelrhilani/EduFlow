<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Développement Web', 'slug' => 'PHP, Laravel, React, etc.'],
            ['name' => 'Design & UI/UX', 'slug' => 'Figma, Adobe XD, Théorie des couleurs.'],
            ['name' => 'Data Science', 'slug' => 'Python, Machine Learning, Statistiques.'],
            ['name' => 'Marketing Digital', 'slug' => 'SEO, Google Ads, Réseaux sociaux.'],
            ['name' => 'Business & Soft Skills', 'slug' => 'Management, Communication, Vente.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
