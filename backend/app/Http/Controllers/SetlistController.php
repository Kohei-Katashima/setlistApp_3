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

    public function showEditForm(Setlist $setlist )
    {

        return view('setlists/edit', [
            'setlist' => $setlist,
        ]);
    }

    public function edit(Setlist $setlist,CreateSetlist $request)
    {

        $setlist->title = $request->title;
        $setlist->save();

        Session::flash('err_msg', 'セットリストファイルが更新されました。');

        return redirect()->route('songs.index', [
            'setlist' => $setlist,
        ]);
    }

    public function delete(Setlist $setlist)
    {

        if (empty($setlist)) {
            Session::flash('err_msg', 'データがありません');
            return redirect()->route('songs.index', [
                'setlist' => $setlist
            ]);
        }
        try {
            $setlist->delete();
        } catch (\Throwable $e) {
            abort(500);
        }
        $user = Auth::user();

         // // ログインユーザーに紐づくフォルダを一つ取得する
         $setlist = $user->setlists()->first();
 
         // まだ一つもフォルダを作っていなければホームページをレスポンスする
         if (is_null($setlist)) {
             return view('home');
         }
        Session::flash('err_msg', 'セットリストフォルダが削除されました。');
        return redirect()->route('songs.index', [
            'setlist' => $setlist
        ]);
    }
}
