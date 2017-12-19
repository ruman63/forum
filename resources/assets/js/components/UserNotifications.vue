<template>
    <li v-if="notifications.length">
      <a href="#" class="dropdown" data-toggle="dropdown">
        <span class="glyphicon glyphicon-bell"></span>
        <span class="badge badge-primary" v-text="notifications.length"></span>
      </a>
      <ul class="dropdown-menu">
        <li v-for="notification in notifications" :key="notification.id">
          <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
        </li>
      </ul>
    </li>
</template>
<script>
export default {
    data() {
      return {
        notifications: []
      }
    },
    created() {
      axios.get('/profiles/' + window.App.user.name + '/notifications')
        .then(({data}) => this.notifications = data);
    },
    methods: {
      markAsRead(notification) {
        axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
      }
    }
}
</script>
