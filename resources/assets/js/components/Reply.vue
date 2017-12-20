<template>
    <div :id="'reply-' + id " class="panel panel-default">
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
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <div class="level">
                        <button class="btn btn-primary btn-xs" type="submit">Update</button>
                        <button class="btn btn-link btn-xs" type="button" @click="cancel">Cancel</button>
                    </div>
                </form>
            </div>
            <article v-else v-html="body"></article>
        </div>
        <div class="panel-footer" v-if="canUpdate">
            <div class="level">
                <button class="btn btn-default btn-xs" @click="editing = true" >Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy"> Delete </button>
            </div>
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
            signedIn() {
                return window.App.signedIn;
            },
            ago() {
                return moment(this.reply.created_at).fromNow();
            },
            canUpdate() {
                return this.authorize(user => {
                    return user.id == this.reply.user_id;
                })
            }     
        },
        data() {
            return {
                id: this.data.id,
                editing: false,
                reply: this.data,
                body: '',
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
                editing = false; 
                this.body = this.data.body;
            },
            destroy() {
                axios.delete('/replies/' + this.data.id)
                    .then((response) => flash('Your reply was deleted!'));
                this.$emit('deleted');
            }
        },
        created() {
            this.body = this.data.body;
        }
    }
</script>
