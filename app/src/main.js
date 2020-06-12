import Vue from 'vue'
import App from './App.vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import VueRouter from 'vue-router';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


import SearchComp from './components/SearchComp'
import DeckBuilderComp from './components/DeckBuilderComp'
import ResultsComp from './components/ResultsComp'
import Index from './components/Index'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

Vue.config.productionTip = false

Vue.use(VueRouter);

Vue.prototype.$eventBus = new Vue()

const routes = [
  { path: '/search', component: SearchComp},
  { path: '/deckbuilder', component: DeckBuilderComp},
  { path: '/results', component: ResultsComp, name: 'results', props: true},
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

