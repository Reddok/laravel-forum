@component('profiles.activities.activity_item')
    @slot('heading')
        {{ $profileUser->name }} favorited reply
        <a href="{{ $record->subject->favorited->path() }}">{{ str_limit($record->subject->favorited->body, 5, '...') }}</a>
    @endslot

    @slot('body')
        {{ $record->subject->favorited->body }}
    @endslot
@endcomponent