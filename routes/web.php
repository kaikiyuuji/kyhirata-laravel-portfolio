<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PortfolioController;

Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index');
