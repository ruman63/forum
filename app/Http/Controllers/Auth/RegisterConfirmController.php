<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))->first();
        if (!$user) {
            return redirect(route('threads.index'))->with('flash', 'Invalid Token');
        }

        $user->confirm();

        return redirect(route('threads.index'))->with('flash', 'You are now a confirmed User, you can start posting.');
    }
}
