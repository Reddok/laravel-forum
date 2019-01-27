<template>
    <nav aria-label="Page navigation" v-if="shouldPaginate">
        <ul class="pagination">
            <li class="page-item" v-show="prevUrl">
                <a class="page-link" :href="prevUrl" aria-label="Previous" @click.prevent="paginate(prevUrl)">
                    <span aria-hidden="true">&laquo; Previous</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item active">
                <span class="page-link">
                    {{ page }}
                    <span class="sr-only">(current)</span>
                </span>
            </li>
            <li class="page-item" v-show="nextUrl">
                <a class="page-link" :href="nextUrl" aria-label="Next" @click.prevent="paginate(nextUrl)">
                    <span aria-hidden="true">Next &raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>

    export default {
        props: ['dataSet'],
        data() {
            return {
                prevUrl: false,
                nextUrl: false,
                page: 1
            }
        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
                window.history.pushState(null, null, '?page=' + this.page)
            }
        },

        computed: {
            shouldPaginate() {
                return !!(this.prevUrl || this.nextUrl);
            }
        },

        methods: {
            paginate(url) {
                this.$emit('paginate', url);
                this.updateUrl();
            }
        }

    }

</script>