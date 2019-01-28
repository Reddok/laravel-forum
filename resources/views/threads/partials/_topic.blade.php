<div class="card" v-if="editing">
    <div class="card-header">
        <input type="text" class="form-control" v-model="form.title">
    </div>
    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-success btn-sm" @click="save">Save</button>
        <button class="btn btn-sm" @click="reset()">Cancel</button>
    </div>
</div>
<div class="card" v-else>
    <div class="card-header">
        <div class="level">
                            <span class="flex">
                                <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25">
                                <a href="{{ route('profiles.index', $thread->creator) }}">
                                    {{ $thread->creator->name }} ({{ $thread->creator->reputation }}XP)
                                </a> posted:
                                <span v-text="thread.title"></span>
                            </span>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-danger">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
    <div class="card-body" v-html="thread.body"></div>
    @can('update', $thread)
        <div class="card-footer">
            <button class="btn btn-primary btn-sm" @click="editing=true">Edit</button>
        </div>
    @endcan
</div>