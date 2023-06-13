import { api } from "./api.js"
import axios from "axios"
import Vue from 'vue'
import Plotly from 'plotly.js-basic-dist-min'

window.api = api

window.axios = axios

window.Vue = Vue

window.Plotly = Plotly

window.app = {}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.withCredentials = true // ATIVA CREDENCIAL PARA SPA

Vue.component('login',        () => import(/* webpackChunkName: "login" */        '../components/login.vue'))
Vue.component('xpert',        () => import(/* webpackChunkName: "xpert" */        '../components/xpert.vue'))
Vue.component('loading',      () => import(/* webpackChunkName: "loading" */      '../components/loading.vue'))
Vue.component('alert',        () => import(/* webpackChunkName: "alert" */        '../components/alert.vue'))
Vue.component('session',      () => import(/* webpackChunkName: "session" */      '../components/session.vue'))
Vue.component('registration', () => import(/* webpackChunkName: "registration" */ '../components/registration.vue'))
Vue.component('profile',      () => import(/* webpackChunkName: "profile" */      '../components/profile.vue'))
Vue.component('navbar',       () => import(/* webpackChunkName: "navbar" */       '../components/navbar.vue'))
Vue.component('dashboard',    () => import(/* webpackChunkName: "dashboard" */    '../components/dashboard.vue'))
Vue.component('list',         () => import(/* webpackChunkName: "list" */         '../components/list.vue'))
Vue.component('job',          () => import(/* webpackChunkName: "job" */          '../components/job.vue'))
Vue.component('cropper',      () => import(/* webpackChunkName: "cropper" */      '../components/cropper.vue'))
Vue.component('QrcodeVue',    () => import(/* webpackChunkName: "qrcode" */       'qrcode.vue'))

Vue.filter('filter_money', function(value) {
  if (!value) { return '0,00' }
  return Number(value).toLocaleString(
    'pt-BR',
    {
      style: 'currency',
      currency: 'BRL',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
})

Vue.filter('filter_phone', function(value) {
  if (!value) { return 'Não possui' }
  const cleanValue = value.replace(/\D/g, '')
  const match      = cleanValue.match(/^(\d{2})(\d{5})(\d{4})$/)
  if (!match) { return value }
  return `(${match[1]}) ${match[2]}-${match[3]}`
})

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask)

import money from 'v-money'
Vue.use(money, {
  decimal:   ',',
  thousands: '.',
  precision: 2,
  prefix:    'R$ ',
  suffix:    '',
  masked:    false,
})

import VueCropper from "vue-cropper"
Vue.use(VueCropper)

const RHXPERT = new Vue({ el: '#xpert' })