<template>
    <div class="alert alert-flash" 
        :class="'alert-'+level" 
        v-show="show" 
        v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                level: 'success',
                show: false,
            }
        },
        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                    this.show = true;

                    this.hide();
                }
            },
            hide() {
                setTimeout(() => this.show = false, 3000);
            }
        },
        created() {
            this.flash({message: this.message, level:'success'});

            events.$on('flash', (data) => this.flash(data));
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 20px;
        bottom: 20px;
    }
</style>

