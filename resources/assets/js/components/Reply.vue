<template>
    <div class="card" :class="isBest? 'border-success' : ''" :id="'reply-'+ attributes.id">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <span v-text="ago"></span> created by
                    <a :href="'/profiles/' + attributes.owner.name">{{ attributes.owner.name }}</a>
                </h5>

                <favorite :reply="attributes" v-if="signedIn()"></favorite>
            </div>

        </div>

        <form @submit.prevent="update">
            <div class="card-body">
                <div v-if="editMode === false" v-html="body"></div>
                <div class="form-group" v-else>
                    <wysiwyg v-model="body"></wysiwyg>
                </div>
            </div>

            <div class="card-footer level reply-buttons" v-if="attributes.can.update || attributes.can.delete || !isBest && canUpdateThread">
                <template v-if="editMode === false">
                    <button class="btn btn-primary" type="button" @click="editMode = true" v-if="attributes.can.update">Edit</button>
                    <button class="btn btn-danger" type="button" @click="destroy" v-if="attributes.can.delete">Delete</button>
                    <button class="btn btn-success ml-a" type="button" @click="markAsBest" v-if="!isBest && canUpdateThread">Mark as Best</button>
                </template>
                <template v-else-if="attributes.can.update">
                    <button class="btn btn-success" type="submit">Update</button>
                    <button class="btn" @click="cancel" type="button">Cancel</button>
                </template>
            </div>
        </form>

    </div>
</template>

<script>
    import Favorite from './Favorite';
    import moment from 'moment';
    import 'at.js';
    import 'jquery.caret';

    export default {
        components: {Favorite},
        props: {
            attributes: Object,
            autocompleteUrl: String,
            rawBestUrl: String,
            canUpdateThread: Boolean
        },
        data() {
            return {
                body: this.attributes.body || '',
                old: this.attributes.body || '',
                editMode: false,
                isBest: this.attributes.isBest
            }
        },

        computed: {
             ago: function() {
                 return moment(this.attributes.created_at).fromNow();
             },
            bestUrl: function() {
                 return this.rawBestUrl.replace(':id', this.attributes.id);
            }
        },

        created() {
            window.events.$on('best:selected', id => {
                this.isBest = +this.attributes.id === +id;
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {body: this.body})
                    .then(() => {
                        this.editMode = false;
                        this.old = this.body;
                        flash('Reply edited!');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
            },
            cancel() {
                this.body = this.old;
                this.editMode = false;
            },
            destroy() {
                axios.delete('/replies/' + this.attributes.id)
                    .then(() => {
                        let $el = $(this.$el);
                        $el.fadeOut(300, () => {
                            this.$emit('deleted');
                            flash('The reply has been removed!');
                        });
                    });
            },
            markAsBest() {
                axios.post(this.bestUrl).then(() => {
                    window.events.$emit('best:selected', this.attributes.id);
                    flash('Reply marked as best!');
                });
            }
        }
    }
</script>
