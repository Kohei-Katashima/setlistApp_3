@extends('layout')

@section('content')
<div class="container">
  @if (session('err_msg'))
  <p class="text-danger">{{ session('err_msg') }}</p>
  @endif
  @isset ($search_result)
  <p class="text-info">{{ $search_result }}</p>
  @endisset
  <div class="row">
    <div class="col col-md-4">
      <nav class="panel panel-default">
        <div id="accordion" class="accordion-container">
          <div class="panel-heading accordion-title js-accordion-title" style="background-color: #e6e9ed;">セットリストフォルダ</div>
          <div class="accordion-content">
            <div class="panel-body">
              <form class="form-inline mt-2 mt-md-0 ml-2" action="{{ route('songs.search', ['setlist' => $current_setlist_id]) }}" method="GET">
                @csrf
                <input class="form-control" type="search" placeholder="盛り上がるセット" aria-label="Search" name="search">
                <button class="btn btn-outline-success " type="submit"><i class="fas fa-search"></i></button>
              </form>
              <a href="{{ route('setlists.create') }}" class="btn btn-default btn-block">
                フォルダを追加する
              </a>
            </div>
            <div class="list-group">
              <div class="">
                @foreach($setlists as $setlist)
                <a href="{{ route('songs.index', ['setlist' => $setlist->id]) }}" class="list-group-item {{ $current_setlist_id === $setlist->id ? 'active' : '' }}">
                  {{ $setlist->title }}
                </a>
                <div class="text-right">
                  <button type="button" class="btn dropdown-toggle dropdown-toggle-split " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">▽
                  </button>
                  <div class="dropdown-menu" style="background-color: #fff;">
                    <a href="{{ route('setlists.edit', ['setlist' => $setlist->id]) }}" class='list-group-item dropdown-item'>編集
                    </a>
                    <form action="{{ route('setlists.delete', ['setlist' => $setlist->id]) }}" method="POST" onsubmit="return checkDelete()">
                      @csrf
                      @method('DELETE')
                      <button type='submit' class='float-right list-group-item dropdown-item'> 削除</button>
                    </form>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </nav>
    </div>
    <div class="column col-md-8">
      <!-- ここにタスクが表示される -->
      <div class="panel panel-default">
        <div id="accordion" class="accordion-container">
          <div class="panel-heading accordion-title js-accordion-title" style="background-color: #e6e9ed;">セットリスト</div>
          <div class="accordion-content">
            <div class="panel-body">
              <div class="text-right">
                <a href="{{ route('songs.create', ['setlist' => $current_setlist_id]) }}" class="btn btn-default btn-block">
                  曲を追加する
                </a>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>曲順</th>
                    <th>タイトル</th>
                    <th>
                      <div class="text-center">
                        アーティスト名
                      </div>
                    </th>
                    <th>時間</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="sort">
                  <?php $i = 1; //表示順用のカウンタ　
                  ?>
                  @foreach($songs as $song)
                  <tr>
                    <td class="class_orderby_id"> {{ $i++ }}</td>
                    <td class="textbox">{{ $song->title }}</td>
                    <td class="textbox">
                      <div class="text-center">
                        {{ $song->band_name }}
                      </div>
                    </td>
                    <td class="textbox">{{ substr($song->time, 0, 5)}}</td>
                    <td>
                      <div class="text-right">
                        <a href="{{ route('songs.edit', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}" class='btn btn-primary btn-sm'>編集</a>
                      </div>
                    </td>
                    <form action="{{ route('songs.delete', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}" method="POST" onsubmit="return checkDelete()">
                      @csrf
                      @method('DELETE')
                      <td><button type='submit' class='btn btn-primary btn-sm'> 削除</button></td>
                    </form>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col col-md-8">
            <nav class="panel panel-default">
              <div class="panel-heading " style="background-color: #e6e9ed;">
                <input type="text" size="30" id="search" value="下北沢　ライブハウス" />
                <input type="button" size="55" value="検索" onClick="SearchGo()" />
              </div>
              <div id="map_canvas" style="width: 100%; height: 30%;"></div>
          </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

<script>
  function checkDelete() {
    if (window.confirm('削除してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script>

<script>
  jQuery(function($) {

    $('.js-accordion-title').on('click', function() {
      /*クリックでコンテンツを開閉*/
      $(this).next().slideToggle();
      /*矢印の向きを変更*/
      $(this).toggleClass('open');
    });

  });
</script>

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
            type: 'POST',
            url: "songs/" + arr_rec[i],
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
        type: 'POST',
        url: "songs/" + record_id,
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
@extends('maps.scripts')

<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>