<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
export default {
    props: ['reply'],
    data() {
        return {
            active: this.reply.isFavorited,
            count: this.reply.favoritesCount,
            endpoint: '/replies/' + this.reply.id + '/favorite'
        }
    },
    methods: {
        toggle(){
            return this.active ? this.unfavorite() : this.favorite();
        },
        favorite() {
            axios.post(this.endpoint);
            this.active = true;
            this.count++;
        },
        unfavorite() {
            axios.delete(this.endpoint);
            this.active = false;
            this.count--;
        }
    },
    computed: {
        classes() {
            return ['btn', this.active ? 'btn-primary' : 'btn-default'];
        }
    }
}
</script>
