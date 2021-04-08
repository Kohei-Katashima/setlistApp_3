@extends('layout')

@section('content')
    <div class="container">
      <div class="row">
        <div class="col col-md-4">
          <nav class="panel panel-default">
            <div class="panel-heading">セットリストフォルダ</div>
            <div class="panel-body">
              <a href="{{ route('setlists.create') }}" class="btn btn-default btn-block">
                フォルダを追加する
              </a>
            </div>
            <div class="list-group">
              @foreach($setlists as $setlist)
              <a href="{{ route('songs.index', ['setlist' => $setlist->id]) }}" class="list-group-item {{ $current_setlist_id === $setlist->id ? 'active' : '' }}">
                {{ $setlist->title }}
              </a>
              @endforeach
            </div>
          </nav>
        </div>
        <div class="column col-md-8">
          <!-- ここにタスクが表示される -->
          <div class="panel panel-default">
            <div class="panel-heading"> セットリスト</div>
            <div class="panel-body">
              <div class="text-right">
                <a href="{{ route('songs.create', ['setlist' => $current_setlist_id]) }}" class="btn btn-default btn-block">
                  曲を追加する
                </a>
              </div>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>タイトル</th>
                  <th>アーティスト名</th>
                  <th>時間</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="sort">
                @foreach($songs as $song)
                <tr>
                  <td>{{ $song->title }}</td>
                  <td>{{ $song->band_name }}</td>
                  <td>{{ $song->time }}</td>
                  <td><a href="{{ route('songs.edit', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}">編集</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
@endsection
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script> 
$(function() {
    $('#sort').sortable();
});
</script>