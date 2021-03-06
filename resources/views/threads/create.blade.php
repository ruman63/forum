@extends('layouts.app')
@section('header')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create New Thread
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('threads.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <select name="channel_id" class="form-control" required placeholder="Select A Channel">
                                <option value=""> Select A Channel </option>
                                @foreach($channels as $channel)
                                <option value="{{ $channel->id }}" {{ ($channel->id == old('channel_id')) ? "selected" : '' }} required>
                                    {{ $channel->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Give it a Title!" class="form-control">
                        </div>
                        <div class="form-group">
                            <wysiwyg name="body" placeholder="Ask your question..."></wysiwyg>
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6LeFLEEUAAAAAGk0gyssw0spvMw3hXjbGj62aBeO"></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </div>
                        @if(count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
