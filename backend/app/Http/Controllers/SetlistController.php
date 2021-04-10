<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSetlist;
use App\Models\Setlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SetlistController extends Controller
{
    //
    public function showCreateForm()
    {
        return view('setlists/create');
    }

    public function create(CreateSetlist $request)
    {
        $user = Auth::user();
        // セットリストモデルのインスタンスを作成する
        $setlist = new Setlist();
        // タイトルに入力値を代入する
        $setlist->title = $request->title;
        // ★ ユーザーに紐づけて保存
        // $set = Setlist::with('users');
        // $set->save($setlist);

        $user->setlists()->save($setlist);

        Session::flash('err_msg', 'セットリストファイルが追加されました。');

        return redirect()->route('songs.index', [
            'setlist' => $setlist->id,
        ]);
    }
}
