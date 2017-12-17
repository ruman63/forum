@component('profiles.activities.activity')
    @slot('heading')
        <a href="/profiles/{{ $profileUser->name }}">{{ $profileUser->name }}</a> <small>left a reply to</small>
        <a href="{{ $activity->subject->thread->path() }}"> {{ $activity->subject->thread->title }} </a>
    @endslot
    @slot('body')
        <article>
            {{ $activity->subject->body }}
        </article>
    @endslot
@endcomponent