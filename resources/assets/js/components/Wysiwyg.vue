<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor ref="editor" input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>
<script>
    import Trix from 'trix';
    export default {
        props: ['name', 'placeholder', 'value', 'shouldClear'],
        mounted() {
            this.$refs.editor.addEventListener('trix-change', (e) => {
                this.$emit('input', e.target.innerHTML);
            });
            this.$watch('value', () => {
                if(this.value == '') {
                    this.$refs.editor.innerHTML = '';
                }
            });
        }    
    }
</script>
<style>
@import '~trix/dist/trix.css';
</style>

