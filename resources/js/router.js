import Vue from 'vue'
import Router from 'vue-router'

import { $auth } from './auth'
/** Auth component */
import LoginForm from './components/pages/LoginForm'
/** Dashboard parent component */
import Dashboard from './components/pages/Dashboard'
import Users from './components/pages/Userlist'
import Provinces from './components/pages/ProvinceList'
import Districts from './components/pages/DistrictList'
import Areas from './components/pages/AreaList'
import Directions from './components/pages/DirectionList'
/** house routes */
import Houses from './components/pages/House/Index'
import HouseList from './components/pages/House/List'
import HouseCreate from './components/pages/House/Create'
/** Room routes */
import Rooms from './components/pages/Room/Index'
import RoomList from './components/pages/Room/List'
import RoomCreate from './components/pages/Room/Create'
/** Service routes */
import Service from './components/pages/Service/Index'
import ServiceList from './components/pages/Service/List'

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
      path: '/',
      component: Dashboard,
      children: [
        {
          path: 'users',
          name: 'user-list',
          component: Users
        },
        {
          path: 'provinces',
          name: 'province-list',
          component: Provinces
        },
        {
          path: 'districts',
          name: 'district-list',
          component: Districts
        },
        {
          path: 'areas',
          name: 'area-list',
          component: Areas
        },
        {
          path: 'directions',
          name: 'direction-list',
          component: Directions
        },
        {
          path: 'houses',
          component: Houses,
          children: [
            {
              path: '',
              name: 'house-list',
              component: HouseList
            },
            {
              path: 'create',
              name: 'house-create',
              component: HouseCreate
            }
          ]
        },
        {
          path: 'rooms',
          component: Rooms,
          children: [
            {
              path: '',
              name: 'room-list',
              component: RoomList
            },
            {
              path: 'create',
              name: 'room-create',
              component: RoomCreate
            }
          ]
        },
        {
          path: 'services',
          component: Service,
          children: [
            {
              path: '',
              name: 'service-list',
              component: ServiceList
            }
          ]
        }
      ],
      beforeEnter(to, from, next) {
        if ($auth.check()) next()
        else router.push({name: 'login'})
      }
    }
  ]
})

export default router