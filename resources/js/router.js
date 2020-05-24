import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from './components/pages/Dashboard.vue'
import ProvinceList from './components/pages/ProvinceList.vue'
import DistrictList from './components/pages/DistrictList.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: '/provinces',
      name: 'provinces',
      component: ProvinceList
    },
    {
      path: '/districts',
      name: 'districts',
      component: DistrictList
    }
  ]
})
