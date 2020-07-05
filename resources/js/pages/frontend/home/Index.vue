<template>
  <div>
    <nav-header/>
    <search-form/>
  </div>
</template>
<script>
import NavHeader from '../layouts/Header'
import SearchForm from './Search'
export default {
  components: {
    NavHeader,
    SearchForm
  },
  data () {
    return {}
  }
}
</script>