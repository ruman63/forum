@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @forelse($threads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                            </span>
                            <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <article>
                            {{ $thread->body }}
                        </article>
                    </div>
                </div>
            @empty
                <div class="panel panel-default">
                    <div class="panel-body">
                        No Threads found for your query.
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</div>
@endsection
