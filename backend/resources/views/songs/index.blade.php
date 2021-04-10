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
              <td class="textbox">{{ $song->title }}</td>
              <td class="textbox">{{ $song->band_name }}</td>
              <td class="textbox">{{ substr($song->time, 0, 5)}}</td>
              <td><a href="{{ route('songs.edit', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}">編集</a></td>
              <form action="{{ route('songs.delete', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}" method="POST" onsubmit="return checkDelete()">
                @csrf
                @method('DELETE')
                <td><button type='submit' class='btn btn-primary'> 削除</button></td>
              </form>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col col-md-8">
        <nav class="panel panel-default">
        <div class="panel-heading">
          <input type="text" size="30" id="search" value="下北沢　ライブハウス" />
          <input type="button" size="55" value="検索" onClick="SearchGo()" />
          </div>
          <div id="map_canvas" style="width: 100%; height: 30%;"></div>
        </nav>
        </div>
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
            url: 'songs/' + arr_rec[i],
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
        url: 'songs/' + record_id,
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

<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBR5RLGX11pxAYicF__NvgyK9OMIqiDqis&libraries=places" charset="utf-8"></script>

<script>
  var mayMap;
  var service;

  // マップの初期設定
  function initialize() {
    // Mapクラスのインスタンスを作成（緯度経度は池袋駅に設定）
    var initPos = new google.maps.LatLng(35.6615848, 139.664697);
    // 地図のプロパティを設定（倍率、マーカー表示位置、地図の種類）
    var myOptions = {
      zoom: 15,
      center: initPos,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    // #map_canva要素にMapクラスの新しいインスタンスを作成
    myMap = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    // 検索の条件を指定（緯度経度、半径、検索の分類）
    var request = {
      location: initPos,
      radius: 1000, // ※１ 表示する半径領域を設定(1 = 1M)
      types: ['night_club'] // ※２ typesプロパティの施設タイプを設定
    };
    var service = new google.maps.places.PlacesService(myMap);
    service.search(request, Result_Places);
  }

  // 検索結果を受け取る
  function Result_Places(results, status) {
    // Placesが検家に成功したかとマうかをチェック
    if (status == google.maps.places.PlacesServiceStatus.OK) {
      for (var i = 0; i < results.length; i++) {
        // 検索結果の数だけ反復処理を変数placeに格納
        var place = results[i];
        createMarker({
          text: place.name,
          position: place.geometry.location
        });
      }
    }
  }

  // 入力キーワードと表示範囲を設定
  function SearchGo() {
    var initPos = new google.maps.LatLng(0, 0);
    var mapOptions = {
      center: initPos,
      zoom: 0,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    // #map_canva要素にMapクラスの新しいインスタンスを作成
    myMap = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    service = new google.maps.places.PlacesService(myMap);
    // input要素に入力されたキーワードを検索の条件に設定
    var myword = document.getElementById("search");
    var request = {
      query: myword.value,
      radius: 5000,
      location: myMap.getCenter()
    };
    service.textSearch(request, result_search);
  }

  // 検索の結果を受け取る
  function result_search(results, status) {
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < results.length; i++) {
      createMarker({
        position: results[i].geometry.location,
        text: results[i].name,
        map: myMap
      });
      bounds.extend(results[i].geometry.location);
    }
    myMap.fitBounds(bounds);
  }

  // 該当する位置にマーカーを表示
  function createMarker(options) {
    // マップ情報を保持しているmyMapオブジェクトを指定
    options.map = myMap;
    // Markcrクラスのオブジェクトmarkerを作成
    var marker = new google.maps.Marker(options);
    // 各施設の吹き出し(情報ウインドウ)に表示させる処理
    var infoWnd = new google.maps.InfoWindow();
    infoWnd.setContent(options.text);
    // addListenerメソッドを使ってイベントリスナーを登録
    google.maps.event.addListener(marker, 'click', function() {
      infoWnd.open(myMap, marker);
    });
    return marker;
  }

  // ページ読み込み完了後、Googleマップを表示
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
