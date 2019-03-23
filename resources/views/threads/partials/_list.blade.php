@forelse($threads as $thread)
    <div class="card">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if($thread->pinned)
                                <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>
                            @endif

                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
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