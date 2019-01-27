@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="pb-2 mt-4 mb-2 border-bottom">
                    <avatar-form
                            store-url="{{ route('api.users.avatar.update', $profileUser) }}"
                            can="{{ $profileUser->can }}"
                            profile="{{ $profileUser }}"
                    ></avatar-form>
                    <h1>
                        {{ $profileUser->name }}
                        <small>since {{ $profileUser->created_at->diffForHumans() }}</small>
                    </h1>
                </div>

                @forelse($activities as $date => $activity)
                    <h1>{{ $date }}</h1>
                    @foreach($activity as $record)
                        @include('profiles/activities/' . $record->type)
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection