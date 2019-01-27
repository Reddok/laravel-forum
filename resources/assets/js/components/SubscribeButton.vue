<template>
    <button :class="classes" v-text="text" @click="subscribeToggle"></button>
</template>
<script>

    export default {
        props: ['initialState', 'subscribeUrl'],
        data: function() {
            return {
                active: JSON.parse(this.initialState)
            }
        },
        computed: {
            classes() {
                return ['btn', this.active? 'btn-default' : 'btn-primary'];
            },
            text() {
                return this.active? 'Unsubscribe' : 'Subscribe';
            }
        },
        methods: {
            subscribeToggle() {
                let method = this.active? 'delete' : 'post',
                    message = this.active?  'Thread unsubscribed!' : 'Thread subscribed!';

                axios[method](this.subscribeUrl).then(() => {
                    this.active = !this.active;
                    flash(message);
                });
            }
        }
    }

</script>