<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();
        if (request()->expectsJson()) {
            return ['status' => "reply favorited!"];
        }
        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
        if (request()->expectsJson()) {
            return ['status' => "reply unfavorited!"];
        }
        return back();
    }
}
