<?php

use App\Livewire\CreateProject;
use App\Livewire\Projects;
use App\Livewire\AllRequests;
use App\Livewire\ProjectSprints;
use App\Livewire\ViewRepliedComments;
use App\Livewire\ViewRequest;
use App\Livewire\ViewTask;
use App\Livewire\Welcome;
use App\Livewire\Customers;
use App\Livewire\Home;
use App\Livewire\CreateUser;
use App\Livewire\Users;
use App\Livewire\Counter;
use App\Livewire\SignIn;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/counter', Counter::class);
Route::get('/', Welcome::class);
Route::get('/signIn', SignIn::class)->name('login')->middleware('guest');
Route::middleware(['auth'])->group(function () {
    Route::get('/users', Users::class);
    Route::get('/projects', Projects::class);
    Route::get('/projects/create', CreateProject::class);
    Route::get('/projects/{project}', AllRequests::class);
    Route::get('/projects/view/{request}', ViewRequest::class);
    Route::get('/projects/view/{project}', ViewRequest::class);
    Route::get('/projects/{project}/sprints', ProjectSprints::class);
    Route::get('/tasks', ViewTask::class)->name('tasks');
    Route::get('/users/create', CreateUser::class);
    Route::get('/customers', Customers::class)->name('customers');
    Route::get('/home', Home::class)->name('home');
});
