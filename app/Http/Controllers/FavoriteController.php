<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();
        return ['message' => 'Reply marked as favorite!'];
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
        return ['message' => 'Reply unmarked as favorite!'];
    }

}
