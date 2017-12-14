@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="POST" action="{{ route('threads.store') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" name="title" placeholder="Give it a Title!" class="form-control">
                </div>
                <div class="form-group">
                    <textarea name="body" id="body" class="form-control" rows="8" placeholder="Write Something Amazing!"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Publish</button>
            </form>
        </div>
    </div>
</div>
@endsection
