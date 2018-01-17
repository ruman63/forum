
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import authorizations from './authorizations';
import InstantSearch from 'vue-instantsearch';
window.Vue = require('vue');

Vue.use(InstantSearch);
Vue.prototype.signedIn = window.App.signedIn;
Vue.prototype.authorize = function (...params) {
    if(!window.App.user) return false;
    else if(typeof(params[0]) == 'string') {
        return authorizations[params[0]](params[1]);
    }
    else {
        return params[0](window.App.user);
    }
}
window.events = new Vue();
window.flash = function (message, level = 'success') {
    events.$emit('flash', {message, level});
}


Vue.component('flash', require('./components/Flash.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));
Vue.component('avatar-upload', require('./components/AvatarUpload.vue'));
Vue.component('user-notifications', require('./components/UserNotifications.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app'
});
