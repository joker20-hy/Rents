<template>
  <div page-section>
    <div class="d-flex">
      <h2>Đánh giá</h2>
      <router-link class="btn text-primary c-flex-middle ml-auto" :to="{name:'review-room', params: {id: this.room}}">
        <edit-icon :width="'13px'" :height="'13px'" class="fill-blue"/>&nbsp;đánh giá
      </router-link>
    </div>
    <div v-if="reviews.length==0" class="text-muted text-center">
      Hiện chưa có đánh giá nào
    </div>
    <div v-for="review in reviews" :key="review.id" item-review>
      <div class="intro">
        <div class="avt-contain">
          <img :src="review.review.anonymous?anonymous_img:review.review.user_avatar">
        </div>
        <div class="summary">
          <div class="text-bold">
            {{ review.review.anonymous?'Anonymous':review.review.user_name }}
          </div>
          <div class="c-flex-middle">
            <chevron-right-icon :width="'12px'" :height="'12px'" style="transform: translateY(-1px)"/>
            &nbsp;{{ review.review.title }}
          </div>
        </div>
      </div>
      <div class="pt-1 c-flex-middle">
        <b>Cơ sở vật chất:</b>&nbsp;{{ review.infra_rate }}&nbsp;
        <star-icon :width="'13px'" :height="'13px'" class="fill-orange" style="transform: translateY(-1px)"/>
      </div>
      <div class="pt-1 c-flex-middle">
        <b>An ninh:</b>&nbsp;{{ review.secure_rate }}&nbsp;
        <star-icon :width="'13px'" :height="'13px'" class="fill-orange" style="transform: translateY(-1px)"/>
      </div>
      <div class="pt-1" v-if="review.review.description">
        <b>Nhận xét: </b>
        {{ review.review.description }}
      </div>
    </div>
    <div class="text-center" v-if="next_page_url">
      <button class="btn text-primary" @click="get(next_page_url)">
        xem thêm
      </button>
    </div>
  </div>
</template>
<script>
import ChevronRightIcon from '../../../../icons/ChevronRight'
import EditIcon from '../../../../icons/Edit'
import StarIcon from '../../../../icons/Star'
export default {
  name: 'review-list',
  components: {
    ChevronRightIcon,
    EditIcon,
    StarIcon
  },
  props: {
    room: {
      required: true
    },
    perpage: {
      required: false,
      default: 5,
      type: Number
    }
  },
  data () {
    return {
      next_page_url: null
    }
  },
  computed: {
    reviews() {
      return this.$store.getters['reviews/reviews']
    },
    anonymous_img() {
      return $config.ANONYMOUS
    },
    type () {
      return $config.REVIEW.TYPE.ROOM
    }
  },
  mounted() {
    this.get()
  },
  methods: {
    get (url=null) {
      url = url==null?`/api/room/${this.room}/review`:this.next_page_url
      ajax().get(url)
      .then(res => {
        this.$store.commit('reviews/total', res.data.total)
        this.$store.commit('reviews/reviews', res.data.data)
        this.next_page_url = res.data.next_page_url
      })
      .catch(err => console.log(err))
    }
  }
}
</script>
<style scoped>
[item-review] {
  border-bottom: 1px solid #6c757d60;
  padding: 10px;
}
.intro {
  display: flex;
}
.intro .avt-contain {
  width: 56px;
}
.intro .avt-contain img {
  width: 56px;
  height: 56px;
  padding: 5px;
  border-radius: 50%;
  background-color: #f8f9fa;
  box-shadow: 0px 0px 2px #343a40;
}
.intro .summary {
  width: calc(100% - 56px);
  padding-left: 10px;
  font-size: small;
}
.fa-star {
  color: #f6993f;
}
h2 {
  font-size: medium;
}
@media(min-width: 992px) {
  h2 {
    font-size: large;
  }
}
</style>
