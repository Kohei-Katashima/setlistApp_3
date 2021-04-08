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

        if (Auth::user()->id !== $setlist->user_id) {
            abort(403);
        }

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
