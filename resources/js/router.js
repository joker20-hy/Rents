import Vue from 'vue'
import Router from 'vue-router'

import Home from './pages/frontend/home/Index'
import SearchRoom from './pages/frontend/search/room/Index'
import Detail from './pages/frontend/detail/Index'
import DetailHouse from './pages/frontend/detail/house/Index'
import DetailRoom from './pages/frontend/detail/room/Index'
import Review from './pages/frontend/review/Index'
import ReviewHouse from './pages/frontend/review/House'
import ReviewRoom from './pages/frontend/review/Room'
import ReviewRenter from './pages/frontend/review/Renter'
import Renter from './pages/frontend/user/Index'
import Account from './pages/frontend/user/Account'
import JoinRoom from './pages/frontend/user/JoinRoom'
import RentedRoom from './pages/frontend/user/Room'
import RoomPayMethod from './pages/frontend/user/RoomPayMethods'
import PaymentRoom from './pages/frontend/user/room_payment/Index'
import PaymentRoomList from './pages/frontend/user/room_payment/List'
import PaymentRoomDetail from './pages/frontend/user/room_payment/Detail'
/** Auth components */
import Login from './pages/auth/Login'
import Register from './pages/auth/Register'
/** Owner components */
import Owner from './pages/owner/Index'
import OwnerHouse from './pages/owner/house/Index'
import OwnerListHouse from './pages/owner/house/List'
import OwnerDetailHouse from './pages/owner/house/Detail'
import OwnerCreateHouse from './pages/owner/house/Create'
import OwnerRoom from './pages/owner/room/Index'
import OwnerListRoom from './pages/owner/room/List'
import OwnerDetailRoom from './pages/owner/room/Detail'
import OwnerCreateRoom from './pages/owner/room/Create'
import OwnerListPayment from './pages/owner/payment/List'
import OwnerCreatePayment from './pages/owner/payment/Create'
import OwnerDetailPayment from './pages/owner/payment/Detail'
import OwnerPayMethods from './pages/owner/paymethods/Index'
import OwnerListPayMethods from './pages/owner/paymethods/List'
import OwnerCreatePayMethods from './pages/owner/paymethods/Create'
import OwnerRenter from './pages/owner/renters/Index'
import OwnerAddRenter from './pages/owner/renters/Add'
import OwnerListRenter from './pages/owner/renters/List'
/** Dashboard parent components */
import Admin from './pages/admin/Index'
import Users from './pages/admin/user/Index'
import UserList from './pages/admin/user/List'
import Provinces from './pages/admin/province/Index'
import ProvinceList from './pages/admin/province/List'
import Districts from './pages/admin/district/Index'
import DistrictList from './pages/admin/district/List'
import Areas from './pages/admin/area/Index'
import AreaList from './pages/admin/area/List'
import Directions from './pages/admin/directions/List'
import Houses from './pages/admin/house/Index'
import HouseList from './pages/admin/house/List'
import HouseCreate from './pages/admin/house/Create'
import Rooms from './pages/admin/room/Index'
import RoomList from './pages/admin/room/List'
import RoomCreate from './pages/admin/room/Create'
import Service from './pages/admin/service/Index'
import ServiceList from './pages/admin/service/List'
import Criteria from './pages/admin/criteria/Index'
import CriteriaList from './pages/admin/criteria/List'
import Reviews from './pages/admin/review/Index'
import ReviewList from './pages/admin/review/List'
import { $auth } from './utilities/request/request'

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
      path: '/phong-tro',
      name: 'search-room',
      component: SearchRoom
    },
    {
      path: '/chi-tiet',
      component: Detail,
      children: [
        {
          path: 'nha-cho-thue/:id?',
          name: 'house-for-rent',
          component: DetailHouse
        },
        {
          path: 'phong-tro/:id?',
          name: 'room-for-rent',
          component: DetailRoom
        }
      ]
    },
    {
      path: '/thue-phong/:room_id',
      name: 'join-room',
      component: JoinRoom,
      beforeEnter(to, from, next) {
        if ($auth.check) {
          $auth.forget('old_route')
          if ($auth.user.room_id==null) next()
          else router.push({name: 'home'})
        }
        else {
          $auth.remember('old_route', to)
          router.push({name: 'login'})
        }
      }
    },
    {
      path: '/nguoi-thue',
      component: Renter,
      children: [
        {
          path: 'tai-khoan',
          name: 'account',
          component: Account,
          beforeEnter(to, from, next) {
            if ($auth.check) {
              $auth.forget('old_route')
              next()
            }
            else {
              $auth.remember('old_route', to)
              router.push({name: 'login'})
            }
          }
        },
        {
          path: 'phong',
          name: 'rented-room',
          component: RentedRoom
        },
        {
          path: 'phong/:id/thanh-toan',
          name: 'room-pay-method',
          component: RoomPayMethod
        },
        {
          path: 'hoa-don-phong',
          component: PaymentRoom,
          children: [
            {
              path: '',
              name: 'payment-room-list',
              component: PaymentRoomList
            },
            {
              path: ':id',
              name: 'payment-room-detail',
              component: PaymentRoomDetail
            }
          ]
        }
      ]
    },
    {
      path: '/danh-gia',
      component: Review,
      children: [
        {
          path: 'nha/:id',
          name: 'review-house',
          component: ReviewHouse
        },
        {
          path: 'phong-tro/:id',
          name: 'review-room',
          component: ReviewRoom
        },
        {
          path: 'nguoi-thue-nha/:id',
          name: 'review-renter',
          component: ReviewRenter
        }
      ],
      beforeEnter(to, from, next) {
        if ($auth.check) {
          $auth.forget('old_route')
          next()
        }
        else {
          $auth.remember('old_route', to)
          router.push({name: 'login'})
        }
      }
    },
    {
      path: '/dang-nhap',
      name: 'login',
      component: Login,
      beforeEnter(to, from, next) {
        if ($auth.check) router.push({name: 'home'})
        else next()
      }
    },
    {
      path: '/dang-ky-tai-khoan',
      name: 'register',
      component: Register,
      beforeEnter(to, from, next) {
        if ($auth.check) router.push({name: 'home'})
        else next()
      }
    },
    {
      path: '/cn',
      component: Owner,
      children: [
        {
          path: 'nha',
          component: OwnerHouse,
          children: [
            {
              path: '',
              name: 'owner-list-house',
              component: OwnerListHouse
            },
            {
              path: 'them-nha',
              name: 'owner-create-house',
              component: OwnerCreateHouse
            },
            {
              path: ':id/phong-tro',
              name: 'owner-list-room',
              component: OwnerListRoom
            },
            {
              path: ':id/them-phong',
              name: 'owner-create-room',
              component: OwnerCreateRoom
            },
            {
              path: ':id',
              name: 'owner-detail-house',
              component: OwnerDetailHouse
            }
          ]
        },
        {
          path: 'phong-tro',
          component: OwnerRoom,
          children: [
            {
              path: ':id',
              name: 'owner-detail-room',
              component: OwnerDetailRoom
            },
            {
              path: ':id/tao-hoa-don',
              name: 'owner-create-payment',
              component: OwnerCreatePayment
            },
            {
              path: ':id/hoa-don',
              name: 'owner-list-payment',
              component: OwnerListPayment
            }
          ]
        },
        {
          path: 'hoa-don/:id',
          name: 'owner-detail-payment',
          component: OwnerDetailPayment
        },
        {
          path: 'nguoi-thue/:room',
          component: OwnerRenter,
          children: [
            {
              path: '',
              name: 'owner-list-renter',
              component: OwnerListRenter
            },
            {
              path: 'them',
              name: 'owner-add-renter',
              component: OwnerAddRenter
            }
          ]
        },
        {
          path: 'phuong-thuc-thanh-toan',
          component: OwnerPayMethods,
          children: [
            {
              path: '',
              component: OwnerListPayMethods,
              name: 'owner-list-paymethod'
            },
            {
              path: 'tao',
              component: OwnerCreatePayMethods,
              name: 'owner-create-paymethod'
            }
          ]
        }
      ],
      beforeEnter(to, from, next) {
        if ($auth.check) {
          if ($auth.user.role==$config.user.ROLE.OWNER) next()
          else router.push({name: 'home'})
        }
        else router.push({name: 'login'})
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
          component: Reviews,
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
        if ($auth.check) {
          $auth.forget('old_route')
          if ($auth.user.role==$config.user.ROLE.ADMIN) next()
          else router.push({name: 'home'})
        }
        else {
          $auth.remember('old_route', to)
          router.push({name: 'login'})
        }
      }
    }
  ]
})

export default router