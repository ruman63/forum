<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> 
                <small>said</small> 
                {{ $reply->created_at->diffForHumans() }}
            </span>
            @if(auth()->check())
                <form method="POST" action="{{ route('replies.favorite', $reply->id) }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count." ".str_plural('Favorite', $reply->favorites_count) }} 
                    </button>
                </form>
            @else
                <strong> {{ $reply->favorites_count." ".str_plural('Favorite', $reply->favorites_count) }} </strong>
            @endif
        </div>
    </div>
    <div class="panel-body">
        <article>
            {{ $reply->body }}
        </article>
    </div>
</div>