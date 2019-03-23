<template>
    <li>
        <div class="dropdown" :class="{show: toggle}">
        <a
                @click="toggle = !toggle"
                aria-haspopup="true"
                aria-expanded="false"
                data-toggle="dropdown"
                class="btn btn-secondary dropdown-toggle"
                type="button"
        >
            Channels <span class="caret"></span>
        </a>

        <div class="dropdown-menu channel-dropdown" aria-labelledby="dropdownMenuLink">
            <div class="input-wrapper">
                <input type="text" class="form-control" v-model="filter" placeholder="Filter Channels...">
            </div>

            <ul class="list-group channel-list">
                <li class="list-group-item dropdown-item" v-for="channel in filteredThreads">
                    <a :href="`/threads/${channel.slug}`" v-text="channel.name"></a>
                </li>
            </ul>
        </div>
        </div>
    </li>
</template>

<script>
    export default {
        props: ['channels'],
        data() {
            return {
                filter: '',
                toggle: false
            }
        },
        computed: {
            filteredThreads() {
                return this.channels.filter(channel => {
                    return channel.name.toLowerCase().startsWith(this.filter.toLocaleLowerCase());
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .channel-dropdown {
        padding: 0;
    }

    .input-wrapper {
        padding: .5rem 1rem;
    }

    .channel-list {
        max-height: 400px;
        overflow: auto;
        margin-bottom: 0;

        .list-group-item {
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
    }
</style>