<?php
include dirname(__FILE__) . '/../includes/header.html.php';
?>
    <div class="main-content">
        <div class="container">
            <div class="news-category">
                <div class="swiper news-tag-slide">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><a href="/">Phòng & Chữa Bệnh</a></div>
                        <div class="swiper-slide"><a href="/">Dinh Dưỡng</a></div>
                        <div class="swiper-slide"><a href="/">Mẹ Và Bé</a></div>
                        <div class="swiper-slide"><a class="active" href="/">Tin Khuyến Mãi</a></div>
                        <div class="swiper-slide"><a href="/">Người Cao Tuổi</a></div>
                        <div class="swiper-slide"><a href="/">Khỏe Đẹp</a></div>
                        <div class="swiper-slide"><a href="/">Giới Tính</a></div>
                        <div class="swiper-slide"><a href="/">Tin Tức Sức Khỏe</a></div>
                        <div class="swiper-slide"><a href="/">Truyền Thông</a></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8">
                    <h1 class="artical-title"><?php echo $item->title; ?></h1>
                    <div class="article">
                        <?php
                        echo $item->content;
                        ?>
                    </div>
                </div>
                <?php 
                if($postCategory){
                ?>
                    <div class="col-xl-4">
                        <h2 class="sidebar-title">Có thể bạn quan tâm</h2>
                        <div class="similar-list">
                            <div class="row">
                                <?php 
                                foreach($postCategory as $items){
                                ?>
                                    <div class="col-12 col-xl-12 col-md-6 news-similar-item mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-5">
                                                <a href="<?php echo url('post.detail', [$items->rewrite,$items->id]) ?>">
                                                    <img src="<?php echo $items->image; ?>" alt="news-detail-1" />
                                                </a>
                                            </div>
                                            <div class="col-7 ps-md-0 ps-md-3">
                                                <a href="<?php echo url('post.detail', [$items->rewrite,$items->id]) ?>">
                                                    <h3 class="news-title"><?php echo $items->title; ?></h3>
                                                </a>
                                                <?php 
                                                $creatAt = '';
                                                if($items->created_at instanceof DateTime){
                                                    $creatAtt = $items->created_at->format('H:i:s d-m-Y'); // Định dạng ngày giờ theo ý muốn
                                                }
                                                ?>
                                                <div class="date"><?php echo $creatAtt; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                }?>
                            </div>
                        </div>
                    </div>
                <?php 
                }?>
            </div>
        </div>
    </div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>