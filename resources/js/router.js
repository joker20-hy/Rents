import Vue from 'vue'
import Router from 'vue-router'

import { $auth } from './auth'
/** Auth component */
import LoginForm from './components/pages/Auth/Login'
/** Dashboard parent component */
import Dashboard from './components/pages/Dashboard'
/** */
import Users from './components/pages/User/Index'
import UserList from './components/pages/User/List'

// import Provinces from './components/pages/ProvinceList'

import Provinces from './components/pages/Province/Index'
import ProvinceList from './components/pages/Province/List'

import Districts from './components/pages/District/Index'
import DistrictList from './components/pages/District/List'


/** area routes */
import Areas from './components/pages/Area/Index'
import AreaList from './components/pages/Area/List'

import Directions from './components/pages/Directions/List'
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
/** Criteria routes */
import Criteria from './components/pages/Criteria/Index'
import CriteriaList from './components/pages/Criteria/List'
/** Review routes */
import Review from './components/pages/Review/Index'
import ReviewList from './components/pages/Review/List'

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
          component: Users,
          children: [
            {
              path: '',
              name: 'user-list',
              component: UserList
            }
          ]
        },
        {
          path: 'provinces',
          component: Provinces,
          children: [
            {
              path: '',
              name: 'province-list',
              component: ProvinceList
            }
          ]
        },
        {
          path: 'districts',
          component: Districts,
          children: [
            {
              path: '',
              name: 'district-list',
              component: DistrictList
            }
          ]
        },
        {
          path: 'areas',
          component: Areas,
          children: [
            {
              path: '',
              name: 'area-list',
              component: AreaList
            }
          ]
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
        },
        {
          path: 'criterias',
          component: Criteria,
          children: [
            {
              path: '',
              name: 'criteria-list',
              component: CriteriaList
            }
          ]
        },
        {
          path: 'reviews',
          component: Review,
          children: [
            {
              path: '',
              name: 'review-list',
              component: ReviewList
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