@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')
            <p class="text-center">
                {{ $threads->render() }}
            </p>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                    <div class="panel-heading">
                        Search
                    </div>
                    <div class="panel-body">
                        <form action="/threads/search">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" placeholder="Search for Threads...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Search</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @if( count($trendingThreads))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Trending Threads
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach($trendingThreads as $thread)
                                <li class="list-group-item"><a href="{{ $thread->link }}">{{$thread->title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
