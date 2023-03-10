import { api } from "./api.js";
import axios from "axios";
import Vue from 'vue'

window.api = api;

window.axios = axios;

window.Vue = Vue;

window.app = {};

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; // ATIVA CREDENCIAL PARA SPA

Vue.component('login',        () => import(/* webpackChunkName: "login" */ '../components/login.vue'));
Vue.component('xpert',        () => import(/* webpackChunkName: "xpert" */ '../components/xpert.vue'));
Vue.component('loading',      () => import(/* webpackChunkName: "loading" */ '../components/loading.vue'));
Vue.component('alert',        () => import(/* webpackChunkName: "alert" */ '../components/alert.vue'));
Vue.component('session',      () => import(/* webpackChunkName: "session" */ '../components/session.vue'));
Vue.component('registration', () => import(/* webpackChunkName: "registration" */ '../components/registration.vue'));
Vue.component('profile',      () => import(/* webpackChunkName: "profile" */ '../components/profile.vue'));
Vue.component('navbar',       () => import(/* webpackChunkName: "navbar" */ '../components/navbar.vue'));
Vue.component('dashboard',    () => import(/* webpackChunkName: "dashboard" */ '../components/dashboard.vue'));
Vue.component('list',         () => import(/* webpackChunkName: "list" */ '../components/list.vue'));
Vue.component('contact',      () => import(/* webpackChunkName: "contact" */ '../components/contact.vue'));
Vue.component('job',          () => import(/* webpackChunkName: "job" */ '../components/job.vue'));

const RHXPERT = new Vue({ el: '#xpert' });