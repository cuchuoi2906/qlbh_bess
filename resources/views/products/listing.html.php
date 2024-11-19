<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
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
	$v_text_cate = 'Tất cả sản phẩm';
	$v_link_cate = '/products/ORDERFAST-0';
}
?>
<div class="main-content">
    <div class="container">
        <div class="menu-prod">
            <div class="d-flex align-items-center gap-2">
                <button <?php echo ($type == 'ORDERFAST') ? 'class="active"' : ''; ?>><a href="/products/ORDERFAST-0">Tất cả sản phẩm</a></button>
                <button <?php echo ($type == '') ? 'class="active"' : ''; ?>>
                    <a href="/order-fast">
                        Đặt hàng nhanh
                        <img src="<?= asset('/images/icon_new.gif') ?>" width="40" style="padding-left:3px" />
                    </a>
                </button>
                <!--<button <?php echo ($type == 'FUNCTIONIAL') ? 'class="active"' : ''; ?>><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button <?php echo ($type == 'COSMECEUTICALS') ? 'class="active"' : ''; ?>><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button <?php echo ($type == 'PERSONALCARE') ? 'class="active"' : ''; ?>><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button <?php echo ($type == 'PRODUCTCOMPANY') ? 'class="active"' : ''; ?>><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>
                <button <?php echo ($type == 'MEDICALDEVICES') ? 'class="active"' : ''; ?>><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>-->
            </div>
        </div>
		<?php 
		if(!empty($type)){
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
		}?>
        <div class="main-search mb-3 d-xl-none">
            <div class="input-group input-group-search-mobile">
                <input type="text" id="keywordm" name="keyword" value="<?php echo isset($keyword) ? $keyword : ''; ?>" class="form-control" placeholder="Nhập tên thuốc, hoạt chất cần tìm..." aria-label="basic-search" aria-describedby="basic-search">
                <span class="input-group-text" id="main-search">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.6" cy="11.6" r="7.6" stroke="#8A909F" stroke-width="1.5"></circle>
                        <path d="M17.2 17.2L20 20" stroke="#8A909F" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </span>
            </div>
            <div id="dropdown-list-suggest-mobile" class="position-relative"></div>
        </div>
        <?php
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
                                $pricePolicies = $items['pricePolicies'];
                                $htmlPriceSl = "";
                                if(check_array($pricePolicies)){
                                    foreach($pricePolicies as $price){
                                        $htmlPriceSl .= '<div class="price-sl">Mua số lượng từ '.$price['quantity'].' giá '.formatCurrencyVND($price['price']).'</div>';
                                    }
                                }
                            ?>
                                <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 6" style="width: 212px; margin-right: 24px;">
                                    <div class="prod-item">
                                        <div class="thumb">
                                            <a href="<?php echo '/san-pham/'.$items['rewrite'].'-'.$items['id']; ?>">
                                                <img src="<?php echo $items['avatar']['url']; ?>" alt="product">
                                            </a>
                                        </div>
                                        <h3 class="prod-title"><a href="<?php echo '/san-pham/'.$items['rewrite'].'-'.$items['id']; ?>"><?php echo $items['name']; ?></a></h3>
                                        <?php 
                                        if(intval($items['price'])>0){
                                            if(intval($items['db_discount_price'])>0){ ?>
                                            <div>
                                                <span class="price"><?php echo formatCurrencyVND($items['db_discount_price']); ?></span>
                                                <span class="text-decoration-line-through price float-end fw-normal color-gray"><?php echo formatCurrencyVND($items['db_price']); ?></span>
                                            </div>
                                            <?php 
                                            }else{ ?>
                                                <div class="price text-end"><?php echo formatCurrencyVND($items['db_price']); ?></div>
                                                <?php
                                            }
                                        }else{
                                            ?>
                                            <span class="badge rounded-pill badge-succes text-end mb-3">Liên hệ để có giá tốt</span>
                                            <?php
                                        }
                                        echo $htmlPriceSl;
                                        if(intval($items['price'])>0){
                                        ?>
                                            <span class="badge rounded-pill btn-warning text-end mb-3">Giá tốt nhất thị trường</span>
                                        <?php 
                                        }?>
                                        <div class="input-group number-input">
                                            <div class="input-group-prepend">
                                                <button data-product-id="<?php echo $items['id']; ?>" class="btn btn-decrement" type="button" disabled="">
                                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="16" cy="16" r="10.6667" stroke="#C3CCEC" stroke-width="1.5"></circle>
                                                        <path d="M19.2 16H12.8" stroke="#C3CCEC" stroke-width="1.5" stroke-linecap="round"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <input id="productCount<?php echo $items['id']; ?>" type="number" class="form-control inputNumber" value="0" min="0">
                                            <div class="input-group-append">
                                                <button data-product-id="<?php echo $items['id']; ?>" class="btn btn-increment" type="button">
                                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="16" cy="16" r="10.6667" stroke="#1C274C" stroke-width="1.5"></circle>
                                                        <path d="M19.2 16L16 16M16 16L12.8 16M16 16L16 12.8M16 16L16 19.2" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
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
                        //pre($productList);
                        foreach($productList as $items){
                            $pricePolicies = $items['pricePolicies'];
                            $htmlPriceSl = "";
                            if(check_array($pricePolicies)){
                                foreach($pricePolicies as $price){
                                    $htmlPriceSl .= '<div class="price-sl">Mua số lượng từ '.$price['quantity'].' giá '.formatCurrencyVND($price['price']).'</div>';
                                }
                            }
                            
                        ?>
                            <div class="col-xl-2 col-6 col-md-4 mb-4" style="padding-left: 4px !important;padding-right: 4px !important;">
                                <div class="prod-item">
                                    <div class="thumb">
                                        <a href="<?php echo '/san-pham/'.$items['rewrite'].'-'.$items['id']; ?>">
                                            <img src="<?php echo $items['avatar']['url']; ?>" alt="product" />
                                        </a>
                                    </div>
                                    <h3 class="prod-title">
                                        <a href="<?php echo '/san-pham/'.$items['rewrite'].'-'.$items['id']; ?>"><?php echo $items['name']; ?></a>
                                    </h3>
                                    <?php 
                                    if(intval($items['price'])>0){
                                        if(intval($items['db_discount_price'])>0){ ?>
                                        <div>
                                            <span class="price"><?php echo formatCurrencyVND($items['db_discount_price']); ?></span>
                                            <span class="text-decoration-line-through price float-end fw-normal color-gray"><?php echo formatCurrencyVND($items['db_price']); ?></span>
                                        </div>
                                        <?php 
                                        }else{ ?>
                                            <div class="price text-end"><?php echo formatCurrencyVND($items['db_price']); ?></div>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <span class="badge rounded-pill bg-success text-end mb-3">Liên hệ để có giá tốt</span>
                                        <?php
                                    }
                                    ?>
                                    <?php 
                                    echo $htmlPriceSl;
                                    if(intval($items['price'])>0){
                                    ?>    
                                        <span class="badge rounded-pill btn-warning text-end mb-2">Giá tốt nhất thị trường</span>
                                    <?php 
                                    }?>
                                    <div class="input-group number-input">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-decrement" type="button" data-product-id="<?php echo $items['id']; ?>" disabled>
                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="16" cy="16" r="10.6667" stroke="#C3CCEC" stroke-width="1.5" />
                                                    <path d="M19.2 16H12.8" stroke="#C3CCEC" stroke-width="1.5" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="number" id="productCount<?php echo $items['id']; ?>" class="form-control inputNumber" value="0" min="0" />
                                        <div class="input-group-append">
                                            <button class="btn btn-increment" data-product-id="<?php echo $items['id']; ?>" type="button">
                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="16" cy="16" r="10.6667" stroke="#1C274C" stroke-width="1.5" />
                                                    <path
                                                        d="M19.2 16L16 16M16 16L12.8 16M16 16L16 12.8M16 16L16 19.2"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                    />
                                                </svg>
                                            </button>
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
include dirname(__FILE__) . '/../includes/footer.html.php';
?>