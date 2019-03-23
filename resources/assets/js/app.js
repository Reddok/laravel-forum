
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import InstantSearch from 'vue-instantsearch';

window.Vue.prototype.signedIn = function() {
    return window.App && window.App.signedIn;
};
window.Vue.prototype.isAdmin = function() {
    return window.App && window.App.isAdmin;
};

window.Vue.use(InstantSearch);
window.Vue.config.ignoredElements = ['trix-editor'];
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('flash', require('./components/Flash.vue'));
Vue.component('thread', require('./pages/Thread.vue'));
Vue.component('reply', require('./components/Reply.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));
Vue.component('user-notifications', require('./components/UserNotifications.vue'));
Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('image-upload', require('./components/ImageUpload.vue'));
Vue.component('wysiwyg', require('./components/Wysiwyg'));
Vue.component('highlight', require('./components/Highlight'));
Vue.component('code-highlight', require('./components/CodeHighlight'));
Vue.component('channel-dropdown', require('./components/ChannelDropdown'));


const app = new Vue({
    el: '#app'
});
