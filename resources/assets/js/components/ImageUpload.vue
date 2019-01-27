<template>
    <div>
        <label :for="id" class="btn btn-primary">Upload</label>
        <input type="file" required @change="onChange" accept="image/*" :id="id" class="d-none">
    </div>
</template>

<script>
    export default {
        props: ['id'],
        methods: {
            onChange(e) {
                let file = e.target.files[0],
                    fileReader = new FileReader();

                if (!file) return;

                fileReader.onload = (e) => {
                    let src = e.target.result;
                    this.$emit('load', {file, src});
                };
                fileReader.readAsDataURL(file);
            }
        }
    }
</script>

<style scoped>

</style>