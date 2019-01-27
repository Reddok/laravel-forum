@forelse($threads as $thread)
    <div class="card">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <a href="{{ $thread->path() }}"><strong>{{ $thread->title }}</strong></a>
                        @else
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                        @endif
                    </h4>
                    <h5>Posted by <a href="{{ route('profiles.index', ['user' => $thread->creator]) }}">{{ $thread->creator->name }}</a></h5>
                </div>
                <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</a>
            </div>
        </div>
        <div class="card-body">
            {!! $thread->body !!}
        </div>
        <div class="card-footer">
            {{ $thread->visits->count() }} visits
        </div>
    </div>
@empty
    <p>There is no posts</p>
@endforelse