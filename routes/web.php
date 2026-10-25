<?php

use App\Livewire\TaskManager;
use App\Livewire\TaskForm;
use App\Livewire\TaskDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', TaskManager::class)->name('tasks.index');
Route::get('/tasks/create', TaskForm::class)->name('tasks.create');
Route::get('/tasks/{taskId}/edit', TaskForm::class)->name('tasks.edit');
Route::get('/tasks/{id}', TaskDetail::class)->name('tasks.show');
