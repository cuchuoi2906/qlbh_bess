<?php
include dirname(__FILE__) . '/../includes/header.html.php';
?>
<style>
    body {
      background-color: #f8f9fa;
    }
    .form-section {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 20px;
      margin-top: 30px;
    }
    .section-title {
      font-weight: bold;
      margin-bottom: 20px;
      font-size: 18px;
      color: #333;
    }
    .upload-text {
      color: #0d6efd;
      cursor: pointer;
      text-decoration: underline;
    }
    .btn-save {
      background-color: #7fa6c7;
      color: #fff;
      font-weight: bold;
      padding: 6px 25px;
    }
    .btn-save:hover {
      background-color: #6c8db0;
      color: #fff;
    }
</style>
<div class="main-content">
    <div class="container">
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-section">
                <div class="section-title">Thông tin cơ sở kinh doanh</div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Tên cơ sở kinh doanh <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="business_name" value="<?php echo $user['name']; ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã số thuế</label>
                        <input type="text" class="form-control" name="tax_code" value="<?php echo $user['tax_code']; ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Địa chỉ (số nhà, tên đường)</label>
                    <input type="text" class="form-control" name="address" value="<?php echo $user['address_register']; ?>" readonly>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <tbody>
                            <tr>
                                <td>1. Căn cước công dân (Upload cùng lúc ảnh mặt trước / mặt sau)</td>
                                <td>
                                    <label class="btn btn-outline-primary btn-sm">
                                        Upload ảnh
                                        <input type="file" name="cccd[]" accept="image/*" multiple hidden>
                                    </label>
                                    <span class="file-name text-muted small"></span>
                                    <?php 
                                    
                                    if($user['cccd_img'] != ""){
                                        $arrcccd = explode(',', $user['cccd_img']);
                                        foreach($arrcccd as $items){
                                        ?>
                                            <a href="<?php echo '/upload/users/'.$items; ?>" target="_blank" />
                                                <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" 
                                                        width="20px" height="20px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                                   <g>
                                                       <path d="M51.8,25.1C47.1,15.6,37.3,9,26,9S4.9,15.6,0.2,25.1c-0.3,0.6-0.3,1.3,0,1.8C4.9,36.4,14.7,43,26,43
                                                           s21.1-6.6,25.8-16.1C52.1,26.3,52.1,25.7,51.8,25.1z M26,37c-6.1,0-11-4.9-11-11s4.9-11,11-11s11,4.9,11,11S32.1,37,26,37z"/>
                                                       <path d="M26,19c-3.9,0-7,3.1-7,7s3.1,7,7,7s7-3.1,7-7S29.9,19,26,19z"/>
                                                   </g>
                                                </svg>
                                            </a>
                                    <?php 
                                        }
                                    }?>
                                </td>
                            </tr>
                            <tr>
                                <td>2. Giấy phép đăng ký kinh doanh</td>
                                <td>
                                    <label class="btn btn-outline-primary btn-sm">
                                        Upload ảnh
                                        <input type="file" name="business_license" accept="image/*" hidden>
                                    </label>
                                    <span class="file-name text-muted small"></span>
                                    <?php 
                                    if($user['business_license_img'] != ''){
                                    ?>
                                        <a href="<?php echo '/upload/users/'.$user['business_license_img']; ?>" target="_blank" />
                                            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" 
                                                    width="20px" height="20px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                               <g>
                                                   <path d="M51.8,25.1C47.1,15.6,37.3,9,26,9S4.9,15.6,0.2,25.1c-0.3,0.6-0.3,1.3,0,1.8C4.9,36.4,14.7,43,26,43
                                                       s21.1-6.6,25.8-16.1C52.1,26.3,52.1,25.7,51.8,25.1z M26,37c-6.1,0-11-4.9-11-11s4.9-11,11-11s11,4.9,11,11S32.1,37,26,37z"/>
                                                   <path d="M26,19c-3.9,0-7,3.1-7,7s3.1,7,7,7s7-3.1,7-7S29.9,19,26,19z"/>
                                               </g>
                                            </svg>
                                        </a>
                                    <?php 
                                    }?>
                                </td>
                            </tr>
                            <tr>
                                <td>3. Giấy phép đăng ký kinh doanh dược</td>
                                <td>
                                    <label class="btn btn-outline-primary btn-sm">
                                        Upload ảnh
                                        <input type="file" name="pharma_license" accept="image/*" hidden>
                                    </label>
                                    <span class="file-name text-muted small"></span>
                                    <?php 
                                    if($user['pharma_license_img'] != ''){
                                    ?>
                                        <a href="<?php echo '/upload/users/'.$user['pharma_license_img']; ?>" target="_blank" />
                                            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" 
                                                    width="20px" height="20px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                               <g>
                                                   <path d="M51.8,25.1C47.1,15.6,37.3,9,26,9S4.9,15.6,0.2,25.1c-0.3,0.6-0.3,1.3,0,1.8C4.9,36.4,14.7,43,26,43
                                                       s21.1-6.6,25.8-16.1C52.1,26.3,52.1,25.7,51.8,25.1z M26,37c-6.1,0-11-4.9-11-11s4.9-11,11-11s11,4.9,11,11S32.1,37,26,37z"/>
                                                   <path d="M26,19c-3.9,0-7,3.1-7,7s3.1,7,7,7s7-3.1,7-7S29.9,19,26,19z"/>
                                               </g>
                                            </svg>
                                        </a>
                                    <?php 
                                    }?>
                                </td>
                            </tr>
                            <tr>
                                <td>4. Giấy chứng nhận đạt chuẩn GPP</td>
                                <td>
                                    <label class="btn btn-outline-primary btn-sm">
                                        Upload ảnh
                                        <input type="file" name="gpp_cert" accept="image/*" hidden>
                                    </label>
                                    <span class="file-name text-muted small"></span>
                                    <?php 
                                    if($user['gpp_cert_img'] != ''){
                                    ?>
                                        <a href="<?php echo '/upload/users/'.$user['gpp_cert_img']; ?>" target="_blank" />
                                            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" 
                                                    width="20px" height="20px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                               <g>
                                                   <path d="M51.8,25.1C47.1,15.6,37.3,9,26,9S4.9,15.6,0.2,25.1c-0.3,0.6-0.3,1.3,0,1.8C4.9,36.4,14.7,43,26,43
                                                       s21.1-6.6,25.8-16.1C52.1,26.3,52.1,25.7,51.8,25.1z M26,37c-6.1,0-11-4.9-11-11s4.9-11,11-11s11,4.9,11,11S32.1,37,26,37z"/>
                                                   <path d="M26,19c-3.9,0-7,3.1-7,7s3.1,7,7,7s7-3.1,7-7S29.9,19,26,19z"/>
                                               </g>
                                            </svg>
                                        </a>
                                    <?php 
                                    }?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-save btn-primary">LƯU LẠI</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Hiển thị tên file khi người dùng chọn
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        const fileNameSpan = this.closest('td').querySelector('.file-name');
        if (this.files.length > 0) {
            const names = Array.from(this.files).map(f => f.name).join(', ');
            fileNameSpan.textContent = names;
        } else {
            fileNameSpan.textContent = '';
        }
    });
});

// Xử lý gửi form bằng AJAX
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('my-profile-business', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        alert(data.message || 'Upload thành công!');
        location.reload();
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra khi upload.');
    });
});
</script>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>