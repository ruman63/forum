@forelse($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if( auth()->check() && $thread->hasChangedFor(auth()->id()) )
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5>
                        <small>Posted By:</small> <a href="{{ route('profiles', $thread->owner) }}">{{ $thread->owner->name }}</a>
                    </h5>
                </div>
                <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</a>
            </div>
        </div>
        <div class="panel-body">
            <article>
                {{ $thread->body }}
            </article>
        </div>
        <div class="panel-footer">
            <strong>{{ $thread->visits . ' ' . str_plural('visit', $thread->visits) }}</strong>
        </div>
    </div>
@empty
    <div class="panel panel-default">
        <div class="panel-body">
            No Threads found for your query.
        </div>
    </div>
@endforelse