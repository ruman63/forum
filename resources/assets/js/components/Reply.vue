<script>
    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                reply: {},
            };
        },
        methods: {
            updateReply() {
                axios.patch('/replies/' + this.reply.id, {
                    body: this.reply.body,
                });
                this.editing = false;
                flash("Your reply has been updated!");
            }
        },
        created() {
            this.reply = this.attributes;
        }
    }
</script>
