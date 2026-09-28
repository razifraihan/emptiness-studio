<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    #[On('cart-updated')]
    public function refresh(): void
    {
        // Memicu render ulang
    }

    public function render()
    {
        return view('livewire.cart-count', [
            'count' => array_sum(session('cart', [])),
        ]);
    }
}