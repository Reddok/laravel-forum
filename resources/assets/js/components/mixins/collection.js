export default {
    data() {
        return {
            items: []
        }
    },


    methods: {
        remove(index) {
            this.items.splice(index, 1);
            this.$emit('removed');
        },
        add(data) {
            this.items.push(data);
            this.$emit('added');
        }
    }
}