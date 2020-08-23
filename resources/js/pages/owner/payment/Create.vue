<template>
  <div class="contain">
    <div v-if="room.renters_count==0" class="alert alert-danger">
      Phòng hiện chưa có người thuê
    </div>
    <form v-if="room.renters_count>0" @submit.prevent="generate()">
      <h4 class="mb-3">Hóa đơn {{ room.name }}</h4>
      <div class="row form-group">
        <div class="col-6">
          <label for="">Tháng</label>
          <input type="number" class="form-control" v-model="month">
        </div>
        <div class="col-6">
          <label for="">Năm</label>
          <input type="number" class="form-control" v-model="year">
        </div>
      </div>
      <div class="row form-group mx-0" v-for="service in room_services" :key="service.service_id">
        <div class="w-100" v-if="service.service.type==service_types.PER_UNIT.value">
          <div class="d-flex">
            <label>{{ service.service.name }}</label> <span class="ml-auto">{{ service.price }} vnđ / {{ service.service.unit }}</span>
          </div>
          <input type="text" class="form-control" v-model="service.amount" :placeholder="service.service.unit" required>
        </div>
        <div class="w-100 d-flex" v-else-if="service.service.type==service_types.BY_RENTERS.value">
          <label>{{ service.service.name }}</label> <span class="ml-auto">{{ room.renters_count }} {{ service.service.unit}} x {{ service.price }} vnđ</span>
        </div>
        <div class="w-100 d-flex" v-else-if="service.service.type==service_types.PERIODIC.value">
          <label>{{ service.service.name }}</label>
          <div class="ml-auto">{{ service.price }} vnđ</div>
        </div>
      </div>
      <div class="form-group d-flex">
        <label>Tiền phòng</label>
        <div class="ml-auto">{{ this.room.price }}vnđ</div>
      </div>
      <div class="d-flex">
        <button class="btn btn-outline-primary ml-auto">Lưu hóa đơn</button>
      </div>
    </form>
    <div class="bill-contain" v-show="bill.services.length > 0">
      <h4>Hóa đơn</h4>
      <div class="form-group row" v-for="row in bill.services" :key="row.servince_id">
        <div class="col-6">{{ row.servince_name }}</div>
        <div class="col-6 text-right">{{ row.price }} vnđ</div>
      </div>
      <div class="form-group row">
        <div class="col-6">Tiền phòng</div>
        <div class="col-6 text-right">{{ bill.room_price }} vnđ</div>
      </div>
      <hr>
      <div class="text-right">{{ bill.total }} vnđ</div>
      <button class="btn btn-success" @click="store()" :disabled="storing">Lưu</button>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      today: new Date(),
      id: this.$route.params.id,
      room_services: [],
      room: {},
      bill: {
        services: [],
        room_price: 0,
        total: 0
      },
      month: '',
      year: '',
      storing: false
    }
  },
  mounted () {
    this.getServices()
    this.month = this.today.getMonth() + 1
    this.year = this.today.getFullYear()
  },
  computed: {
    service_types () {
      return $config.SERVICE_TYPE
    }
  },
  methods: {
    getServices () {
      $request.get(`/api/room/${this.id}/services`)
      .then(res => {
        res.data.room_services.forEach(serv => serv.amount='')
        this.room_services = res.data.room_services
        this.room = res.data.room
      })
      .catch(err => console.log(err))
    },
    generate () {
      this.bill.services = []
      this.bill.room_price = this.room.price
      this.bill.total = this.room.price
      this.room_services.forEach(serv => {
        serv.amount = serv.service.type==this.service_types.BY_RENTERS.value?this.room.renters_count:serv.amount
        let row = {
          servince_id: serv.service.id,
          servince_name: serv.service.name,
          amount: serv.amount==''?'':`${serv.amount} ${serv.service.unit}`,
          price: serv.amount==''?serv.price:serv.price*parseInt(serv.amount)
        }
        this.bill.services.push(row)
        this.bill.total += row.price
      })
    },
    store() {
      $eventHub.$emit('on-loading')
      this.storing = true
      $request.post(`/api/payment`, {
        room_id: this.id,
        time: `${this.today.getFullYear()}-${this.today.getMonth()>8?this.today.getMonth()+1:`0${this.today.getMonth()}`}-01`,
        bill: this.bill
      })
      .then(res => {
        $eventHub.$emit('off-loading')
        this.storing = false
        $eventHub.$emit('success-alert', {
          title: 'Thành công',
          message: 'Đã lưu hóa đơn thành công',
          timeout: 3000
        })
        this.$router.push({name: 'owner-list-payment', params: {id: this.id}})
      })
      .catch(err => {
        $eventHub.$emit('off-loading')
        this.storing = false
        $eventHub.$emit('error-alert', {
          title: 'Không thành công',
          message: 'Đã có lỗi xảy ra, không thể lưu hóa đơn',
          timeout: 3000
        })
      })
    }
  }
}
</script>
<style scoped>
form, .bill-contain {
  padding: 20px 15px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0px 1px 2px #ddd;
  margin-bottom: 50px;
}
label {
  font-weight: 600;
}
.contain {
  width: 400px;
  max-width: 100%;
  margin-right: auto;
  margin-left: auto;
  margin-top: 40px;
  padding: 10px;
}
.bill {
  padding: 10px;
  border: 1px solid #aaa;
  border-radius: 6px;
}
</style>