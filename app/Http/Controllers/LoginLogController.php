<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function index()
    {
        $loginLogs = LoginLog::with('user')
            ->orderBy('login_at', 'desc')
            ->paginate(20);

        return view('logs.index', compact('loginLogs'));
    }
}
