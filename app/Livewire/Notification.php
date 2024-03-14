<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\NotificationType;
use Livewire\Attributes\On;

class Notification extends Component
{
    public int $notificationType;
    public string $message;
    public bool $showNotification = false;

    public int $timeout = 3000;


    #[On('displayNotification')]
    public function displayNotification(string $message, int $type)
    {
        $this->message = $message;
        $this->showNotification = true;
        $this->notificationType = $type;
    }
    public function hideNotification()
    {
        $this->showNotification = false;
    }
    public function render()
    {
        return view('livewire.notification');
    }
}
