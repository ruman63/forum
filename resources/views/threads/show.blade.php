@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="level">
                        <h4 class="flex">
                            <a href="/profiles/{{ $thread->owner->name }}">{{ $thread->owner->name }}</a> <small>posted</small> {{ $thread->title }}
                        </h4> 
                        @if(Auth::check())
                            <form method="POST" action="{{ '/threads/'. $thread->channel->slug . '/' . $thread->id }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-link">Delete</button>
                            </form>
                        @endif
                    </div>
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
                        <a href="/profiles/{{ $thread->owner->name }}">{{ $thread->owner->name }}</a> and has currently has 
                        {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                    </p>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
