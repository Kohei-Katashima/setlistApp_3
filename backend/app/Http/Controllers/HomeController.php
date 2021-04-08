<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
         // // ログインユーザーを取得する
         $user = Auth::user();


         // // ログインユーザーに紐づくフォルダを一つ取得する
         $setlist = $user->setlists()->first();
 
         // まだ一つもフォルダを作っていなければホームページをレスポンスする
         if (is_null($setlist)) {
             return view('home');
         }
 
         // フォルダがあればそのフォルダのタスク一覧にリダイレクトする
         return redirect()->route('songs.index', [
             'id' => $setlist->id,
         ]);
    }
}
