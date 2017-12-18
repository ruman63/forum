<template>
    <div>
        <div v-for="(reply, index) in items"  :key="index">
            <reply  :data="reply" @deleted="remove(index)" ></reply>
        </div>
        <new-reply :action="action" @created="add"></new-reply>
    </div>
</template>

<script>
import Reply from './Reply.vue';
import NewReply from './NewReply.vue';
export default {
    props: ['data'],
    components: { Reply, NewReply },
    data() {
        return {
            items: this.data,
            action: window.location.pathname +'/reply'
        }
    },
    methods: {
        add(reply) {
            this.items.push(reply);

            this.$emit('added');
        },
        remove(index) {
            this.items.splice(index, 1);    

            flash('Your reply was deleted!')
            
            this.$emit('removed');
        }
    }
}
</script>

