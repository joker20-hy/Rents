<template>
  <div contain-box>
    <h1 class="text-primary text-center">Chào mừng bạn đến với Rent</h1>
    <br>
    <h3 class="text-center">Hướng dẫn đăng phòng</h3>
    <br>
    <h4 class="text-primary" id="register">Bước 1</h4>
    <div page-section>
        Để đăng phòng trên hệ thống, bạn cần một tài khoản chủ trọ - 
        tài khoản sẽ giúp bạn quản lý danh sách dẫy trọ và phòng trọ một cách hiệu quả và nhanh chóng.
        <div>
            Bạn có thể đăng ký tài khoản <router-link :to="{name: 'register'}" class="text-primary">tại đây</router-link>
        </div>
    </div>
    <h4 class="text-primary" id="register-owner">Bước 2</h4>
    <div page-section>
        Hãy chọn <span class="text-bold">"Tài khoản"</span> trong menu bên phải và chọn <span class="text-bold">"Đăng ký chủ trọ"</span>
         và làm theo hướng dẫn.
    </div>
    <h4 class="text-primary" id="create-house">Bước 3</h4>
    <div page-section>
        Trong giao diện quản lý, bạn hãy chọn <span class="text-bold">"Thêm nhà"</span> để thêm bản ghi nhà hoặc dãy trọ của bạn.
    </div>
    <h4 class="text-primary" id="create room">Bước 4</h4>
    <div page-section>
        Sau khi đã thêm nhà thành công, chọn nút <span class="text-bold">"Phòng trọ"</span> đề đi đến phần quản trị danh sách phòng và thêm phòng trọ.
    </div>
  </div>
</template>
<script>
export default {
  
}
</script>