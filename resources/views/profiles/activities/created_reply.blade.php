@component('profiles.activities.activity_item')
    @slot('heading')
        <a href="{{ $profileUser->path() }}">{{ $profileUser->name }}</a> replied to
        <a href="{{ $record->subject->thread->path() }}">{{ $record->subject->thread->title }}</a>
    @endslot

    @slot('body')
        {!! $record->subject->body !!}
    @endslot
@endcomponent