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

        // $sum = Song::selectRaw('SEC_TO_TIME(sum(time_to_sec(time)))')->groupBy('setlist_id')->get();
        $sum = Song::selectRaw('sec_to_time(sum( time_to_sec(time))) as total_time')->groupBy('setlist_id')->get();
        // dd($sum);

        // $sum = $songs->sum('time_to_sec(time)) as total_sec,
        // sec_to_time(sum( time_to_sec(time))) as total_time ');



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
        $song->memo = $request->memo;

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
        $song->memo = $request->memo;
        $song->id = $request->input('id');
        $song->save();

        Session::flash('err_msg', '曲が更新されました。');

        return redirect()->route('songs.index', [
            'setlist' => $song->setlist_id,
        ]);
    }

    public function update(CreateSong $request)
    {
        // dd($request->songs);
        $songs = Song::all();

        foreach ($songs as $song) {
            $song->timestamps = false;
            $id = $song->id;
            foreach ($request->songs as $songFrontEnd) {
                if ($songFrontEnd['id'] == $id) {
                    $song->update(['order' => $songFrontEnd['order']]);
                }
            }
        }

        return response('Update Successful.', 200);
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

    public function search(Setlist $setlist, Request $request)
    {
        //
        $setlists = Setlist::latest()->where('title', 'like', "%{$request->search}%")->paginate(5);

        $search_result = $request->search . 'を含むセットリストの検索結果' . $setlists->total() . '件';

        // ユーザーのフォルダを取得する
        $setlists = Auth::user()->setlists()->get();

        // 選ばれたフォルダに紐づく曲を取得する
        $songs = $setlist->songs()->get();

        return view('songs.index', [
            'setlists' => $setlists,
            'search_result' => $search_result,
            'search_query' => $request->search,
            'current_setlist_id' => $setlist->id,
            'songs' => $songs,
        ]);
    }
}
