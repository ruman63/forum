@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h2>
            {{ $profileUser->name }} <small>since {{ $profileUser->created_at->diffForHumans() }}</small> 
            </h2>
        </div>
        @foreach($threads as $thread)
             <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="level">
                    <span class="flex">
                        <a href="{{ $thread->path() }}"> {{ $thread->title }} </a>
                    </span>
                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                </h4> 
                </div>
                <div class="panel-body">
                    <article>
                        {{ $thread->body }}
                    </article>
                </div>
            </div>
        @endforeach

        {{ $threads->links() }}
    </div>
@endsection