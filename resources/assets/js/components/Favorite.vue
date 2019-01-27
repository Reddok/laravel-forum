<template>
    <button :class="classes.join(' ')" @click="toggle">
        <span v-if="processing" class="glyphicon glyphicon-refresh glyphicon-spin"></span>
        <template v-else>
            <span class="glyphicon glyphicon-heart"></span>
            {{this.count}}
        </template>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited,
                processing: false
            }
        },
        computed: {
            classes() {
                return ['btn', this.active? 'btn-primary' : 'btn-default'];
            },
            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },
        methods: {
            toggle() {
                var promise;
                this.processing = true;
                promise = this.active? this.destroy() : this.create();

                promise.then(() => this.processing = false);
            },
            destroy() {
                return axios.delete(this.endpoint)
                    .then(() => {
                        flash('This reply has been unfavorited');
                        this.count--;
                        this.active = false;
                    })
            },
            create() {
                return axios.post(this.endpoint)
                    .then(() => {
                    flash('This reply has been favorited');
                    this.count++;
                    this.active = true;
                });
            }
        }
    }
</script>