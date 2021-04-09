<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setlist;
use App\Models\Song;
use App\Http\Requests\CreateSong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SongController extends Controller
{
    //

    /**
     * song一覧
     * @param Setlist $setlist
     * @return \Illuminate\View\View
     */
    public function index(Setlist $setlist)
    {

        if (Auth::user()->id !== $setlist->user_id) {
            abort(403);
        }
        
        // ユーザーのフォルダを取得する
        $setlists = Auth::user()->setlists()->get();
        
        // 選ばれたフォルダに紐づく曲を取得する
        $songs = $setlist->songs()->get();
        // $sum = $songs->sum('time');

        return view('songs/index', [
            'setlists' => $setlists,
            'current_setlist_id' => $setlist->id,
            'songs' => $songs,
            // 'sum' => $sum,
        ]);
    }

    /**
     * GET /setlists/{id}/songs/create
     */
    public function showCreateForm(Setlist $setlist)
    {
        return view('songs/create', [
            'setlist' => $setlist->id
        ]);
    }

    public function create(Setlist $setlist, CreateSong $request)
    {
        $song = new Song();
        $song->band_name = $request->band_name;
        $song->title = $request->title;
        $song->time = $request->time;

        $setlist->songs()->save($song);

        Session::flash('err_msg', '曲が追加されました。');

        return redirect()->route('songs.index', [
            'setlist' => $setlist->id,
        ]);
    }

    /**
     * GET /setlists/{id}/songs/{song_id}/edit
     */
    public function showEditForm(Setlist $setlist, Song $song)
    {
        $this->checkRelation($setlist, $song);

        return view('songs/edit', [
            'song' => $song,
        ]);
    }

    public function edit(Setlist $setlist, Song $song, CreateSong $request)
    {
        $this->checkRelation($setlist, $song);

        $song->band_name = $request->band_name;
        $song->title = $request->title;
        $song->time = $request->time;
        $song->save();

        Session::flash('err_msg', '曲が更新されました。');

        return redirect()->route('songs.index', [
            'setlist' => $song->setlist_id,
        ]);
    }

    public function delete(Setlist $setlist, Song $song)
    {
        $this->checkRelation($setlist, $song);


        if (empty($song)) {
            Session::flash('err_msg', 'データがありません');
            return redirect()->route('songs.index', [
                'setlist' => $song->setlist_id,
            ]);
        }
        try {
            $song->delete();
        } catch (\Throwable $e) {
            abort(500);
        }
        Session::flash('err_msg', '曲が削除されました。');
        return redirect()->route('songs.index', [
            'setlist' => $song->setlist_id,
        ]);
    }

    private function checkRelation(Setlist $setlist, Song $song)
    {
        if ($setlist->id !== $song->setlist_id) {
            abort(404);
        }
    }
}
