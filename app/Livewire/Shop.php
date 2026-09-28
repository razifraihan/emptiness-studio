<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Shop')]
class Shop extends Component
{
    #[Url]
    public string $category = '';

    #[Url]
    public string $sort = 'newest';

    public function render()
    {
        $query = Product::with(['variants', 'images', 'project'])
            ->where('status', 'active')
            ->withMin('variants', 'price')
            ->withSum(['orderItems as sold_count' => fn ($q) => $q->whereHas(
                'order', fn ($o) => $o->where('payment_status', 'paid')
            )], 'quantity')
            ->when($this->category, fn ($q) => $q->whereHas(
                'category', fn ($c) => $c->where('slug', $this->category)
            ));

        match ($this->sort) {
            'price_asc' => $query->orderBy('variants_min_price'),
            'price_desc' => $query->orderByDesc('variants_min_price'),
            default => $query->latest(),
        };

        return view('livewire.shop', [
            'products' => $query->get(),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }
}