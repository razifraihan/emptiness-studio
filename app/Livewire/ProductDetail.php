<?php

namespace App\Livewire;

use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProductDetail extends Component
{
    #[Locked]
    public int $productId;

    public ?int $variantId = null;

    public bool $added = false;

    public function mount(Product $product): void
    {
        abort_unless($product->status === 'active', 404);

        $this->productId = $product->id;

        if ($product->variants()->count() === 1) {
            $this->variantId = $product->variants()->value('id');
        }
    }

    public function selectVariant(int $id): void
    {
        $this->variantId = $id;
        $this->added = false;
    }

    public function addToCart(): void
    {
        $product = $this->loadProduct();
        $variant = $product->variants->firstWhere('id', $this->variantId);

        if (! $variant || ! ($product->project?->is_open_for_sale ?? true)) {
            return;
        }

        $available = max(0, $variant->stock - $variant->reserved);

        if ($available < 1) {
            return;
        }

        $cart = session('cart', []);
        $cart[$variant->id] = min(($cart[$variant->id] ?? 0) + 1, $available);
        session(['cart' => $cart]);

        $this->dispatch('cart-updated');

        $this->added = true;
    }

    protected function loadProduct(): Product
    {
        return Product::with(['variants', 'images', 'project', 'category'])
            ->findOrFail($this->productId);
    }

    public function render()
    {
        $product = $this->loadProduct();
        $variant = $product->variants->firstWhere('id', $this->variantId);
        $open = $product->project?->is_open_for_sale ?? true;

        $available = $variant ? max(0, $variant->stock - $variant->reserved) : 0;
        $showRemaining = false;

        if ($variant && $available > 0 && config('shop.show_remaining')) {
            $sold = OrderItem::where('product_variant_id', $variant->id)
                ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))
                ->sum('quantity');

            $showRemaining = $variant->reserved > 0 || $sold > 0;
        }

        return view('livewire.product-detail', compact(
            'product', 'variant', 'open', 'available', 'showRemaining'
        ))->title($product->name);
    }
}