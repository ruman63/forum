<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';

    export default {
        props: ['thread'],
        components: { Replies, SubscribeButton },
        data() {
            return {
                repliesCount: this.thread.repliesCount,
                locked: this.thread.locked,
                title: this.thread.title,
                body: this.thread.body,
                form: {},
                editing: false,
            };
        },
        methods: {
            toggleLock(){
                let url = `/lock-threads/${this.thread.slug}`
                axios[this.locked ? 'delete' : 'post'](url).then(() => {
                    this.locked = !this.locked
                    flash('Thread has been ' + (this.locked ? 'Locked' : 'Unlocked') + '!')
                });
            },
            update(){
                let url = `/threads/${this.thread.channel.slug}/${this.thread.slug}`
                axios.patch(url, this.form).then(() => {
                    flash('Your thread has been updated!');
                    this.title = this.form.title;
                    this.body = this.form.body;
                    this.editing = false;
                });
            },
            resetForm(){
                this.form = {
                    title: this.thread.title,
                    body: this.thread.body
                }
                this.editing = false;
            }
        },
        created() {
            this.resetForm();
        }
    }
</script>
    