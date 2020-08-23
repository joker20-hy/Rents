<template>
  <div page-section>
    <div class="d-flex">
      <h2>Đánh giá</h2>
      <router-link class="btn text-primary ml-auto" :to="{name:'review-room', params: {id: this.id}}">
        <i class="fas fa-pencil"></i> đánh giá</router-link>
    </div>
    <transition-group name="slide-fade">
      <div v-for="review in reviews" :key="review.id" item-review>
        <div class="intro">
          <div class="avt-contain">
            <!-- <img :src="review.user_avatar" alt="" v-if="review.anonymous"> -->
            <img :src="anonymous_img" alt="">
          </div>
          <div class="summary">
            <div class="text-bold">
              {{ review.user_name }}
            </div>
            <div>
              <i class="fas fa-chevron-right"></i> {{ review.title }}
            </div>
          </div>
        </div>
        <div class="pt-1" v-if="review.review_rooms">
          Cơ sở vật chất: {{ review.review_rooms.infra_rate }} <i class="fas fa-star"></i>
        </div>
        <div class="pt-1" v-if="review.review_rooms">
          An ninh: {{ review.review_rooms.secure_rate }} <i class="fas fa-star"></i>
        </div>
      </div>
    </transition-group>
    <div class="text-center" v-if="next_page_url">
      <button class="btn text-primary" @click="get(next_page_url)">
        xem thêm
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: 'review-list',
  props: {
    id: {
      required: true
    }
  },
  data () {
    return {
      reviews: [],
      next_page_url: null
    }
  },
  computed: {
    anonymous_img() {
      return $config.ANONYMOUS
    }
  },
  mounted() {
    this.get()
  },
  methods: {
    get (url=null) {
      url = url==null?`/api/review/${$config.REVIEW.TYPE.ROOM}/${this.id}?perpage=5`:this.next_page_url
      $request.get(`/api/review/${$config.REVIEW.TYPE.ROOM}/${this.id}?perpage=5`)
      .then(res => {
        res.data.data.forEach(review => this.reviews.push(review))
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
