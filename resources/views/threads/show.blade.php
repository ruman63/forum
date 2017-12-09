@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4>
                    {{ $thread->title }}
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
</div>
@endsection
