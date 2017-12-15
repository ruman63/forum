@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4>
                    {{ $thread->owner->name }} <small>posted</small> {{ $thread->title }}
                </h4> 
                </div>
                <div class="panel-body">
                    <article>
                        {{ $thread->body }}
                    </article>
                </div>
            </div>
        </div>
    </div>

    @foreach($thread->replies as $reply)
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#">{{ $reply->owner->name }}</a> said 
                        {{ $reply->created_at->diffForHumans() }}
                    </div>
                    <div class="panel-body">
                        <article>
                            {{ $reply->body }}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(auth()->check())
            <form action="{{ $thread->path('reply') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea name="body" class="form-control" rows="6" placeholder="Have something to say..."></textarea>
                </div>
                <button class="btn btn-default">Reply</button>
            </form>
            @else
            <div>
                <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this thread</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
