import Vue from 'vue'
import Router from 'vue-router'

import { $auth } from './auth'
import Home from './pages/frontend/home/Index'
// import Search from './pages/frontend/search/Index'
import SearchRoom from './pages/frontend/search/room/Index'
import Detail from './pages/frontend/detail/Index'
import DetailHouse from './pages/frontend/detail/house/Index'
import DetailRoom from './pages/frontend/detail/room/Index'
/** Auth component */
import Login from './pages/auth/Login'
/** Dashboard parent component */
import Admin from './pages/admin/Index'
/** */
import Users from './pages/admin/user/Index'
import UserList from './pages/admin/user/List'
/** */
import Provinces from './pages/admin/province/Index'
import ProvinceList from './pages/admin/province/List'
/** */
import Districts from './pages/admin/district/Index'
import DistrictList from './pages/admin/district/List'
/** area routes */
import Areas from './pages/admin/area/Index'
import AreaList from './pages/admin/area/List'

import Directions from './pages/admin/directions/List'
/** house routes */
import Houses from './pages/admin/house/Index'
import HouseList from './pages/admin/house/List'
import HouseCreate from './pages/admin/house/Create'
/** Room routes */
import Rooms from './pages/admin/room/Index'
import RoomList from './pages/admin/room/List'
import RoomCreate from './pages/admin/room/Create'
/** Service routes */
import Service from './pages/admin/service/Index'
import ServiceList from './pages/admin/service/List'
/** Criteria routes */
import Criteria from './pages/admin/criteria/Index'
import CriteriaList from './pages/admin/criteria/List'
/** Review routes */
import Review from './pages/admin/review/Index'
import ReviewList from './pages/admin/review/List'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/phong-tro/:province?/:district?/:area?',
      name: 'search-room',
      component: SearchRoom
    },
    {
      path: '/chi-tiet',
      component: Detail,
      children: [
        {
          path: 'nha-cho-thue/:house?',
          name: 'house-for-rent',
          component: DetailHouse
        },
        {
          path: 'phong-tro/:room?',
          name: 'room-for-rent',
          component: DetailRoom
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      beforeEnter(to, from, next) {
        if ($auth.check()) router.push({name: 'home'})
        else next()
      }
    },
    {
      path: '/a',
      component: Admin,
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