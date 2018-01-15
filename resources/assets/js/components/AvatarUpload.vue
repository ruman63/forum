<template>
    <div>
        <div class="level">
            <img :src="avatar" :alt="user.name" width="50" height="50">
            <h2  class="flex" v-text="user.name"></h2>
        </div>
        <form v-if="authorize('updateProfile',user)">
            <image-upload name="avatar" @uploaded="persist"></image-upload>
        </form>
    </div>
</template>
<script>
import ImageUpload from './ImageUpload.vue';
export default {
    props: ['user'],
    components: { ImageUpload },
    data() {
        return {
            avatar: this.user.avatar_path
        }
    },
    computed: {
        canUpload() {
            return this.authorize(user => user.id == this.user.id);
        },
    },
    methods: {
        persist(data) {
            var formData = new FormData();
            formData.append('avatar', data.file);
            axios.post(`/api/users/${this.user.name}/avatar`, formData)
                .then((response) => {
                    this.avatar = data.src;
                    flash('Avatar Updated!');
                });
        }
    }
}
</script>
