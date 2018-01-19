<template>
    <div :id="'reply-' + id " class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <span class="flex">
                    <a :href="'/profiles/' + reply.owner.name " v-text="reply.owner.name"></a> 
                    <small>said</small> 
                    <span v-text="ago"></span>
                </span>
                <favorite :reply="reply" v-if="signedIn"></favorite>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>
                    <div class="level">
                        <button class="btn btn-primary btn-xs" type="submit">Update</button>
                        <button class="btn btn-link btn-xs" type="button" @click="cancel">Cancel</button>
                    </div>
                </form>
            </div>
            <article v-else v-html="body"></article>
        </div>
        <div class="panel-footer level" v-if="authorize('updateReply', reply) || ( authorize('updateThread', reply.thread) && !isBest )">
            <div v-show="authorize('updateReply', reply)">
                <button class="btn btn-default btn-xs" @click="editing = true" >Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy"> Delete </button>
            </div>
            <button class="btn btn-success btn-xs ml-a" @click="markBest" v-if="authorize('updateThread', reply.thread) && !isBest"> Best Reply? </button>
        </div>
    </div>
</template>


<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    export default {
        props: ['data'],
        components: { Favorite },
        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow();
            }     
        },
        data() {
            return {
                id: this.data.id,
                editing: false,
                reply: this.data,
                body: '',
                isBest: this.data.isBest,
            };
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body,
                })
                .then(response => {flash("Your reply has been updated!")})
                .catch(error => {
                    console.log("error");
                    flash(error.response.data, 'danger');
                    this.body = this.data.body;
                });
                this.editing = false;
            },
            cancel() {
                this.editing = false; 
                this.body = this.data.body;
            },
            destroy() {
                axios.delete('/replies/' + this.data.id)
                    .then((response) => flash('Your reply was deleted!'));
                this.$emit('deleted');
            },
            markBest() {
                axios.post('/replies/' + this.id + '/best')
                    .then(() => {
                        flash('Best reply was marked!');
                        window.events.$emit('best-reply-selected', this.id)
                    });
            }
        },
        created() {
            this.body = this.data.body;
            window.events.$on('best-reply-selected', (id) => {
                this.isBest = (this.id === id);
            });
        }
    }
</script>
