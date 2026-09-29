<?php

namespace App\Livewire;

use App\Models\Subscriber;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class SubscribeForm extends Component
{
    public string $email = '';

    // Honeypot: manusia tidak melihat kolom ini, bot biasanya mengisinya.
    public string $website = '';

    public bool $done = false;

    public function submit(): void
    {
        if ($this->website !== '') {
            $this->done = true;

            return;
        }

        $key = 'subscribe:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Please try again in a little while.');

            return;
        }

        RateLimiter::hit($key, 3600);

        $this->email = Str::lower(trim($this->email));

        $this->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $this->email],
            ['source' => 'footer'],
        );

        if ($subscriber->unsubscribed_at) {
            $subscriber->update([
                'unsubscribed_at' => null,
                'subscribed_at' => now(),
            ]);
        }

        $this->email = '';
        $this->done = true;
    }

    public function render()
    {
        return view('livewire.subscribe-form');
    }
}