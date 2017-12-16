<div class="panel panel-default">
    <div class="panel-heading level">
        <h5 class="flex">
            <a href="/profiles/{{ $reply->owner->id }}">{{ $reply->owner->name }}</a> said 
            {{ $reply->created_at->diffForHumans() }}
        </h5>
        @if(auth()->check())
            <div>
                <form method="POST" action="{{ route('replies.favorite', $reply->id) }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count." ".str_plural('Favorite', $reply->favorites_count) }} 
                    </button>
                </form>
            </div>
        @else
            <strong> {{ $reply->favorites_count." ".str_plural('Favorite', $reply->favorites_count) }} </strong>
        @endif
    </div>
    <div class="panel-body">
        <article>
            {{ $reply->body }}
        </article>
    </div>
</div>