import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import http from 'http-client';
import AWN from "awesome-notifications";

import SubscriptionManagementForm from './components/SubscriptionManagementForm';
import SubscriptionDownload from "./components/SubscriptionDownload";

Vue.prototype.$http = http;
Vue.prototype.$notify = new AWN({position: 'top-right'});
Vue.use(BootstrapVue);

let vue = new Vue({
    el: '#flyerless-subscription-management-root',


    components: {
        SubscriptionManagementForm,
        SubscriptionDownload
    }
});