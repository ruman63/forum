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
            };
        },
        methods: {
            toggleLock(){
                axios[ this.locked ? 'delete' : 'post']('/lock-threads/' + this.thread.slug)
                    .then(() => {
                        this.locked = !this.locked
                        flash('Thread has been ' + (this.locked ? 'Locked' : 'Unlocked') + '!')
                    });
            }
        }
    }
</script>
    