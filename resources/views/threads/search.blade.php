@extends('layouts.app')

@section('content')
    <ais-index index-name="threads"
               app-id="{{ config('scout.algolia.id') }}"
               api-key="{{ config('scout.algolia.key') }}"
               query="{{ $q }}"
    >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <ais-results>
                        <template slot-scope="{ result }">
                            <div class="card">
                                <div class="card-header">
                                    <div class="level">
                                        <div class="flex">
                                            <h4>
                                                <a :href="result.path">
                                                    <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                                </a>
                                            </h4>
                                            <h5>Posted by <a :href="result.creator.path" v-text="result.creator.name"></a></h5>
                                        </div>
                                        <a :href="result.path" v-text="result.replies_count + ' replies'"></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <highlight :result="result" attribute-name="body"></highlight>
                                </div>
                                <div class="card-footer" v-text="result.visits + ' visits'"></div>
                            </div>
                        </template>
                    </ais-results>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Search
                        </div>
                        <div class="card-body">
                            <ais-search-box>
                                <ais-input
                                        placeholder="Find the thread..."
                                        :autofocus="true"
                                        class="form-control"
                                        style="width: 90%"
                                >
                                </ais-input>
                            </ais-search-box>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Filter By Channel
                        </div>
                        <div class="card-body">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Filter By Creator
                        </div>
                        <div class="card-body">
                            <ais-refinement-list attribute-name="creator.name"></ais-refinement-list>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </ais-index>
@endsection