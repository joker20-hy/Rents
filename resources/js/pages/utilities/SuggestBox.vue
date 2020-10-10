<template>
  <div v-click-outside="hideSuggest" class="position-relative">
    <input type="search" class="input" v-model="keywords" :placeholder="placeholder" @keyup="getSuggest()" @change="update()" :required="required">
    <transition-group name="slide-fade" tag="div" class="result-list" :class="show ? 'show': ''">
      <div v-for="record in records" :key="record.id" @click="choose(record)">{{ record.name }}</div>
    </transition-group>
  </div>
</template>
<script>
import serialize from '../../utilities/serialize'
import ClickOutside from 'vue-click-outside'

export default {
  name: 'suggest-box',
  props: {
    api: {
      required: true,
      type: String
    },
    value: {
      required: false,
      type: Object
    },
    params: {
      required: false,
      type: Object
    },
    limit: {
      required: false,
      default: 10,
      type: Number
    },
    placeholder: {
      required: false,
      type: String
    },
    required: {
      required: false,
      type: Boolean
    }
  },
  watch: {
    value (val) {
      this.id = val.id
      this.keywords = val.name
    },
    params (val) {
      this.query = val
    }
  },
  data () {
    return {
      id: this.value==undefined?'':this.value.id,
      keywords: this.value==undefined?'':this.value.name,
      query: this.params==undefined?{}:this.params,
      show: false,
      records: []
    }
  },
  created () {
    this.query.keywords = ''
  },
  methods: {
    getSuggest () {
      if (this.keywords.length < 2) return false
      this.id = ''
      this.query.keywords = this.keywords
      this.query.limit = this.limit
      let targetApi = `${this.api}?${serialize.fromObj(this.query)}`
      ajax().get(targetApi)
      .then(res => {
        if (res.data.length > 0) this.showSuggest()
        this.records = res.data
      })
      .catch(err => {
        $eventHub.$emit('error-alert', {
          title: 'Error',
          message: 'Unable to get suggests'
        })
      })
    },
    choose (record) {
      this.id = record.id
      this.keywords = record.name
      this.update()
      this.hideSuggest()
    },
    showSuggest () {
      this.show = true
    },
    hideSuggest () {
      this.show = false
    },
    update () {
      this.$emit('change', {
        id: this.id,
        name: this.keywords,
      })
    }
  },
  directives: {
    ClickOutside
  }
}
</script>
<style scoped>
  .result-list {
    display: none;
  }
  .result-list.show {
    display: block;
    display: block;
    position: absolute;
    background-color: #f8f9fa;
    z-index: 2;
    width: 100%;
    box-shadow: 0px 2px 2px;
  }
  .result-list > div {
    cursor: pointer;
    padding: 5px 10px;
  }
  .result-list > div:hover {
    background-color: #0000000f;
  }
</style>