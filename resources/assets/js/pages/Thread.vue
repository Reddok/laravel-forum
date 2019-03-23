<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscribeButton';

    export default {
        props: ['thread', 'lockUrl', 'unlockUrl', 'saveUrl', 'pinUrl', 'unpinUrl'],
        components: {Replies, SubscribeButton},
        data() {
            return {
                count: this.thread.replies_count,
                locked: this.thread.locked,
                pinned: this.thread.pinned,
                editing: false,
                form: {}
            };
        },
        created() {
            this.reset();
        },
        methods: {
            toggleLock() {
                let method = this.locked? 'delete' : 'post',
                    url = this.locked? this.unlockUrl : this.lockUrl,
                    state = !this.locked;

                axios[method](url).then((response) => {
                    this.locked = state;
                    flash(response.data);
                })
            },
            togglePin() {
                let method = this.pinned? 'delete' : 'post',
                    url = this.pinned? this.pinUrl : this.unpinUrl,
                    state = !this.pinned;

                axios[method](url).then((response) => {
                    this.pinned = state;
                    flash(response.data);
                })
            },
            reset(replacer = this.thread, replaced = this.form) {
                this.editing = false;
                replaced.title = replacer.title;
                replaced.body = replacer.body;
            },
            save() {
                axios.patch(this.saveUrl, this.form).then(() => {
                    this.reset(this.form, this.thread);
                    flash('Thread successfully updated!');
                });
            },
            classes(state) {
                return [
                    'btn',
                    state? 'btn-primary' : 'btn-default'
                ];
            }
        }
    }
</script>