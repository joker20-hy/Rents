<template>
  <transition name="slide-fade">
    <div page-section>
      <div>
        <router-link :to="{name: 'user-detail', params: {id: item.user.id}}" class="text-bold text-primary">
          {{ `${item.user.profile.lastname} ${item.user.profile.firstname}` }}
        </router-link>
      </div>
      <div>
        {{ item.user.profile.address }}
      </div>
      <div class="c-flex-middle pt-2">
        <span v-if="item.status==statuses.APPROVED" class="text-bold bg-success text-light px-1 rounded">Đã phê duyệt</span>
        <span v-else-if="item.status==statuses.WAITING" class="text-bold bg-primary text-light px-1 rounded">Chờ xử lý</span>
        <span v-else-if="item.status==statuses.DECLINED" class="text-bold bg-danger text-light px-1 rounded">Đã từ chối</span>
        <router-link :to="{name: 'application-detail', params: {id: item.id}}" class="ml-auto text-primary">
          Chi tiết
        </router-link>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  props: {
    item: {
      required: true,
      type: Object
    }
  },
  data() {
    return {
      statuses: $config.OWNER_APPLICATION_STATUS
    }
  }
}
</script>