@extends('layouts.app')

@push('initialScripts');
<script src='https://www.google.com/recaptcha/api.js?render=6LfHwnkUAAAAAGFy5YqSOBj813cy_IUszU1UcCrq'></script>
@endpush
@push('scripts')
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LfHwnkUAAAAAGFy5YqSOBj813cy_IUszU1UcCrq', {action: 'thread_creation'})
                .then(function(token) {
                    var $form = $('#createThreadForm');

                    $form.append('<input type="hidden" value="' + token + '" name="g-recaptcha-response"/>');
                    $form.find('button[type=submit]').attr('disabled', false);
                });
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create a new thread
                    </div>
                    <div class="card-body">
                        <form action="{{ route('threads.store') }}" method="POST" id="createThreadForm">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="channel">Channel:</label>
                                <select name="channel_id" id="channel" class="form-control">
                                    <option value="">Choose one...</option>

                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ $channel->id == old('channel_id')? ' selected ' : '' }}>{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <wysiwyg name="body" value="{{ old('body') }}"></wysiwyg>
                            </div>
                            <button class="btn btn-primary" type="submit" disabled>Submit</button>
                        </form>
                        @if(count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection