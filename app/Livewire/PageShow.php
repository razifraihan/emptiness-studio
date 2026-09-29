<?php

namespace App\Livewire;

use App\Models\StaticPage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.app')]
class PageShow extends Component
{
    #[Locked]
    public int $pageId;

    public function mount(string $slug): void
    {
        $page = StaticPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $this->pageId = $page->id;
    }

    public function render()
    {
        $page = StaticPage::findOrFail($this->pageId);

        return view('livewire.page-show', compact('page'))
            ->title($page->title);
    }
}