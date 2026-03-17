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
            ['name' => 'Développement Web', 'description' => 'PHP, Laravel, React, etc.'],
            ['name' => 'Design & UI/UX', 'description' => 'Figma, Adobe XD, Théorie des couleurs.'],
            ['name' => 'Data Science', 'description' => 'Python, Machine Learning, Statistiques.'],
            ['name' => 'Marketing Digital', 'description' => 'SEO, Google Ads, Réseaux sociaux.'],
            ['name' => 'Business & Soft Skills', 'description' => 'Management, Communication, Vente.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
