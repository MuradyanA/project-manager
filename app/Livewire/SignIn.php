<?php

namespace App\Livewire;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SignIn extends Component
{
    #[Rule('required|email')]
    public $email = "";

    #[Rule('required')]
    public $password = "";

    #[Rule('boolean')]
    public $rememberPassword = false;


    public function submit()
    {
        $this->validate();
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->rememberPassword)) {
            session()->regenerate();
            return $this->redirect('/home');
        }
        $this->addError('email', 'The provided credentials do not match our records.');
        return back();
    }

    public function render()
    {
        return view('livewire.sign-in');
    }
}
