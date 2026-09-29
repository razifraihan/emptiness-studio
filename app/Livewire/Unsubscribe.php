<?php

namespace App\Livewire;

use App\Models\Subscriber;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Unsubscribe')]
class Unsubscribe extends Component
{
    #[Locked]
    public int $subscriberId;

    public bool $done = false;

    public function mount(string $token): void
    {
        $this->subscriberId = Subscriber::where('token', $token)->firstOrFail()->id;
    }

    public function confirm(): void
    {
        Subscriber::whereKey($this->subscriberId)->update(['unsubscribed_at' => now()]);

        $this->done = true;
    }

    public function render()
    {
        return view('livewire.unsubscribe');
    }
}