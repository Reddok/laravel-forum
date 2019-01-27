<template>
    <div>
        <div class="mb-2">
            <img :src="avatar" :alt="user.name" width="200" height="200">
        </div>
        <image-upload name="avatar" v-if="possibilities.update" @load="onLoad" id="avatar"></image-upload>
    </div>
</template>

<script>
    export default {
        props: ['can', 'storeUrl', 'profile'],
        data() {
            let user = JSON.parse(this.profile);

            return {
                user: user,
                avatar: user.avatar_path,
                possibilities: JSON.parse(this.can)
            }
        },

        methods: {
            onLoad(data) {
                this.avatar = data.src;
                this.persist(data.file);
            },
            persist(file)
            {
                let formData = new FormData();
                formData.append('avatar', file);

                axios.post(this.storeUrl, formData)
                    .then(() => {
                        console.log('uploaded');
                        flash('Avatar has been updated!')
                    })
            }
        }
    }
</script>

<style scoped>

</style>
