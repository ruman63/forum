<template>
    <div>
        <div v-if="signedIn">
            <form @submit.prevent="create">
                <div class="form-group">
                    <textarea name="body" 
                        id="body"
                        class="form-control" 
                        rows="6" 
                        v-model="body"
                        placeholder="Have something to say..."
                        required>
                    </textarea>
                </div>
                <button type="submit" class="btn btn-default">Reply</button>
            </form>
        </div>
        <p v-else>Please <a href="/login">sign in</a> to participate in this thread</p>
    </div>
</template>
<script>
    import 'at.js';
    import 'jquery.caret';
    export default {
        data() {
            return {
                body: ''
            }
        },
        computed: {
            url() {
                return location.pathname + '/replies';
            }
        },
        methods: {
            create() {
                axios.post(this.url, {body: this.body})
                    .catch(error => flash( error.response.data, 'danger' ))
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply was left');
                        this.$emit('created', data);
                    });
            }
        },
        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        console.log(query);
                        axios.get('/api/users', {
                            params: {
                                name: query
                            }
                        }).then(response => callback(response.data));
                    }
                }
            });
        }
    }
</script>
