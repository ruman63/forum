{{--  Editing the thread  --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <h4>
            <input type="text" v-model="form.title" class="form-control">
        </h4> 
    </div>
    <div class="panel-body">
        <wysiwyg v-model="form.body"></wysiwyg>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-primary" @click="update">Update</button>
            <button class="btn btn-default btn-xs" @click="resetForm">Cancel</button>
            <form method="POST" class="ml-a" action="{{ $thread->path() }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-link">Delete</button>
            </form>
        </div>
    </div>
</div>
{{--  Viewing the Thread  --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ asset($thread->owner->avatar_path) }}" alt="{{ $thread->owner->name }}" width="25" >
            <h4 class="flex">
                <a href="/profiles/{{ $thread->owner->name }}">{{ $thread->owner->name }}</a> <small>posted</small> <span v-text="title"></span>
            </h4>
        </div>
    </div>
    <div class="panel-body">
        <article v-html="body"></article>
    </div>
    <div class="panel-footer">
        @can('update', $thread)
            <button class="btn btn-default btn-xs" @click="editing = true">Edit</button>
        @endcan
    </div>
</div>