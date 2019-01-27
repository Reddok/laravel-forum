<template>
    <div class="dropdown" v-show="notifications.length">
        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-bell"></span> {{ notifications.length }}
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a v-for="notification in notifications" class="dropdown-item" :href="notification.data.link" @click="read(notification)">
                {{ notification.data.message }}
            </a>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['fetchUrl', 'readUrl'],
        data() {
            return {
                notifications: []
            };
        },
        created() {
            axios.get(this.fetchUrl)
                .then(({data}) => {
                    this.notifications = data;
            })
        },

        methods: {
            read(notification) {
                axios.delete(this.readUrl + '/' + notification.id);
            }
        }

    }

</script>