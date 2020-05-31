import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from './components/pages/Dashboard'
import ProvinceList from './components/pages/ProvinceList'
import DistrictList from './components/pages/DistrictList'
import AreaList from './components/pages/AreaList'
import Userlist from './components/pages/Userlist'

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
    },
    {
      path: '/areas',
      name: 'areas',
      component: AreaList
    },
    {
      path: '/users',
      name: 'users',
      component: Userlist
    }
  ]
})
