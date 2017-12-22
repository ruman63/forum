<template>
    <input type="file" accept="image/*" @change="change">
</template>
<script>
export default {
    methods: {
        change(event) {
            if(!event.target.files.length) return;
            let file = event.target.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = () => {
                let src = reader.result;
                this.$emit('uploaded', { src, file });
            }
        }
    }
}
</script>

