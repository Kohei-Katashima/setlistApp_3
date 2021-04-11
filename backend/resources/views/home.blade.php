@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col col-md-offset-3 col-md-6">
    <div class="text-center">
      <img src="{{ asset('image/logo_transparent.png') }}" class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140">
      <h4>setlistAppへようこそ！</h4>
    </div>
      <nav class="panel panel-default">
        <div class="panel-heading text-center">
          まずはセットリストフォルダを作成しましょう
        </div>
        <div class="panel-body">
          <div class="text-center">
            <a href="{{ route('setlists.create') }}" class="btn btn-primary">
              フォルダ作成ページへ
            </a>
          </div>
        </div>
      </nav>
    </div>
  </div>
</div>
@endsection