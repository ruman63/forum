<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body" 
                    class="form-control" 
                    rows="6" 
                    v-model="body"
                    placeholder="Have something to say...">
                </textarea>
            </div>
            <button class="btn btn-default" @click="create">Reply</button>
        </div>
        <p v-else>Please <a href="/login">sign in</a> to participate in this thread</p>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                body: ''
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            url() {
                return location.pathname + '/reply';
            }
        },
        methods: {
            create() {
                axios.post(this.url, {body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply was left');
                        this.$emit('created', data);
                    });
            }
        }
    }
</script>
