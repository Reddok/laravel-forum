<template>
    <div>
        <input type="hidden" :name="name" :value="value" id="trix">
        <trix-editor ref="trix" input="trix" @trix-change="input"></trix-editor>
    </div>
</template>

<script>
    import 'trix';
    import 'trix/dist/trix.css'

    export default {
        props: ['name', 'value'],
        watch: {
            value(value) {
                if(value === '') {
                    this.$refs.trix.value = '';
                }
            }
        },
        methods: {
            input(e) {
                this.$emit('input', e.target.innerHTML);
            }
        }
    }
</script>
