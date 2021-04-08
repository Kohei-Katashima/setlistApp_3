<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setlist;
use App\Models\Song;
use App\Http\Requests\CreateSong;
use Illuminate\Support\Facades\Auth;

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
        // ユーザーのフォルダを取得する
        $setlists = Auth::user()->setlists()->get();

        // 選ばれたフォルダに紐づく曲を取得する
        $songs = $setlist->songs()->get();

        return view('songs/index', [
            'setlists' => $setlists,
            'current_setlist_id' => $setlist->id,
            'songs' => $songs,
        ]);
    }

    /**
     * GET /setlists/{id}/songs/create
     */
    public function showCreateForm(Setlist $setlist)
    {
        return view('songs/create', [
            'setlist_id' => $setlist->id
        ]);
    }

    public function create(Setlist $setlist, CreateSong $request)
    {
        $song = new Song();
        $song->band_name = $request->band_name;
        $song->title = $request->title;
        $song->time = $request->time;

        $setlist->songs()->save($song);

        return redirect()->route('songs.index', [
            'id' => $setlist->id,
        ]);
    }

    /**
     * GET /setlists/{id}/songs/{song_id}/edit
     */
    public function showEditForm(Setlist $setlist, int $song)
    {
        return view('songs/edit', [
            'song' => $song,
        ]);
    }

    public function edit(Setlist $setlist, int $song, CreateSong $request)
    {
        // 2
        $song->band_name = $request->band_name;
        $song->title = $request->title;
        $song->time = $request->time;
        $song->save();

        // 3
        return redirect()->route('songs.index', [
            'id' => $song->setlist_id,
        ]);
    }
}
