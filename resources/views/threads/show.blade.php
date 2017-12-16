@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                <h4>
                    <a href="/profiles/{{ $thread->owner->id }}">{{ $thread->owner->name }}</a> <small>posted</small> {{ $thread->title }}
                </h4> 
                </div>
                <div class="panel-body">
                    <article>
                        {{ $thread->body }}
                    </article>
                </div>
            </div>
            
            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach
            {{ $replies->links() }}

            @if(auth()->check())
                <form action="{{ $thread->path('reply') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="body" class="form-control" rows="6" placeholder="Have something to say..."></textarea>
                    </div>
                    <button class="btn btn-default">Reply</button>
                </form>
            @else
                <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this thread</p>
            @endif
            
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>
                        This thread was posted {{ $thread->created_at->diffForHumans() }} by 
                        <a href="/profiles/{{ $thread->owner->id }}">{{ $thread->owner->name }}</a> and has currently has 
                        {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                    </p>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
