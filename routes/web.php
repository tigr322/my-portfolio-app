<?php

use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home'));