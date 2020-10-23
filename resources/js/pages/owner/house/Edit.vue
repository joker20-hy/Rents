<template>
  <div class="container">
    <form @submit.prevent="store()" class="col-lg-10 col-xl-8" detail-form id="create-house">
      <h3 class="py-3 px-2 bg-primary text-light" style="margin-left: -25px;margin-right: -25px;">
        Thêm nhà
      </h3>
      <div class="row" v-if="bucket.length>0">
        <div class="col-4" v-for="(temp, index) in bucket" :key="index">
          <img :src="temp" alt="" class="w-100">
        </div>
      </div>
      <div class="form-group d-flex align-items-center py-2">
        <label for="" class="m-0">Ảnh nhà</label>
        <div class="position-relative pl-3">
          <button type="button" class="btn text-primary" onclick="clickTarget('#images')">
            <i class="far fa-images fa-lg"></i>
          </button>
          <input type="file" id="images" class="d-none" ref="images" @change="getImages" accept="image/*" multiple>
        </div>
      </div>
      <label for="">
        Tên nhà
        <span class="text-danger" title="Required feild">*</span>
      </label>
      <div class="form-group">
        <input type="text" class="input" v-model="house.name" placeholder="House name" required>
      </div>
      <div class="form-group row">
        <div class="col-md-4">
          <label for="">
            Tỉnh thành <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/provinces'" :placeholder="'Tỉnh thành'" @change="getProvince" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">
            Quận huyện <span class="text-danger" title="Required feild">*</span>
          </label>
          <suggest-box :api="'/api/sg/districts'" :params="{province: house.province_id}" :placeholder="'Quận huyện'" @change="getDistrict" :required="true"/>
        </div>
        <div class="col-md-4">
          <label for="">Khu vực <span class="text-danger" title="Required feild">*</span></label>
          <suggest-box :api="'/api/sg/areas'" :params="{province: house.province_id,district: house.district_id}" :placeholder="'Khu vực, đường, phố'" @change="getArea"/>
        </div>
      </div>
      <div class="form-group">
        <label for="">
          Địa chỉ chi tiết
          <span class="text-danger" title="Required feild">*</span>
        </label>
        <input type="text" class="input" v-model="house.address" placeholder="vd: số 20 ngõ 20" required>
      </div>
      <label>Dịch vụ của nhà trọ <span class="text-danger" title="Required feild">*</span></label>
      <small>(Những dịch vụ mà người thuê trọ cần trả trong quá trình thuê nhà)</small>
      <choose-service :list="services"/>
      <div class="form-group d-flex">
        <label for="">Nhà để cho thuê?</label>
        <switch-box v-model="house.rent" class="ml-auto" :class="house.rent==1?'on':''"/>
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">Giá</label>
        <input type="number" class="input" v-model="house.price" placeholder="Price of house">
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">Hướng nhà</label>
        <select class="input" v-model="house.direction">
          <option value="">Hướng nhà</option>
          <option v-for="dir in directions" :value="dir.id" :key="dir.id">{{ dir.name }}</option>
        </select>
      </div>
      <div class="form-group" v-show="house.rent">
        <label for="">Mô tả về ngôi nhà</label>
        <ckeditor :editor="editor" v-model="house.description" :config="editorConfig"></ckeditor>
      </div>
      <div class="form-group d-flex">
        <button class="btn btn-outline-primary ml-auto">Thêm</button>
      </div>
    </form>
  </div>
</template>
<script>
export default {
  data () {
    return {}
  }
}
</script>