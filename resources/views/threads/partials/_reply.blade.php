<reply :attributes="{{ $reply }}" v-cloak inline-template>
    <div class="card" id="reply-{{$reply->id}}">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    {{ $reply->created_at->diffForHumans() }} created by
                    <a href="{{ route('profiles.index', $reply->owner->name) }}">{{ $reply->owner->name }}</a>
                </h5>
                @auth
                    <favorite :reply="{{ $reply }}"></favorite>
                @endauth
            </div>

        </div>
        <div class="card-body">
            <div v-if="editMode === false" v-text="body"></div>
            <div class="form-group" v-else>
                <textarea v-model="body" class="form-control"></textarea>
            </div>
        </div>

        <div class="card-footer level reply-buttons">
            <template v-if="editMode === false">
                @can('update', $reply)
                    <button class="btn btn-primary" @click="editMode = true">Edit</button>
                @endcan
                @can('delete', $reply)
                        <button class="btn btn-danger" @click="destroy">Delete</button>
                @endcan
            </template>
            <template v-else>
                @can('update', $reply)
                    <button class="btn btn-success" @click="update">Update</button>
                    <button class="btn" @click="cancel">Cancel</button>
                @endcan
            </template>
        </div>

    </div>
</reply>
