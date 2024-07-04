<?php
include dirname(__FILE__) . '/../includes/header.html.php';
?>
        <div class="main-content">
            <div class="container">
                <div class="news-category">
                    <div class="swiper news-tag-slide">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><a href="/">Phòng & Chữa Bệnh</a></div>
                            <div class="swiper-slide"><a class="active" href="/">Dinh Dưỡng</a></div>
                            <div class="swiper-slide"><a href="/">Mẹ Và Bé</a></div>
                            <div class="swiper-slide"><a href="/">Tin Khuyến Mãi</a></div>
                            <div class="swiper-slide"><a href="/">Người Cao Tuổi</a></div>
                            <div class="swiper-slide"><a href="/">Khỏe Đẹp</a></div>
                            <div class="swiper-slide"><a href="/">Giới Tính</a></div>
                            <div class="swiper-slide"><a href="/">Tin Tức Sức Khỏe</a></div>
                            <div class="swiper-slide"><a href="/">Truyền Thông</a></div>
                        </div>
                    </div>
                </div>
                <?php
                $postAll = $items->toArray();
                $fristNews = $postAll[0];
                $news_3_next = array_slice($postAll,1,4);
                $NextNews = array_slice($postAll,4);
                ?>
                <div id="news-feature" class="mb-3">
                    <div class="row">
                        <div class="col-xl-7 mb-4 mb-xl-0">
                            <div class="news-feature-1">
                                <div class="thumb">
                                    <a href="<?php echo url('post.detail', [$fristNews['rewrite'],$fristNews['id']]) ?>">
                                        <img src="<?php echo $fristNews['image']; ?>" alt="news-1" />
                                    </a>
                                </div>
                                <div class="inner">
                                    <div class="category"><a href="<?php echo url('post.listing', ['news',$fristNews['category']['id']]); ?>"><?php echo $fristNews['category']['name']; ?></a></div>
                                    <a href="<?php echo url('post.detail', [$fristNews['rewrite'],$fristNews['id']]) ?>"
                                        ><h3 class="news-title"><?php echo $fristNews['title']; ?></h3></a
                                    >
                                    <?php 
                                    $creatAtt = '';
                                    if($fristNews['created_at'] instanceof DateTime){
                                        $creatAtt = $fristNews['created_at']->format('H:i:s d-m-Y'); // Định dạng ngày giờ theo ý muốn
                                    }
                                    ?>
                                    <div class="date"><?php echo $creatAtt; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <?php 
                            foreach($news_3_next as $items){
                            ?>
                                <div class="news-feature-r mb-3">
                                    <div class="row">
                                        <div class="col-md-5 pe-md-4">
                                            <a href="<?php echo url('post.detail', [$items['rewrite'],$items['id']]) ?>">
                                                <img src="<?php echo $items['image']; ?>" alt="news-2" />
                                            </a>
                                        </div>
                                        <div class="col-md-7 ps-md-0">
                                            <div class="category mt-2 mt-md-0"><a href="<?php echo url('post.listing', ['news',$fristNews['category']['id']]); ?>"><?php echo $fristNews['category']['name']; ?></a></div>
                                            <a href="<?php echo url('post.detail', [$items['rewrite'],$items['id']]) ?>">
                                                <h3 class="news-title">
                                                    <?php echo $items['title']; ?>
                                                </h3>
                                            </a>
                                            <?php 
                                            $creatAt = '';
                                            if($items['created_at'] instanceof DateTime){
                                                $creatAtt = $items['created_at']->format('H:i:s d-m-Y'); // Định dạng ngày giờ theo ý muốn
                                            }
                                            ?>
                                            <div class="date"><?php echo $creatAt; ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                            }?>
                        </div>
                    </div>
                </div>
                <?php 
                if(check_array($NextNews)){
                ?>
                    <div id="news-list">
                        <h2 class="news-title">Tin Tức Mới</h2>
                        <div class="row">
                            <?php 
                            foreach($NextNews as $items){
                            ?>
                                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                                    <div class="news-item">
                                        <div class="thumb">
                                            <a href="<?php echo url('post.detail', [$items['rewrite'],$items['id']]) ?>">
                                                <img src="<?php echo $items['image']; ?>" />
                                            </a>
                                        </div>
                                        <div class="inner">
                                            <div class="category"><a href="<?php echo url('post.listing', ['news',$fristNews['category']['id']]); ?>"><?php echo $fristNews['category']['name']; ?></a></div>
                                            <a href="<?php echo url('post.detail', [$items['rewrite'],$items['id']]) ?>">
                                                <h3 class="title"><?php echo $items['title']; ?></h3>
                                            </a>
                                            <div class="desc">
                                                <?php echo $items['teaser']; ?>
                                            </div>
                                            <?php 
                                            $creatAt = '';
                                            if($items['created_at'] instanceof DateTime){
                                                $creatAtt = $items['created_at']->format('H:i:s d-m-Y'); // Định dạng ngày giờ theo ý muốn
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
                <?php 
                }?>
                <div class="grid-bottom d-flex justify-content-center align-items-center">
                    <?php
                    echo renderPagination($pagination);
                    ?>
                </div>
            </div>
        </div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>