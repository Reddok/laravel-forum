<template>
    <div class="alert alert-success alert-flash" :class="'alert-' + level" role="alert" v-show="show">
        {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                level: 'success',
                show: false
            }
        },
        created() {
            if (this.message) {
                try {
                    let obj = JSON.parse(this.message);
                    this.flash(obj.message, obj.level);
                } catch(e) {
                    this.flash(this.message);
                }
            }

            window.events.$on('flash', data => this.flash(data.message, data.level));
        },

        methods: {

            flash(message, level) {
                this.body = message;
                this.level = level || 'success';
                this.show = true;

                setTimeout(this.hide.bind(this), 3000);
            },
            hide() {
                this.show = false;
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
