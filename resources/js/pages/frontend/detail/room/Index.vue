<template>
  <div class="container" v-if="room!=null">
    <image-gallary :images="room.images" @detail="slideshow"/>
    <div class="d-flex">
      <h3>{{ room.name }}</h3>
      <router-link v-if="hasRight" class="ml-auto btn c-flex-middle" :to="{name: 'owner-detail-room', params: {id: room.id}}">
        <edit-icon :width="'15px'" :height="'15px'"/>&nbsp;Chỉnh sửa
      </router-link>
    </div>
    <div class="text-dark c-flex-middle" v-if="room.house">
      <location-pin-icon :width="'15px'" :height="'15px'"/>&nbsp;{{ room.house.address_detail }}
    </div>    
    <div class="text-center my-3">
      <div>
        <span style="font-size: 60px;line-height: 1">{{ room.avg_rate }}</span>/10
      </div>
      <a v-if="total_reviews>0" href="#danh-gia">{{ total_reviews }} đánh giá</a>
    </div>
    <div class="row my-2">
      <div class="col-6">
        <strong>Giá: </strong>
        {{ range(room.price) }} vnđ
      </div>
      <div class="col-6 text-right">
        <strong>Đóng tiền:</strong> {{ room.cycle }} tháng / lần
      </div>
      <div class="col-6">
        <span class="text-bold">Diện tích:</span> {{ room.acreage }} m2
      </div>
    </div>

    <div class="alert alert-success" v-if="room.roommate_wanted" page-section>
      <div><span class="text-bold">Cần thêm: </span>{{ room.roommate_wanted.number }}</div>
      <div v-if="room.roommate_wanted.contact"><span class="text-bold">Thông tin liên hệ: </span>{{ room.roommate_wanted.contact }}</div>
      <div v-if="room.roommate_wanted.content"><span class="text-bold">Thông tin thêm: </span>{{ room.roommate_wanted.content }}</div>
    </div>

    <div class="c-toolbar" v-if="room.house.contact" page-section>
      <h2 class="mb-0">Liên hệ của chủ nhà</h2>
      <div class="col-6 py-2 pl-0" v-if="room.house.contact.phone!=null">
        <span class="text-bold">Số điện thoại:</span> <a :href="`tel:${room.house.contact.phone}`">{{ room.house.contact.phone }}</a>
      </div>
      <div class="col-6 py-2 pr-0" v-if="room.house.contact.other!=null">
        <span class="text-bold">Liên hệ khác:</span> {{ room.house.contact.other }}
      </div>
    </div>

    <div page-section>
      <h2>Cơ Sở vật chất</h2>
      <room-criterias v-if="room.criterias" :list="room.criterias" class="mb-3"/>
    </div>
    <div page-section>
      <h2>Dịch vụ</h2>
      <room-services v-if="room.house" :id="room.house.id" class="mb-3"/>
    </div>
    <div page-section>
      <h2>Mô tả</h2>
      <p v-html="room.description"></p>
    </div>
    <review-list :id="'danh-gia'" :room="id"></review-list>
    <button class="search-btn" @click="toggleSideSearch">
      <search-icon class="fill-light" :width="'16px'" :height="'16px'"/>
    </button>
    <modal name="image-carousel" :classes="['mb-auto']">
      <carousel :per-page="1" :mouse-drag="true" v-model="carousel_index">
        <slide v-for="(image, index) in room.images" :key="index" :value="index">
          <img :src="image" alt="" class="mw-100 mh-100">
        </slide>
      </carousel>
    </modal>
  </div>
</template>
<script>
import { Carousel, Slide } from 'vue-carousel';
import ImageGallary from './Images'
import RoomCriterias from './Criterias'
import RoomServices from './Services'
import ReviewList from './Reviews'
import SearchIcon from '../../../../icons/search'
import EditIcon from '../../../../icons/Edit'
import LocationPinIcon from '../../../../icons/LocationPin'
export default {
  components: {
    ImageGallary,
    RoomCriterias,
    RoomServices,
    ReviewList,
    Carousel,
    Slide,
    SearchIcon,
    EditIcon,
    LocationPinIcon
  },
  watch: {
    '$route.params.id': {
      handler (id) {
        this.id = id
        this.get()
      },
      immediate: true,
      deep: true
    }
  },
  data () {
    return {
      id: '',
      carousel_index: 0
    }
  },
  computed: {
    room () {
      return this.$store.getters['rooms/first']
    },
    admin () {
      return this.$store.getters['auth/admin']
    },
    owner () {
      return this.$store.getters['auth/owner']
    },
    total_reviews() {
      return this.$store.getters['reviews/total']
    },
    hasRight() {
      return this.owner||this.admin
    }
  },
  methods: {
    toggleSideSearch (e) {
      e.stopPropagation()
      $eventHub.$emit('toggle-side-search')
    },
    range(number) {
      return $number.range(`${number}`)
    },
    get () {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/room/${this.id}`)
      .then(res => {
        res.data.description = utf8.decode(res.data.description)
        res.data.house.contact = JSON.parse(res.data.house.contact)
        res.data.images = JSON.parse(res.data.images)
        this.$store.commit('rooms/rooms', [res.data])
        $eventHub.$emit('off-loading')
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        console.log(err.response.data)
      })
    },
    slideshow (index) {
      this.carousel_index = index
      console.log(this.carousel_index)
      this.$modal.show('image-carousel')
    }
  }
}
</script>
<style scoped>
h2 {
  font-size: medium;
}
@media(min-width: 992px) {
  h2 {
    font-size: large;
  }
}
</style>