<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class HeaderSearch extends Component
{
    public string $q = '';

    public function submit(): void
    {
        $term = trim($this->q);

        $this->redirect(
            route('shop', $term !== '' ? ['q' => $term] : []),
            navigate: true
        );
    }

    public function render()
    {
        $term = mb_strtolower(trim($this->q));
        $results = collect();

        if (mb_strlen($term) >= 2) {
            $like = '%' . addcslashes($term, '%_\\') . '%';

            $results = Product::where('status', 'active')
                ->whereRaw('LOWER(name) LIKE ?', [$like])
                ->orderBy('name')
                ->limit(5)
                ->get(['id', 'name', 'slug']);
        }

        return view('livewire.header-search', [
            'results' => $results,
            'term' => $term,
        ]);
    }
}