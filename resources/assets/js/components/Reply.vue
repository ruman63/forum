<template>
    <div :id="'reply-' + id " class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <span class="flex">
                    <a :href="'/profiles/' + reply.owner.name " v-text="reply.owner.name"></a> 
                    <small>said</small> 
                    <span v-text="reply.created_at"></span>
                </span>
                <favorite :reply="reply" v-if="signedIn"></favorite>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <div class="level">
                    <button class="btn btn-primary btn-xs" @click="update">Update</button>
                    <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>
                </div>
            </div>
            <article v-else v-text="body"></article>
        </div>
        <!-- @can('update', $reply) -->
            <div class="panel-footer" v-if="canUpdate">
                <div class="level">
                    <button class="btn btn-default btn-xs" @click="editing = true" >Edit</button>
                    <button class="btn btn-danger btn-xs" @click="destroy"> Delete </button>
                </div>
            </div>
        <!-- @endcan -->
    </div>
</template>


<script>
    import Favorite from './Favorite.vue';
    export default {
        props: ['data'],
        components: { Favorite },
        computed: {
            signedIn() {
                return window.App.signedIn;
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
                });
                this.editing = false;
                flash("Your reply has been updated!");
            },
            destroy() {
                axios.delete('/replies/' + this.data.id); 
                this.$emit('deleted');
            }
        },
        created() {
            this.body = this.data.body;
        }
    }
</script>
