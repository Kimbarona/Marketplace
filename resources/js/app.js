window.Vue = require("vue");

import Vue from "vue";
import Vuetify from "../plugins/vuetify";
import router from "./router/index";
import store from "./store";
import App from "../js/components/App.vue"
import font from "@fortawesome/fontawesome-free/css/all.css";
import "material-design-icons-iconfont/dist/material-design-icons.css";
import "vuetify/dist/vuetify.min.css";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import VueSweetalert2 from "vue-sweetalert2";
import FlashMessage from "@smartweb/vue-flash-message";
import VueConfirmDialog from "vue-confirm-dialog";

Vue.use(VueConfirmDialog);
Vue.use(FlashMessage);
Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.component("vue-confirm-dialog", VueConfirmDialog.default);

// Vue.component("app-container",require("./components/Home.vue").default);

new Vue({
    vuetify: Vuetify,
    el: "#app",
    font,
    router,
    store,
    render: h => h(App)
});
