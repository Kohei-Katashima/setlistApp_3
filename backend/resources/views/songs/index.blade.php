@extends('layout')

@section('content')
<div class="container-fluid">
  @if (session('err_msg'))
  <p class="text-danger h3">{{ session('err_msg') }}</p>
  @endif
  @isset ($search_result)
  <p class="text-info h3">{{ $search_result }}</p>
  @endisset
  <div class="row">
    <div class="col col-md-4">
      <nav class="panel panel-default">
        <b-button block v-b-toggle.accordion-1 size="lg" style="background-color: #e6e9ed; color: #212529; border:none;" class="accordion-title">セットリストフォルダ</b-button>
        <b-card no-body class="mb-1">
          <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <div class="panel-body">
                <form class="card card-sm" action="{{ route('songs.search', ['setlist' => $current_setlist_id]) }}" method="GET">
                  @csrf
                  <div class=" row no-gutters align-items-center">
                    <!--end of col-->
                    <div class="col">
                      <input class="form-control form-control-lg form-control-borderless" type="search" placeholder="盛り上がるセット" aria-label="Search" name="search">
                    </div>
                    <!--end of col-->
                    <div class="col-auto">
                      <button class=" btn-lg btn-success" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                    <!--end of col-->
                  </div>
                </form>
                <a href="{{ route('setlists.create') }}" class="btn btn-default btn-block">
                  フォルダを追加する
                </a>
              </div>
              <div class="list-group">
                <div class="">
                  @foreach($setlists as $setlist)
                  <a href="{{ route('songs.index', ['setlist' => $setlist->id]) }}" class="list-group-item h4 {{ $current_setlist_id === $setlist->id ? 'active' : '' }}">
                    {{ $setlist->title }}
                  </a>
                  <div class="text-right">
                    <button type="button" class="btn btn-lg dropdown-toggle dropdown-toggle-split " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            </b-card-body>
          </b-collapse>
        </b-card>
      </nav>
    </div>
    <div class="column col-md-8">
      <!-- ここにタスクが表示される -->
      <div class="panel panel-default">
        <b-button block v-b-toggle.accordion-2 size="lg" style="background-color: #e6e9ed; color: #212529; border: none;" class="accordion-title">セットリスト</b-button>
        <b-card no-body class="mb-1">
          <b-collapse id="accordion-2" visible accordion="" role="tabpanel">
            <b-card-body>
              <div id="accordion" class="accordion-container">
                <div class="panel-body">
                  <div class="text-right">
                    <a href="{{ route('songs.create', ['setlist' => $current_setlist_id]) }}" class="btn btn-default btn-block">
                      曲を追加する
                    </a>
                  </div>
                </div>
                <div class="table-responsive">
                  <table-draggable :songs="{{ $songs }}"></table-draggable>
                </div>
              </div>
            </b-card-body>
          </b-collapse>
        </b-card>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col col-md-12">
          <nav class="panel panel-default">
            <div class="panel-heading " style="background-color: #e6e9ed;">
              <input type="text" size="50" id="search" value="下北沢　ライブハウス" />
              <input type="button" size="55" value="検索" onClick="SearchGo()" />
            </div>
            <div class="panel-body">
              <div id="map_canvas" style="width: 100%; height: 300%;"></div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script>
  function checkDelete() {
    if (window.confirm('削除してよろしいですか？')) {
      return true;
    } else {
      return false;
    }
  }
</script>
@extends('maps.scripts')