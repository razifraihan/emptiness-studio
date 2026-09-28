<?php

namespace App\Livewire;

use App\Models\ProductVariant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Keranjang')]
class Cart extends Component
{
    public function increment(int $variantId): void
    {
        $cart = session('cart', []);
        $variant = ProductVariant::find($variantId);

        if (! $variant || ! isset($cart[$variantId])) {
            return;
        }

        $available = max(0, $variant->stock - $variant->reserved);
        $cart[$variantId] = min($cart[$variantId] + 1, $available);

        $this->save($cart);
    }

    public function decrement(int $variantId): void
    {
        $cart = session('cart', []);

        if (! isset($cart[$variantId])) {
            return;
        }

        $cart[$variantId]--;

        if ($cart[$variantId] < 1) {
            unset($cart[$variantId]);
        }

        $this->save($cart);
    }

    public function remove(int $variantId): void
    {
        $cart = session('cart', []);
        unset($cart[$variantId]);

        $this->save($cart);
    }

    protected function save(array $cart): void
    {
        session(['cart' => $cart]);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cart = session('cart', []);

        $variants = ProductVariant::with('product.images')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $lines = [];
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            $variant = $variants->get($id);

            if (! $variant || ! $variant->product) {
                continue;
            }

            $available = max(0, $variant->stock - $variant->reserved);
            $unavailable = $available < 1;
            $qty = $unavailable ? $qty : min($qty, $available);
            $lineTotal = $unavailable ? 0 : $variant->price * $qty;
            $image = $variant->product->images->first();

            $lines[] = [
                'id' => $variant->id,
                'name' => $variant->product->name,
                'slug' => $variant->product->slug,
                'label' => trim(implode(' / ', array_filter([$variant->size, $variant->color]))),
                'price' => $variant->price,
                'qty' => $qty,
                'available' => $available,
                'unavailable' => $unavailable,
                'total' => $lineTotal,
                'image' => $image ? asset('storage/' . $image->path) : null,
            ];

            $subtotal += $lineTotal;
        }

        return view('livewire.cart', compact('lines', 'subtotal'));
    }
}