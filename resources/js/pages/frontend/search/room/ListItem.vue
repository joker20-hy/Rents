<template>
  <div class="row mx-auto mt-2 mb-3" search-item>
    <image-preview :images="images"/>
    <div intro room>
      <h2>
        <router-link :to="{name: 'room-for-rent', params: {id: room.id}}">{{ room.name }}</router-link>
      </h2>
      <div address>
        {{ room.address }}
      </div>
      <div rate>
        <span avg>
          {{ room.avg_rate }}
        </span>
        <span count>
          {{ room.rate_count }} Đánh giá
        </span>
      </div>
      <div criterias v-if="room.criterias">
        <div item v-for="criteria in room.criterias" :key="criteria.id">
          &#10004;{{ criteria.name }}
        </div>
      </div>
      <div base-info>
        <div acreage>
          <span class="text-bold">Diện tich</span> {{ room.acreage }} m2
        </div>
        <div price>
          {{ price }} vnđ/tháng
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ImagePreview from '../Images'
export default {
  name: 'list-item',
  components: {
    ImagePreview
  },
  props: {
    room: {
      required: true,
      type: Object
    }
  },
  computed: {
    images () {
      return JSON.parse(this.room.images)
    },
    price () {
      return `${this.room.price}`.replace(/(\d)(?=(\d{3})+\b)/g, '$1,')
    }
  },
  data () {
    return {}
  }
}
</script>