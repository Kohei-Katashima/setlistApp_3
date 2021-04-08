@extends('layout')

@section('content')
<div class="container">
  @if (session('err_msg'))
  <p class="text-danger">{{ session('err_msg') }}</p>
  @endif
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
              <th>曲順</th>
              <th>タイトル</th>
              <th>アーティスト名</th>
              <th>時間</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="sort">
            <?php $i = 1; //表示順用のカウンタ　
            ?>
            @foreach($songs as $song)
            <tr>
              <td class="class_orderby_id"> {{ $i++ }}</td>
              <td>{{ $song->title }}</td>
              <td>{{ $song->band_name }}</td>
              <td>{{ $song->time }}</td>
              <td><a href="{{ route('songs.edit', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}">編集</a></td>
              <form action="{{ route('songs.delete', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}" method="POST" onsubmit="return checkDelete()">
                @csrf
                @method('DELETE')
                <td><button type='submit' class='btn btn-primary' > 削除</button></td>
              </form>
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
<!-- <script> 
$(function() {
    $('#sort').sortable();
});
</script> -->
<script>
  function checkDelete() {
    if (window.confirm('削除してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
  // GET以外では、csrfトークンが無いとエラーになる。
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(function() {

    // jquery-uiを使って、表をD&Dで並び替え可能にする
    // sortableは直下の要素を「並び替え可能」にする！
    $('#sort').sortable({

      // 並び替えされたら、MySQLに反映させる。
      update: function(ev, ui) {
        // 変更後の順番配列を取得
        arr_rec = $(this).sortable("toArray");
        // 全てのレコードに対して、表示順を更新する。
        for (var i = 0, len = arr_rec.length; i < len; i++) {
          console.log(arr_rec[i]);
          $.ajax({
            type: 'PUT',
            url: 'product_class/' + arr_rec[i],
            data: {
              'orderby_id': i + 1
            },
            success: function(data) {
              console.log(data);
            }
          });
        }
        // 表示順のセルに、番号を振り直す
        var i = 1;
        $(".class_orderby_id").each(function() {
          $(this).text(i++);
        });
      }
    });

    // テキストボックスからフォーカスが離れたら、入力されている文字列でレコード更新する
    $(".textbox").focusout(function() {

      // フォーカスの外れたレコードIDと文字列を取得
      var record_id = $(this).parent("td").parent("tr").attr('id');
      var record_text = $(this).val();

      // MySQLレコードを更新
      $.ajax({
        type: 'PUT',
        url: 'product_class/' + record_id,
        data: {
          'class_name': record_text
        },
        success: function(data) {
          console.log(data);
        }
      });
    });

    


  });
</script>