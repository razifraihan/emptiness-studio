<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public function render()
    {
        $project = Project::where('status', '!=', 'draft')
            ->orderByDesc('number')
            ->first();

        $featured = Product::with(['variants', 'images', 'project'])
            ->where('status', 'active')
            ->withSum(['orderItems as sold_count' => fn ($q) => $q->whereHas(
                'order', fn ($o) => $o->where('payment_status', 'paid')
            )], 'quantity')
            ->orderByDesc('is_featured')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.home', compact('project', 'featured'));
    }
}