<template>
    <div>
        <div class="form-group">
            <wysiwyg
                    name="body"
                    id="body"
                    placeholder="Have something to say?"
                    v-model="body"
            >
            </wysiwyg>
        </div>
        <button class="btn btn-primary" type="submit" @click="submit">Submit</button>
    </div>
</template>

<script>
    export default {

        props: ['endpoint', 'autocompleteUrl'],
        data() {
            return {
                body: ''
            }
        },

        mounted() {
            let url = this.autocompleteUrl;
            $("#body").atwho({
                at: '@',
                delay: 2000,
                callbacks: {
                    remoteFilter(query, callback) {
                        console.log(query);
                        axios.get(url, {params: {query}})
                            .then(({data}) => {
                                callback(data);
                            })
                    }
                }
            });

        },

        methods: {
            submit() {
                axios.post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        this.$emit('created', data);
                        flash('A new reply has been added!');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            }
        }

    }
</script>