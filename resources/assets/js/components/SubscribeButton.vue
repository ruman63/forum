<template>
    <button :class="classes" @click="toggleSubscription" v-text="btnText"></button>
</template>

<script>
export default {
    props: [ 'active' ],
    computed: {
        btnText() {
            return this.active ? 'Unsubscribe' : 'Subscribe'; 
        },
        classes() {
            return ['btn', this.active ? 'btn-primary' : 'btn-default'];
        }
    },
    methods: {
        toggleSubscription() {
            axios[
                (this.active ? 'delete' : 'post')
            ](location.pathname + '/subscriptions');
            this.active = !this.active;
        }
    }

}
</script>

