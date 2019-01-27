<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :attributes="reply" :raw-best-url="bestUrl" :autocomplete-url="autocompleteUrl"  :can-update-thread="canUpdateThread == 'true'? true:false" @deleted="remove(index)"></reply>
        </div>
        <paginator :dataSet="dataSet" @paginate="fetch"></paginator>
        <p  class="text-center" v-if="!signedIn()">Please <a href="/login">Sign-In</a> to participate in this discussion.</p>
        <p  class="text-center" v-else-if="$parent.locked">This thread has been locked. No more replies allowed.</p>
        <div v-else>
            <new-reply :endpoint="createUrl" :autocomplete-url="autocompleteUrl" @created="add"></new-reply>
        </div>

    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply'
    import collection from './mixins/collection'

    export default {
        props: ['createUrl', 'getUrl', 'autocompleteUrl', 'bestUrl', 'canUpdateThread'],
        mixins: [collection],
        components: {Reply, NewReply},
        data() {
            return {
                dataSet: {}
            }
        },
        created() {
            this.fetch(this.url);
        },
        methods: {
            fetch(url) {
                axios.get(url)
                    .then(this.refresh);
            },
            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
                window.scrollTo(0,0);
            },
        },

        computed: {
            url() {
                let search = location.search;
                return this.getUrl + search;
            }
        }
    }
</script>