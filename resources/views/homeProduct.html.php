<?php
include dirname(__FILE__) . '/includes/headerProduct.html.php';
$v_text_cate = '';
$v_link_cate = '';
if($type == 'FUNCTIONIAL'){
	$v_text_cate = 'Thực Phẩm Chức Năng';
	$v_link_cate = '/products/FUNCTIONIAL-0';
}
if($type == 'COSMECEUTICALS'){
	$v_text_cate = 'Hóa Mỹ phẩm';
	$v_link_cate = '/products/COSMECEUTICALS-0';
}
if($type == 'PERSONALCARE'){
	$v_text_cate = 'Chăm Sóc Cá Nhân';
	$v_link_cate = '/products/PERSONALCARE-0';
}
if($type == 'PRODUCTCOMPANY'){
	$v_text_cate = 'TPCN NichieiAsia';
	$v_link_cate = '/products/PRODUCTCOMPANY-0';
}
if($type == 'MEDICALDEVICES'){
	$v_text_cate = 'Thiết Bị Y Tế';
	$v_link_cate = '/products/MEDICALDEVICES-0';
}
if($type == 'ORDERFAST'){
	$v_text_cate = 'Đặt hàng nhanh';
	$v_link_cate = '/products/ORDERFAST-0';
}
?>
<div class="main-content">
    <div class="container">
        <div class="menu-prod">
            <div class="d-flex align-items-center gap-2">
                <!--<button <?php echo ($type == '') ? 'class="active"' : ''; ?>><a href="/products">FLASH SALE</a></button>
                <button <?php echo ($type == 'ORDERFAST') ? 'class="active"' : ''; ?>><a href="/products/ORDERFAST-0">Đặt hàng nhanh</a></button>-->
                <!--<button <?php echo ($type == 'FUNCTIONIAL') ? 'class="active"' : ''; ?>><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>-->
                <!--<button <?php echo ($type == 'COSMECEUTICALS') ? 'class="active"' : ''; ?>><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button <?php echo ($type == 'PERSONALCARE') ? 'class="active"' : ''; ?>><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>-->
                <!--<button <?php echo ($type == 'PRODUCTCOMPANY') ? 'class="active"' : ''; ?>><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>-->
                <!--<button <?php echo ($type == 'MEDICALDEVICES') ? 'class="active"' : ''; ?>><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>-->
            </div>
        </div>
		<?php 
		if(!empty($type) && 1 == 2){
		?>
			<nav aria-label="breadcrumb" class="breadcrumb-container">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><a href="/">Trang chủ</a></li>
					<li class="breadcrumb-item"><a href="<?php echo $v_link_cate; ?>"><?php echo $v_text_cate; ?></a></li>
					<?php 
					if($categoryByParentId){
					?>
						<li class="breadcrumb-item"><a href="<?php echo '/products/'.$categoryByParentId->rewrite.'-'.$categoryByParentId->id; ?>"><?php echo $categoryByParentId->name ?></a></li>
					<?php 
					}
					if($categoryById){
						?>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $categoryById->name ?></li>
						<?php
					}?>
				</ol>
			</nav>
        <?php 
		}
        if(intval($is_hot) > 0 && $productList){
            $now = time();
            // Trừ 15 ngày
            $start = strtotime('-15 days', $now);
            $end = strtotime('+15 days', $now);


            $v_date_start = date('Y-m-d\TH:i:s', $start);
            $v_date_end = date('Y-m-d\TH:i:s', $end);
            $productList10items = array_slice($productList,0,10);
            $productList = array_slice($productList,10);
        ?>
            <script>
                function getTimeRemaining(startDate, endDate) {
                    const now = new Date();
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    if (now < start) {
                        return { error: "Promotion has not started yet" };
                    }

                    if (now > end) {
                        return { error: "Promotion has ended" };
                    }

                    const timeDiff = end - now;

                    const seconds = Math.floor((timeDiff / 1000) % 60);
                    const minutes = Math.floor((timeDiff / 1000 / 60) % 60);
                    const hours = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);
                    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

                    return {
                        days: days,
                        hours: hours,
                        minutes: minutes,
                        seconds: seconds
                    };
                }

                function updateTimer() {
                    const startDate = "<?php echo $v_date_start; ?>";
                    const endDate = "<?php echo $v_date_end; ?>";
                    const timerDiv = document.getElementById("timeHot");

                    const remainingTime = getTimeRemaining(startDate, endDate);

                    if (remainingTime.error) {
                        timerDiv.textContent = remainingTime.error;
                    } else {
                        timerDiv.innerHTML = '<span>Còn</span><div class="hour">'+remainingTime.days+'</div>Ngày<div class="hour">'+remainingTime.hours+'</div>Giờ<div class="minutes">'+remainingTime.minutes+'</div><span>Phút</span><div class="second">'+remainingTime.seconds+'</div><span>Giây</span>';
                    }
                }

                // Update the timer every second
                setInterval(updateTimer, 1000);

                // Initial update
                updateTimer();
            </script>
            <div class="flash-sale">
                <div class="top-flash-sale">
                    <img src="<?= asset('/images/flash-sale.png') ?>" alt="flash-sale"><br />
                    <div class="flash-sale-left">    
                        <div class="count-down d-flex gap-2 align-items-center" id="timeHot">
                        </div>
                    </div>
                    <div class="flash-sale-right">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <span>Từ ngày</span>
                            <div class="date"><?php echo date('d/m/Y',strtotime($v_date_start)); ?></div>
                        </div>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <span>Đến ngày</span>
                            <div class="date"><?php echo date('d/m/Y',strtotime($v_date_end)); ?></div>
                        </div>
                    </div>
                </div>
                <div class="bottom-flash-sale">
                    <div class="swiper flash-sale-slide swiper-initialized swiper-horizontal swiper-backface-hidden">
                        <div class="swiper-wrapper" id="swiper-wrapper-863ad8227b1e233f" aria-live="polite">
                            <?php 
                            foreach($productList10items as $items){ 
                            ?>
                                <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 6" style="width: 212px; margin-right: 24px;">
                                    <div class="prod-item">
                                        <div class="thumb">
                                            <img src="<?php echo $items['avatar']['url']; ?>" alt="product">
                                        </div>
                                        <h3 class="prod-title"><a href="<?php echo '/san-pham/'.$items['rewrite'].'-'.$items['id']; ?>"><?php echo $items['name']; ?></a></h3>
                                        <div class="price"><?php echo formatCurrencyVND($items['price']); ?></div>
                                    </div>
                                </div>
                            <?php 
                            }?>
                        </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                </div>
            </div>
        <?php 
        }?>
    </div>
    <div class="container d-none d-md-block">
        <?php 
        $typeCat = '';
        if($categoryByType && !in_array($type,['PRODUCTCOMPANY','ORDERFAST'])){
        ?>
            <div class="category">
                <div class="row">
                    <?php
                    foreach($categoryByType as $items){
                        $childsArr = $items->childs;
                        $typeCat = $items->type;
                    ?>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="category-item">
                                <div class="row">
                                    <div class="col-xl-5">
                                        <div class="cat-header">
                                            <div class="cat-icon">
                                                <img src="<?php echo $items->icon; ?>" alt="cat-icon-1" />
                                            </div>
                                            <h2 class="cat-title">
                                                <?php echo $items->name; ?>
                                            </h2>
                                            <div class="count"><?php echo $items->total_product; ?> sản phẩm</div>
                                        </div>
                                    </div>
                                    <div class="col-xl-7">
                                        <div class="cat-child">
                                            <ul>
                                                <?php 
                                                if($childsArr){
                                                    foreach($childsArr as $items){
                                                    ?>
                                                    <li>
                                                        <a href="/products/<?php echo $typeCat; ?>-<?php echo $items->rewrite; ?>-<?php echo $items->id; ?>"><?php echo $items->name; ?></a>
                                                    </li>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    }?>
                </div>
            </div>
        <?php 
        }?>
    </div>
    <?php 
    if($categoryByType && !in_array($type,['PRODUCTCOMPANY','ORDERFAST'])){
        $v_text_cat = '';
        switch ($typeCat) {
            case 'FUNCTIONIAL':
                $v_text_cat = 'Thực Phẩm Chức Năng';
                break;
            case 'COSMECEUTICALS':
                $v_text_cat = 'Dược Mỹ Phẩm';
                break;
            case 'PERSONALCARE':
                $v_text_cat = 'Chăm Sóc Cá Nhân';
                break;
            case 'PRODUCTCOMPANY':
                $v_text_cat = 'Sản Phẩm Vua Dược';
                break;
            case 'MEDICALDEVICES':
                $v_text_cat = 'Thiết Bị Y Tế';
                break;
            default:
                $v_text_cat = 'Thực Phẩm Chức Năng';
        }
    ?>
        <div id="category-mb" class="d-block d-md-none">
            <div class="container">
                <h2 class="categories-title">Danh mục <?php echo $v_text_cat; ?></h2>
                <div class="accordion" id="categories">
                    <?php 
                    foreach($categoryByType as $items){
                        $childsArr = $items->childs;
                        $typeCat = $items->type;
                    ?>
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="category-1<?php echo $items->id; ?>">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-category-1<?php echo $items->id; ?>"
                                    aria-expanded="false"
                                    aria-controls="panelsStayOpen-category-1<?php echo $items->id; ?>"
                                >
                                    <div class="d-flex">
                                        <div class="icon">
                                            <img src="<?php echo $items->icon; ?>" alt="cat-icon-1">
                                        </div>
                                        <div class="info">
                                            <h3 class="category-title"><?php echo $items->name; ?></h3>
                                            <div class="count"><?php echo $items->total_product; ?> sản phẩm</div>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <?php 
                            if($childsArr){
                            ?>
                                <div id="panelsStayOpen-category-1<?php echo $items->id; ?>" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-category-1">
                                    <div class="accordion-body">
                                        <div class="cat-child">
                                            <ul>
                                                <?php 
                                                foreach($childsArr as $items){
                                                ?>
                                                    <li><a href="/products/<?php echo $typeCat; ?>-<?php echo $items->rewrite; ?>-<?php echo $items->id; ?>"><?php echo $items->name; ?></a></li>
                                                <?php 
                                                }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                            }?>
                        </div>
                    <?php 
                    }?>
                </div>
            </div>
        </div>
    <?php 
    }?>
    <div class="container">
        <div class="prod-grid">
            <div class="grid-header d-flex justify-content-between align-items-center">
                <div class="hd-left d-flex align-items-center gap-2">
                    <span>Sắp xếp theo</span>
                    <div class="d-flex">
                        <button type="button" class="d-flex btn btn-outline-primary <?php echo ($sort_type == 'ASC') ? 'active' : '';  ?>" onclick="sortProductList('ASC');" >
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.6 8.79999H12.8" stroke="#018279" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.2 12.8H12.8" stroke="#018279" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M8.8 16.8H12.8" stroke="#018279" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M16 18.4V5.59998L18.4 8.79998"
                                    stroke="#018279"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>Giá thấp đến cao
                        </button>
                        <button style="margin-left:2px;" type="button" class="d-flex btn btn-outline-primary <?php echo ($sort_type == 'DESC') ? 'active' : '';  ?>" onclick="sortProductList('DESC');">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.60001 15.2L12.8 15.2" stroke="#018279" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.20001 11.2H12.8" stroke="#018279" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M8.79999 7.20001L12.8 7.20001" stroke="#018279" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M16 5.60002L16 18.4L18.4 15.2"
                                    stroke="#018279"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>Giá cao đến thấp
                        </button>
                    </div>
                </div>
            </div>
            <?php 
            if(check_array($productList)){
            ?>
                <div class="prod-list">
                    <div class="row">
                        <?php
                        foreach($productList as $items){
                        ?>
                            <div class="col-xl-2 col-6 col-md-4 mb-4">
                                <div class="prod-item">
                                    <div class="thumb">
                                        <img src="<?php echo $items['avatar']['url']; ?>" alt="product" />
                                    </div>
                                    <h3 class="prod-title">
                                        <a href="<?php echo '/san-pham/'.$items['rewrite'].'-'.$items['id']; ?>"><?php echo $items['name']; ?></a>
                                    </h3>
                                    <div class="price"><?php echo formatCurrencyVND($items['price']); ?></div>
									<button class="btn-custom d-flex gap-2 align-items-center mt-1 btn-increment-order" data-product-id="<?php echo $items['id']; ?>">
										<svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M16.8 33.6C18.1255 33.6 19.2 34.6745 19.2 36C19.2 37.3255 18.1255 38.4 16.8 38.4C15.4745 38.4 14.4 37.3255 14.4 36C14.4 34.6745 15.4745 33.6 16.8 33.6Z" stroke="white" stroke-width="2"></path>
											<path d="M31.2 33.6001C32.5255 33.6001 33.6 34.6746 33.6 36.0001C33.6 37.3256 32.5255 38.4001 31.2 38.4001C29.8746 38.4001 28.8 37.3256 28.8 36.0001C28.8 34.6746 29.8746 33.6001 31.2 33.6001Z" stroke="white" stroke-width="2"></path>
											<path d="M8.41793 9.74694L8.74961 8.80355L8.74961 8.80355L8.41793 9.74694ZM8.33168 8.65661C7.81066 8.47343 7.23979 8.74731 7.05661 9.26833C6.87343 9.78935 7.1473 10.3602 7.66832 10.5434L8.33168 8.65661ZM12.1373 11.7168L12.963 11.1526L12.963 11.1526L12.1373 11.7168ZM14.2203 28.1379L13.4947 28.8259L13.4947 28.8259L14.2203 28.1379ZM37.8524 20.6124L38.8319 20.8143L38.8331 20.8081L37.8524 20.6124ZM37.0528 24.492L38.0322 24.6938L38.0322 24.6938L37.0528 24.492ZM37.9752 15.5153L37.1826 16.1251L37.1826 16.1251L37.9752 15.5153ZM35.4138 28.8806L34.7821 28.1054L34.7821 28.1054L35.4138 28.8806ZM13.7329 20.416V16.0614H11.7329V20.416H13.7329ZM8.74961 8.80355L8.33168 8.65661L7.66832 10.5434L8.08626 10.6903L8.74961 8.80355ZM22.3 30.6H30.7846V28.6H22.3V30.6ZM13.7329 16.0614C13.7329 14.9256 13.7343 13.994 13.6527 13.24C13.5686 12.4636 13.3886 11.7754 12.963 11.1526L11.3117 12.281C11.4817 12.5297 11.5994 12.8562 11.6643 13.4553C11.7316 14.0768 11.7329 14.8818 11.7329 16.0614H13.7329ZM8.08626 10.6903C9.14995 11.0643 9.86511 11.3175 10.3947 11.5773C10.8982 11.8244 11.1447 12.0365 11.3117 12.281L12.963 11.1526C12.5344 10.5255 11.9624 10.1188 11.2758 9.78184C10.6152 9.45774 9.76847 9.16176 8.74961 8.80355L8.08626 10.6903ZM11.7329 20.416C11.7329 22.7449 11.7553 24.4022 11.9711 25.6624C12.1993 26.9947 12.6508 27.9359 13.4947 28.8259L14.946 27.4499C14.4036 26.8778 14.1114 26.3112 13.9424 25.3248C13.7611 24.2662 13.7329 22.7936 13.7329 20.416H11.7329ZM22.3 28.6C20.0391 28.6 18.4494 28.5976 17.2471 28.4271C16.0792 28.2615 15.4249 27.955 14.946 27.4499L13.4947 28.8259C14.402 29.7829 15.5565 30.2074 16.9663 30.4073C18.3416 30.6024 20.0986 30.6 22.3 30.6V28.6ZM12.7329 15.592H32.1421V13.592H12.7329V15.592ZM36.873 20.4105L36.0734 24.2901L38.0322 24.6938L38.8318 20.8143L36.873 20.4105ZM32.1421 15.592C33.5086 15.592 34.727 15.5933 35.6922 15.7012C36.1723 15.7549 36.5429 15.8305 36.8117 15.925C37.0885 16.0223 37.1703 16.1091 37.1826 16.1251L38.7678 14.9056C38.4249 14.4599 37.937 14.2005 37.4747 14.0381C37.0045 13.8729 36.4667 13.7754 35.9144 13.7136C34.8145 13.5907 33.4716 13.592 32.1421 13.592V15.592ZM38.8331 20.8081C39.105 19.4458 39.3313 18.3233 39.3866 17.4283C39.4431 16.5115 39.3362 15.6444 38.7678 14.9056L37.1826 16.1251C37.3246 16.3096 37.4339 16.5998 37.3904 17.3051C37.3455 18.0322 37.1552 18.9966 36.8718 20.4167L38.8331 20.8081ZM30.7846 30.6C32.0083 30.6 33.0167 30.6018 33.8262 30.5027C34.663 30.4003 35.4033 30.1792 36.0455 29.6558L34.7821 28.1054C34.5415 28.3014 34.216 28.4401 33.5833 28.5175C32.9233 28.5983 32.0584 28.6 30.7846 28.6V30.6ZM36.0734 24.2901C35.8163 25.5377 35.64 26.3844 35.4276 27.0145C35.2241 27.6186 35.0226 27.9094 34.7821 28.1054L36.0455 29.6558C36.6877 29.1325 37.0537 28.4521 37.3229 27.6532C37.5833 26.8804 37.7852 25.8924 38.0322 24.6938L36.0734 24.2901Z" fill="white"></path>
										</svg>
										<span class="text-white">Đặt Hàng</span>
									</button>
                                </div>
                            </div>
                        <?php  
                        }?>
                    </div>
                </div>
            <?php 
            }?>
            <div class="grid-bottom d-flex justify-content-center align-items-center">
                <?php
                if($pagination){
                    echo renderPagination($pagination);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/includes/footerProduct.html.php';
?>