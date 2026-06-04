<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Single auth endpoint that works for both web (institution/admin) and student guards.
// The channel callback in routes/channels.php handles per-guard authorization.
Broadcast::routes(['middleware' => ['web', 'auth:web,student']]);

require __DIR__ . '/auth.php';
require __DIR__ . '/admin/auth.php';
require __DIR__ . '/admin/web.php';
require __DIR__ . '/institution.php';
require __DIR__ . '/student.php';
require __DIR__ . '/website.php';
