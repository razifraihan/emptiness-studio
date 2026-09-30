<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Shop')]
class Shop extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $q = '';

    #[Url]
    public string $category = '';

    #[Url]
    public array $sizes = [];

    #[Url]
    public array $colors = [];

    #[Url]
    public string $min = '';

    #[Url]
    public string $max = '';

    #[Url]
    public string $sort = 'newest';

    public function updated($property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['q', 'category', 'sizes', 'colors', 'min', 'max']);
        $this->resetPage();
    }

    public function render()
    {
        $term = mb_strtolower(trim($this->q));
        $sizes = array_values(array_filter($this->sizes));
        $colors = array_values(array_filter($this->colors));
        $min = $this->min !== '' ? (int) preg_replace('/\D/', '', $this->min) : null;
        $max = $this->max !== '' ? (int) preg_replace('/\D/', '', $this->max) : null;

        $query = Product::with(['variants', 'images', 'project'])
            ->where('status', 'active')
            ->withMin('variants', 'price')
            ->withSum(['orderItems as sold_count' => fn ($q) => $q->whereHas(
                'order', fn ($o) => $o->where('payment_status', 'paid')
            )], 'quantity')
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . $term . '%';
                $q->where(function ($w) use ($like) {
                    $w->whereRaw('LOWER(name) LIKE ?', [$like])
                      ->orWhereRaw('LOWER(description) LIKE ?', [$like]);
                });
            })
            ->when($this->category, fn ($q) => $q->whereHas(
                'category', fn ($c) => $c->where('slug', $this->category)
            ))
            ->when($sizes || $colors || $min !== null || $max !== null, function ($q) use ($sizes, $colors, $min, $max) {
                // Satu varian harus memenuhi semua filter sekaligus
                $q->whereHas('variants', function ($v) use ($sizes, $colors, $min, $max) {
                    if ($sizes) {
                        $v->whereIn('size', $sizes);
                    }
                    if ($colors) {
                        $v->whereIn('color', $colors);
                    }
                    if ($min !== null) {
                        $v->where('price', '>=', $min);
                    }
                    if ($max !== null) {
                        $v->where('price', '<=', $max);
                    }
                });
            });

        match ($this->sort) {
            'price_asc' => $query->orderBy('variants_min_price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('variants_min_price')->orderBy('id'),
            default => $query->latest()->orderByDesc('id'),
        };

        // Pilihan filter diambil dari varian produk aktif
        $variantBase = ProductVariant::whereHas('product', fn ($p) => $p->where('status', 'active'));

        $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $sizeOptions = (clone $variantBase)->whereNotNull('size')->where('size', '!=', '')
            ->distinct()->pluck('size')->all();
        usort($sizeOptions, function ($a, $b) use ($sizeOrder) {
            $ia = array_search(strtoupper($a), $sizeOrder, true);
            $ib = array_search(strtoupper($b), $sizeOrder, true);

            return [$ia === false ? 99 : $ia, $a] <=> [$ib === false ? 99 : $ib, $b];
        });

        $colorOptions = (clone $variantBase)->whereNotNull('color')->where('color', '!=', '')
            ->distinct()->orderBy('color')->pluck('color')->all();

        $activeFilters = count($sizes) + count($colors)
            + ($min !== null ? 1 : 0) + ($max !== null ? 1 : 0);

        return view('livewire.shop', [
            'products' => $query->paginate(12),
            'categories' => Category::orderBy('sort_order')->get(),
            'sizeOptions' => $sizeOptions,
            'colorOptions' => $colorOptions,
            'activeFilters' => $activeFilters,
            'hasAnyFilter' => $activeFilters > 0 || $term !== '' || $this->category !== '',
        ]);
    }
}