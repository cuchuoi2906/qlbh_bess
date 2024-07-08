<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
?>
<div class="main-content">
    <div class="container">
        <div class="menu-prod">
            <div class="d-flex align-items-center gap-2">
                <button <?php echo ($type == '') ? 'class="active"' : ''; ?>><a href="/products">FLASH SALE</a></button>
                <button <?php echo ($type == 'FUNCTIONIAL') ? 'class="active"' : ''; ?>><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button <?php echo ($type == 'COSMECEUTICALS') ? 'class="active"' : ''; ?>><a href="/products/COSMECEUTICALS-0">Dược Mỹ Phẩm</a></button>
                <button <?php echo ($type == 'PERSONALCARE') ? 'class="active"' : ''; ?>><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button <?php echo ($type == 'PRODUCTCOMPANY') ? 'class="active"' : ''; ?>><a href="/products/PRODUCTCOMPANY-0">Sản Phẩm Vua Dược</a></button>
                <button <?php echo ($type == 'MEDICALDEVICES') ? 'class="active"' : ''; ?>><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>
            </div>
        </div>
    </div>
    <div class="container d-none d-md-block">
        <?php 
        $typeCat = '';
        if($categoryByType){
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
                                            <div class="count"><?php echo $items->count_pro; ?> sản phẩm</div>
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
    if($categoryByType){
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
                            <h2 class="accordion-header" id="category-1">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-category-1"
                                    aria-expanded="false"
                                    aria-controls="panelsStayOpen-category-1"
                                >
                                    <div class="d-flex">
                                        <div class="icon">
                                            <img src="<?php echo $items->icon; ?>" alt="cat-icon-1">
                                        </div>
                                        <div class="info">
                                            <h3 class="category-title"><?php echo $items->name; ?></h3>
                                            <div class="count"><?php echo $items->count_pro; ?> sản phẩm</div>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <?php 
                            if($childsArr){
                            ?>
                                <div id="panelsStayOpen-category-1" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-category-1">
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
                    <button type="button" class="btn btn-outline-primary <?php echo ($sort_type == 'ASC') ? 'active' : '';  ?>" onclick="sortProductList('ASC');" >
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
                        </svg>
                        Giá từ thấp đến cao
                    </button>
                    <button type="button" class="btn btn-outline-primary <?php echo ($sort_type == 'DESC') ? 'active' : '';  ?>" onclick="sortProductList('DESC');">
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
                        </svg>

                        Giá từ cao đến thấp
                    </button>
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
                                    <!--<div class="btn-favorite">
                                        <div class="outline">
                                            <img src="<?= asset('/images/icons/heart.svg') ?>" />
                                        </div>
                                        <div class="fill">
                                            <img src="<?= asset('/images/icons/heart-fill.svg') ?>" />
                                        </div>
                                    </div>-->
                                    <h3 class="prod-title">
                                        <a href="/"><?php echo $items['name']; ?></a>
                                    </h3>
                                    <div class="price"><?php echo formatCurrencyVND($items['price']); ?></div>
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
            <div class="grid-bottom d-flex justify-content-between align-items-center">
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