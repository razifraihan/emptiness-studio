<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Project')]
class ProjectIndex extends Component
{
    public function render()
    {
        $projects = Project::where('status', '!=', 'draft')
            ->orderByDesc('number')
            ->get();

        return view('livewire.project-index', [
            'current' => $projects->whereIn('status', ['open', 'upcoming']),
            'archive' => $projects->whereIn('status', ['closed', 'archived']),
        ]);
    }
}