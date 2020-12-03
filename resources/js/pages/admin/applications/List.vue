<template>
  <div contain-box>
    <h3>Đơn yêu cầu chủ trọ</h3>
    <list-item v-for="item in applications" :key="item.id" :item="item"/>
    <paginate
      v-model="query.page"
      :page-count="page_count"
      :page-range="3"
      :margin-pages="2"
      :click-handler="changePage"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination'"
      :page-class="'page-item'"
      v-if="applications.length>1">
    </paginate>
    <div v-if="applications.length==0" class="text-center text-muted">
      Chưa có bản ghi nào
    </div>
  </div>
</template>
<script>
import ListItem from './ListItem'
export default {
  components: {
    ListItem
  },
  data () {
    return {
      page_count: 1,
      query: {
        page: 1
      },
      chosen: ''
    }
  },
  watch: {
    "$route.query": {
      handler(query) {
        this.list()
      },
      immediate: true,
      deep: true
    }
  },
  computed: {
    applications() {
      return this.$store.getters['applications/applications']
    }
  },
  methods: {
    list() {
      $eventHub.$emit('on-loading')
      ajax().get(`/api/owner/application/list?${serialize.fromObj(this.query)}`)
      .then(res => {
        $eventHub.$emit('off-loading')
        this.$store.commit('applications/applications', res.data.data)
        this.page_count = res.data.last_page
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: err.response.data.message,
          timeout: 4000
        })
      })
    },
    changePage(page) {
      this.query.page = page
      this.list()
    }
  }
}
</script>
