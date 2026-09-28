<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        ProductVariant::query()->delete();
        Product::withTrashed()->forceDelete();
        Project::query()->delete();
        Category::query()->delete();

        $categories = [];
        foreach (['Hoodie', 'Tote Bag', 'Work Jacket', 'T-Shirt'] as $i => $name) {
            $categories[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sort_order' => $i,
            ]);
        }

        $projects = [
            1 => ['For A Space of Clarity', 'closed'],
            2 => ['Where Stillness Speaks', 'closed'],
            3 => ['Freedom in The Quiet', 'open'],
            4 => ['In Emptiness, We Grow', 'draft'],
        ];

        $created = [];
        foreach ($projects as $number => [$name, $status]) {
            $created[$number] = Project::create([
                'number' => $number,
                'name' => $name,
                'slug' => Str::slug($name),
                'status' => $status,
                'concept' => null,
                'is_preorder' => false,
                'show_countdown' => false,
            ]);
        }

        $items = [
            ['Hoodie',       'Hoodie',      350000, ['S', 'M', 'L', 'XL'], 10],
            ['Tote Bag',     'Tote Bag',    120000, [null],                10],
            ['Work Jacket',  'Work Jacket', 450000, ['M', 'L', 'XL'],       0],
            ['Work T-Shirt', 'T-Shirt',     220000, ['S', 'M', 'L', 'XL'], 10],
        ];

        foreach ($items as [$name, $cat, $price, $sizes, $stock]) {
            $product = Product::create([
                'project_id' => $created[3]->id,
                'category_id' => $categories[$cat]->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Deskripsi menyusul.',
                'status' => 'active',
                'is_featured' => true,
            ]);

            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => 'ES03-' . strtoupper(Str::slug($name)) . '-BLK' . ($size ? "-$size" : ''),
                    'size' => $size,
                    'color' => 'Hitam',
                    'price' => $price,
                    'stock' => $stock,
                    'reserved' => 0,
                    'weight_grams' => 300,
                ]);
            }
        }
    }
}