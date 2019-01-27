@component('profiles.activities.activity_item')
    @slot('heading')
        <a href="{{ $profileUser->path() }}">{{ $profileUser->name }}</a> published
        <a href="{{ $record->subject->path() }}">{{ $record->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $record->subject->body }}
    @endslot
@endcomponent