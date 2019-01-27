<script>
    import {Highlight} from 'vue-instantsearch';

    export default {
        extends: Highlight,
        data() {
            console.log('data');
            return {};
        },
        beforeCreate() {
            console.log('before create');
        },
        created() {
            console.log('created');
        },
        render(h, ctx) {
            let txt = document.createElement('textarea');
            txt.innerHTML = ctx.props.result._highlightResult[ctx.props.attributeName].value;
            ctx.props.result._highlightResult[ctx.props.attributeName].value = txt.value;

            return Highlight.render(h, ctx);
        }
    }
</script>
