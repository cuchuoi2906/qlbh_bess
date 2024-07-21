<?php
include dirname(__FILE__) . '/../includes/header.html.php';
?>
        <div class="main-content">
            <div class="container">
                <?php 
                if($cateogryChildren){
                ?>
                    <div class="news-category">
                        <div class="swiper news-tag-slide">
                            <div class="swiper-wrapper">
                                <?php 
                                foreach($cateogryChildren as $cate){
                                    $class = ($idCate == $cate->id) ? 'class="active"' : "";
                                ?>
                                    <div class="swiper-slide">
                                        <a href="/bai-viet/news-<?php echo $cate->id; ?>" <?php echo $class; ?>><?php echo $cate->name; ?></a>
                                    </div>
                                <?php 
                                }?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                if($postAll){
                    $items = $postAll->data;
                    $pagination = $postAll->meta->pagination;
                    $postAll = $items->toArray();
                    $fristNews = $postAll[0];
                    $news_3_next = array_slice($postAll,1,3);
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
                                        <!--<div class="category"><a href="<?php echo url('post.listing', ['news',$fristNews['category']['id']]); ?>"><?php echo $fristNews['category']['name']; ?></a></div>-->
                                        <a href="<?php echo url('post.detail', [$fristNews['rewrite'],$fristNews['id']]) ?>"
                                            ><h3 class="news-title"><?php echo $fristNews['title']; ?></h3>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5">
                                <?php 
                                foreach($news_3_next as $items){
                                ?>
                                    <div class="news-feature-r mb-3 news-line-items">
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
                                        <div class="news-item news-line-items">
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
                <?php 
                }?>
            </div>
        </div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>