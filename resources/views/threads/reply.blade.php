<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <span class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> 
                    <small>said</small> 
                    {{ $reply->created_at->diffForHumans() }}
                </span>
                @if(auth()->check())
                    <favorite :reply="{{ $reply }}"></favorite>
                @else
                    <strong> {{ $reply->favorites_count." ".str_plural('Favorite', $reply->favorites_count) }} </strong>
                @endif
                
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <div class="level">
                    <button class="btn btn-primary btn-xs" @click="update">Update</button>
                    <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>
                </div>
            </div>
            <article v-else v-text="body"></article>
        </div>
        @can('update', $reply)
            <div class="panel-footer">
                <div class="level">
                    <button class="btn btn-default btn-xs" @click="editing = true" >Edit</button>
                    <button class="btn btn-danger btn-xs" @click="destroy"> Delete </button>
                </div>
            </div>
        @endcan
    </div>
</reply>