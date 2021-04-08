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
    public function index(int $id)
    {
        $user = Auth::user();

        // $set = Setlist::with('users');

        $setlists = $user->setlists()->get();

        // $setlists = $set->get();
        // 選ばれたフォルダを取得する
        $current_setlist = Setlist::find($id);

        if (is_null($current_setlist)) {
            abort(404);
        }

        // 選ばれたフォルダに紐づく曲を取得する
        $songs = $current_setlist->songs()->get();

        return view('songs/index', [
            'setlists' => $setlists,
            'current_setlist' => $current_setlist,
            'current_setlist_id' => $current_setlist->$id,
            'songs' => $songs,
        ]);
    }

    /**
     * GET /setlists/{id}/songs/create
     */
    public function showCreateForm(int $id)
    {
        return view('songs/create', [
            'setlist_id' => $id
        ]);
    }

    public function create(int $id, CreateSong $request)
    {
        $current_setlist = Setlist::find($id);

        $song = new Song();
        $song->band_name = $request->band_name;
        $song->title = $request->title;
        $song->time = $request->time;

        $current_setlist->songs()->save($song);

        return redirect()->route('songs.index', [
            'id' => $current_setlist->id,
        ]);
    }

    /**
     * GET /setlists/{id}/songs/{song_id}/edit
     */
    public function showEditForm(int $id, int $song_id)
    {
        $song = Song::find($song_id);

        return view('songs/edit', [
            'song' => $song,
        ]);
    }

    public function edit(int $id, int $song_id, CreateSong $request)
    {
        // 1
        $song = Song::find($song_id);

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
