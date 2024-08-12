function formatCurrencyVND(number) {
    // Sử dụng Intl.NumberFormat để định dạng số
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(number);
}

document.addEventListener('DOMContentLoaded', () => {
    var swiper_1 = new Swiper('.slide-banner', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        spaceBetween: 24,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
    });
    var swiper_2 = new Swiper('.section-4-slide-1', {
        spaceBetween: 18,
        slidesPerView: 'auto',
        breakpoints: {
            768: {
                spaceBetween: 24,
            },
        },
    });
    var swiper_3 = new Swiper('.section-4-slide-2', {
        spaceBetween: 18,
        slidesPerView: 'auto',
        breakpoints: {
            768: {
                spaceBetween: 24,
            },
        },
    });
    var swiper_4 = new Swiper('.slide-feedback', {
        spaceBetween: 18,
        slidesPerView: 1,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 1.3,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 1.6,
                spaceBetween: 24,
            },
            1200: {
                slidesPerView: 2,
                spaceBetween: 24,
            },
        },
    });
    var swiper_5 = new Swiper('.slide-news', {
        spaceBetween: 18,
        slidesPerView: 1.3,
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 24,
            },
            1200: {
                slidesPerView: 4,
                spaceBetween: 24,
            },
        },
    });
    var flash_sale_slide = new Swiper('.flash-sale-slide', {
        spaceBetween: 18,
        slidesPerView: 2,
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 24,
            },
            1200: {
                slidesPerView: 5,
                spaceBetween: 24,
            },
        },
    });
    
    var news_tag_slide = new Swiper('.news-tag-slide', {
        spaceBetween: 16,
        slidesPerView: 'auto',
    });

    const toggleMenu = () => {
        document.querySelector('.menu-mb').classList.toggle('active');
    };

    document.getElementById('toggle-menu').addEventListener('click', toggleMenu);
    document.getElementById('close-sidebar').addEventListener('click', toggleMenu);
    document.getElementById('sidebar-overlay').addEventListener('click', toggleMenu);

    const incrementButtons = document.querySelectorAll('.btn-increment');
    const decrementButtons = document.querySelectorAll('.btn-decrement');
    const favoriteButtons = document.querySelectorAll('.btn-favorite');

    // Thêm sự kiện click cho mỗi nút tăng
    incrementButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const inputNumber = this.parentElement.previousElementSibling;
            inputNumber.value = parseInt(inputNumber.value) + 1;

            // Kích hoạt nút trừ tương ứng nếu giá trị lớn hơn 0
            if (parseInt(inputNumber.value) > 0) {
                inputNumber.previousElementSibling.querySelector('.btn-decrement').disabled = false;
            }
        });
    });

    decrementButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const inputNumber = this.parentElement.nextElementSibling;
            const newValue = parseInt(inputNumber.value) - 1;
            inputNumber.value = newValue;

            if (newValue <= 0) {
                this.disabled = true;
            }
        });
    });

    favoriteButtons.forEach((button) => {
        button.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    });

    let backToTopBtn = document.getElementById('back-to-top');

    if (backToTopBtn) {
        window.onscroll = function () {
            scrollFunction();
        };

        backToTopBtn.addEventListener('click', function () {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        });
    }

    const scrollFunction = () => {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            backToTopBtn.style.display = 'flex';
        } else {
            backToTopBtn.style.display = 'none';
        }
    };

    const toggleVoucherDrawer = () => {
        document.querySelector('#voucher-list').classList.toggle('active');
    };

    const viewVoucher = document.getElementById('view-voucher');
    if (viewVoucher) {
        document.getElementById('view-voucher').addEventListener('click', toggleVoucherDrawer);
        if(document.getElementById('drawer-overlay')){
            document.getElementById('drawer-overlay').addEventListener('click', toggleVoucherDrawer);
        }
        if(document.getElementById('drawer-close')){
            document.getElementById('drawer-close').addEventListener('click', toggleVoucherDrawer);
        }
    }
    // Function to submit the input value
    function submitInputSearchProduct(type) {
        const inputElement = document.getElementById('keyword');
        let inputValue = inputElement.value;
        if(inputValue == '' || type == 2){
            inputElementm = document.getElementById('keywordm');
            inputValue = inputElementm.value;
        }
        const baseUrl = window.location.origin + window.location.pathname;

        // Append the input value as a query parameter
        const fullUrl = `${baseUrl}?keyword=${encodeURIComponent(inputValue)}`;

        // Redirect to the new URL
        window.location.href = fullUrl;
    }

    const button = document.getElementById('main-search');
    button.addEventListener('click', function(){
		submitInputSearchProduct(2);
	});

    const buttonPc = document.getElementById('main-search-pc');
    buttonPc.addEventListener('click', submitInputSearchProduct);

    // Event listener for the Enter key
    const inputElement = document.getElementById('keyword');
    inputElement.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission if within a form
            submitInputSearchProduct();
        }
    });
    // Event listener for the Enter key
    let inputElementm = document.getElementById('keywordm');
    inputElementm.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission if within a form
            submitInputSearchProduct(2);
        }
    });
    $('.btn-increment, .btn-decrement').click(function() {
        var productId = $(this).data('product-id');
        let productCount = $("#productCount"+productId).val();
        $.ajax({
            url: '/cart',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount
            },
            success: function(response) {
                if(document.getElementById('pageCart')){
                    let total_money = parseInt(response.meta.total_money);
                    let total_product = parseInt(response.meta.total_product);
                    if(response.data.length > 0){
                        for(i=0;i<response.data.length;i++){
                            let id = response.data[i].product.id;
                            let quantity = parseInt(response.data[i].quantity);
                            let price = parseInt(response.data[i].product.price);
                            if(document.getElementById('total-price-product'+id)){
                                $('#total-price-product'+id).text(formatCurrencyVND(quantity*price));
                            }
                        }
                    }
                    $('#totalMoney').text(formatCurrencyVND(total_money));
                    $('#totalProduct').text(formatCurrencyVND(total_product));
                    
                }
                totalProduct = parseInt(response.meta.total_product_cart);
                $('#cartCount').text(totalProduct);
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    $('#btnOrder').click(function() {
        var productId = $(this).data('product-id');
        let productCount = $("#productCount"+productId).val();
        $.ajax({
            url: '/order',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount
            },
            success: function(response) {
                if(response.code == 200){
                    alert("Đơn hàng đặt thành công. Bạn vui lòng chuyển khoản để Hoàn thành.");
					orderId = response.data.vars.id;
                    window.location.href = '/payment/'+orderId;
                }else{
                    alert(response.error);
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
});

// Function to submit the input value
function sortProductList(sort) {
    const inputElement = document.getElementById('keyword');
    let inputValue = inputElement.value;
    if(inputValue == ''){
        inputElementm = document.getElementById('keywordm');
        inputValue = inputElementm.value;
    }
    const baseUrl = window.location.origin + window.location.pathname;

    // Append the input value as a query parameter
    const fullUrl = `${baseUrl}?sort_by=price&sort_type=`+sort;

    // Redirect to the new URL
    window.location.href = fullUrl;
}

