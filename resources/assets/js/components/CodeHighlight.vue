<template>
    <div ref="codeArea">
        <slot></slot>
    </div>
</template>

<script>
    import HighLighter from 'highlight.js';
    import 'highlight.js/styles/foundation.css';

    export default {
        mounted() {
            this.$refs.codeArea.querySelectorAll('pre')
                .forEach(HighLighter.highlightBlock.bind(HighLighter))
        }
    }
</script>