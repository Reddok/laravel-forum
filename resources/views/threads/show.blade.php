@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
@endpush

@section('content')
    <thread
            inline-template
            :thread="{{ $thread }}"
            lock-url="{{ route('lock-threads.store', $thread) }}"
            unlock-url="{{ route('lock-threads.destroy', $thread) }}"
            save-url="{{ route('threads.update', ['channel' => $thread->channel, 'thread' => $thread]) }}"
            v-cloak
    >
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('threads.partials._topic')
                    <replies
                            @removed="count--"
                            @added="count++"
                            get-url="{{ route('replies.index', [$thread->channel, $thread]) }}"
                            create-url="{{ route('replies.create', [$thread->channel, $thread]) }}"
                            autocomplete-url="{{ route('api.users.index') }}"
                            best-url="{{ route('best-replies.store', ['reply' => ':id']) }}"
                            can-update-thread="{{ json_encode($thread->can['update']) }}"
                    ></replies>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            This thread has been posted at {{ $thread->created_at }} by <a href="{{ route('profiles.index', $thread->creator->name) }}">{{ $thread->creator->name }}</a> and has
                            @{{count}} {{ str_plural('comment', $thread->replies_count) }}.
                            <p>
                                <subscribe-button
                                        initial-state="{{ json_encode($thread->isSubscribedTo) }}"
                                        subscribe-url="{{ route('threadSubscriptions.create', ['channel' => $thread->channel, 'thread' => $thread]) }}"
                                ></subscribe-button>
                                <button class="btn btn-secondary" v-if="isAdmin()" @click="toggleLock" v-text="locked? 'Unlock' : 'Lock'"></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread>
@endsection