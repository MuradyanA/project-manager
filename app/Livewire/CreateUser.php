<?php

namespace App\Livewire;

use App\Events\UserCreatedEvent;
use App\Mail\UserRegistered;
use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class CreateUser extends Component
{
    #[Rule('required|string')]
    public $name = "";

    #[Rule('required|email')]
    public $email = "";
    
    #[Rule('required|string')]
    public $password = "";

    #[Rule('required|string')]
    public $role = "";
    
    #[Rule('int|nullable|exists:customers,id')]
    public $customerId = null;



    public function generateRandomPassword($length = 10) {
        return Str::random($length);
    }


    public function createUser(){
        $this->password = $this->generateRandomPassword(10);
        $validated = $this->validate();
        $user = User::create($validated);
        $pass = $this->password;
        event(new UserCreatedEvent($user, $pass));
        return redirect()->to('/users/create')
               ->with('status', 'User created!');
    }

    public function render(){
        return view('livewire.create-user');
    }
}
