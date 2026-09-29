<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProjectShow extends Component
{
    #[Locked]
    public int $projectId;

    public function mount(Project $project): void
    {
        abort_if($project->status === 'draft', 404);

        $this->projectId = $project->id;
    }

    public function render()
    {
        $project = Project::findOrFail($this->projectId);

        $products = Product::with(['variants', 'images', 'project'])
            ->where('project_id', $project->id)
            ->where('status', 'active')
            ->withSum(['orderItems as sold_count' => fn ($q) => $q->whereHas(
                'order', fn ($o) => $o->where('payment_status', 'paid')
            )], 'quantity')
            ->latest()
            ->get();

        return view('livewire.project-show', compact('project', 'products'))
            ->title($project->name);
    }
}