<template>
  <div class="container">
    <h1 class="d-flex align-items-end">
        Danh sách tỉnh thành
        <b-button v-b-toggle.create variant="light" class="ml-auto" style="width: 40px;height:40px">
          <i class="fas fa-plus text-primary"></i>
        </b-button>  
    </h1>
    <b-collapse id="create">
      <form action="" class="my-3">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Nhập tên tỉnh thành" required>
        </div>
        <button class="btn btn-outline-primary">Create</button>
      </form>
    </b-collapse>
    <table class="table table-borderless">
      <thead>
        <th>STT</th>
        <th>Tên</th>
        <th>Kinh độ</th>
        <th>Vĩ độ</th>
        <th>Hành động</th>
      </thead>
      <tbody>
        <tr>
            <td>1</td>
            <td>Hà Nội</td>
            <td></td>
            <td></td>
            <td>
            <button class="btn btn-outline-primary">Edit</button>
            <button class="btn text-danger">Delete</button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Hồ Chí Minh</td>
            <td></td>
            <td></td>
            <td>
            <button class="btn btn-outline-primary">Edit</button>
            <button class="btn text-danger">Delete</button>
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  name: 'province-list',
  data () {
    return {}
  }
}
</script>