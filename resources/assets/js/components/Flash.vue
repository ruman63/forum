<template>
    <div class="alert alert-success alert-flash" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                show: false,
            }
        },
        methods: {
            flash(message) {
                if (message) {
                    this.body = message;
                    this.show = true;

                    this.hide();
                }
            },
            hide() {
                setTimeout(() => this.show = false, 3000);
            }
        },
        created() {
            this.flash(this.message);

            events.$on('flash', (message) => this.flash(message));
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

