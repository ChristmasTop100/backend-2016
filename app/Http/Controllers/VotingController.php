<?php

namespace App\Http\Controllers;

use App\Song;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    public function index() {
        if (Auth::check()) {
            $songs = Song::with([
                'votes' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                }
            ])->get();
        } else {
            $songs = Song::all();
        }

        return view('index')->with([
            'songs' => $songs,
        ]);
    }
}
