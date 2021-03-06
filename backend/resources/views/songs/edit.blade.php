@extends('layout')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col col-md-offset-3 col-md-6">
      <nav class="panel panel-default">
        <div class="panel-heading">タスクを編集する</div>
        <div class="panel-body">
          @if($errors->any())
          <div class="alert alert-danger">
            @foreach($errors->all() as $message)
            <p>{{ $message }}</p>
            @endforeach
          </div>
          @endif
          <form action="{{ route('songs.edit', ['setlist' => $song->setlist_id, 'song' => $song->id]) }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="title">タイトル</label>
              <input type="text" class="form-control" name="title" id="title" value="{{ old('title') ?? $song->title }}" />
            </div>
            <div class="form-group">
              <label for="band_name">アーティスト名</label>
              <input type="text" class="form-control" name="band_name" id="band_name" value="{{ old('band_name') ?? $song->band_name }}" />
            </div>
            <div class="form-group">
              <label for="time">時間</label>
              <input type="time" class="form-control" name="time" id="time" value="{{ old('time') ?? $song->time }}" />
            </div>
            <div class="form-group">
                <label for="memo">メモ</label>
                <input type="text" class="form-control" name="memo" id="memo" value="{{ old('memo') ?? $song->memo}}" />
              </div>
            <div class="text-right">
              <button type="submit" class="btn btn-primary">更新</button>
            </div>
          <input type="hidden" name="id" value="{{ old('id') ?? $song->id }}">
          </form>
          <div class="text-right">
            <small class="mt-3 pt-3">
              <a href="{{ route('songs.index', ['setlist' => $song->setlist_id]) }}">戻る</a>
            </small>
          </div>
        </div>
    </div>
    </nav>
  </div>
</div>
</div>
@endsection