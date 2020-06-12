import Vue from 'vue'
import App from './App.vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import VueRouter from 'vue-router';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


import AdvancedSearchComp from './components/AdvancedSearchComp'
import DeckBuilderComp from './components/DeckBuilderComp'
import SearchComp from './components/SearchComp'
import Index from './components/Index'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

Vue.config.productionTip = false

Vue.use(VueRouter);

Vue.prototype.$eventBus = new Vue()

const routes = [
  { path: '/advancedsearch', component: AdvancedSearchComp, name: 'advancedsearch'},
  { path: '/deckbuilder', component: DeckBuilderComp, name: 'deckbuilder'},
  { path: '/search', component: SearchComp, name: 'search', props: true},
  { path: '/', component: Index, name: 'index'},
]

const router = new VueRouter({
  routes,
  mode: 'history'
})


new Vue({
  el:'#app',
  router,
  render: h => h(App)
});

