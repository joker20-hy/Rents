<section id="search-form">
    <form>
        <suggest-input class="form-row" id="address" placeholder="Nhập địa chỉ, quận huyện"/>
        
        <div class="form-row bg-light">
            <div class="w-50">
                <select name="" id="">
                    <option value="">Tỉnh thành</option>
                    <option value="1">Hà Nội</option>
                </select>
            </div>
            <div style="width: 3px;background-color: #0000;"></div>
            <div class="w-50">
                <select name="" id="">
                    <option value="">Quận huyện</option>
                    <option value="1">Hà Nội</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <button class="bg-red text-light text-bold">Tìm</button>
        </div>
    </form>
</section>
<script>
    // let data = [
    //     {id: 1, name: 'Hà Nội'},
    //     {id: 2, name: 'Hồ Chí Minh'},
    //     {id: 3, name: 'Cấn Thơ'},
    //     {id: 4, name: 'Đà Nẵng'}
    // ]
    // let m = new Suggest('.form-row.suggest-wrap')
    // let provinces = new Record(data)
</script>