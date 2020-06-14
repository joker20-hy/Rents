import Vue from 'vue'
import Router from 'vue-router'

import { $auth } from './auth'

import Dashboard from './components/pages/Dashboard'
import ProvinceList from './components/pages/ProvinceList'
import DistrictList from './components/pages/DistrictList'
import AreaList from './components/pages/AreaList'
import Userlist from './components/pages/Userlist'
import LoginForm from './components/pages/LoginForm'

import DirectionList from './components/pages/DirectionList'
import Houses from './components/pages/House/Index'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: '/a',
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginForm
    },
    {
      path: '/au',
      component: Dashboard,
      children: [
        {
          path: 'users',
          name: 'user-list',
          component: Userlist
        },
        {
          path: 'provinces',
          name: 'province-list',
          component: ProvinceList
        },
        {
          path: 'districts',
          name: 'district-list',
          component: DistrictList
        },
        {
          path: 'areas',
          name: 'area-list',
          component: AreaList
        },
        {
          path: 'directions',
          name: 'direction-list',
          component: DirectionList
        },
        {
          path: 'houses',
          name: 'houses',
          component: Houses
        }
      ],
      beforeEnter(to, from, next) {
        if ($auth.check()) next()
        else router.push({name: 'login'})
      }
    }
    // {
    //   path: '/dashboard',
    //   name: 'dashboard',
    //   component: Dashboard,
    //   meta: {
    //     requireAuth: true
    //   }
    // },
    // {
    //   path: '/provinces',
    //   name: 'provinces',
    //   component: ProvinceList,
    //   meta: {
    //     requireAuth: true
    //   }
    // },
    // {
    //   path: '/districts',
    //   name: 'districts',
    //   component: DistrictList,
    //   meta: {
    //     requireAuth: true
    //   }
    // },
    // {
    //   path: '/areas',
    //   name: 'areas',
    //   component: AreaList,
    //   meta: {
    //     requireAuth: true
    //   }
    // },
    // {
    //   path: '/users',
    //   name: 'users',
    //   component: Userlist,
    //   meta: {
    //     requireAuth: true
    //   }
    // },
    // {
    //   path: '/house',
    //   component: 
    // }
  ]
})
// router guards
// router.beforeEach((to, from, next) => {
//   if (to.meta.requireAuth) {
//     if (!$auth.check()) {
//       window.location.href = '/a/login'
//     } else {
//       next()
//     }
//   } else if ($auth.check()) {
//     history.go(-1);
//   } else {
//     next()
//   }
// })

export default router