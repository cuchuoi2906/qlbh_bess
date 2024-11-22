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
            if(document.getElementById('keywordm')){
                
            }else{
                inputElementm = document.getElementById('keywordSearchFast');
            }
            inputValue = inputElementm.value;
        }
        const baseUrl = window.location.origin + window.location.pathname;

        // Append the input value as a query parameter
        const fullUrl = `${baseUrl}?keyword=${encodeURIComponent(inputValue)}`;

        // Redirect to the new URL
        window.location.href = fullUrl;
    }
	
	if(document.getElementById('main-search')){
		const button = document.getElementById('main-search');
		button.addEventListener('click', function(){
			submitInputSearchProduct(2);
		});
	}
	
	if(document.getElementById('main-search-pc')){
		const buttonPc = document.getElementById('main-search-pc');
		buttonPc.addEventListener('click', submitInputSearchProduct);
	}
    // Event listener for the Enter key
	if(document.getElementById('keyword')){
		const inputElement = document.getElementById('keyword');
		inputElement.addEventListener('keypress', function(event) {
			if (event.key === 'Enter') {
				event.preventDefault(); // Prevent form submission if within a form
				submitInputSearchProduct();
			}
		});
	}
    // Event listener for the Enter key
	if(document.getElementById('keywordm')){
		let inputElementm = document.getElementById('keywordm');
		inputElementm.addEventListener('keypress', function(event) {
			if (event.key === 'Enter') {
				event.preventDefault(); // Prevent form submission if within a form
				submitInputSearchProduct(2);
			}
		});
	}
	
    $('.btn-increment, .btn-decrement').click(function() {
		if(document.getElementById("btn-increment-cart")){
			return;
		}
        var productId = $(this).data('product-id');
        let productCount = $("#productCount"+productId).val();
        $.ajax({
            url: '/cart',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount,
                is_add_more: 3,
            },
            success: function(response) {
                if(document.getElementById('pageCart')){
                    let total_money = parseInt(response.meta.total_money);
                    let total_money_origin = parseInt(response.meta.total_money_origin);
                    let total_product = parseInt(response.meta.total_product);
                    let total_discount = parseInt(response.meta.total_discount);
                    if(response.data.length > 0){
                        for(i=0;i<response.data.length;i++){
                            let id = response.data[i].product.id;
                            let quantity = parseInt(response.data[i].quantity);
                            let price = parseInt(response.data[i].product.price);
                            let discount_price = parseInt(response.data[i].product.discount_price);
                            let price_policy = parseInt(response.data[i].product.price_policy);
                            price = discount_price ? discount_price : price;
                            price = price_policy ? price_policy : price;
                            if(document.getElementById('priceDetail'+id)){
                                $('#priceDetail'+id).text(formatCurrencyVND(price));
                            }
                            if(document.getElementById('total-price-product'+id)){
                                $('#total-price-product'+id).text(formatCurrencyVND(quantity*price));
                            }
                            
                        }
                        $('#totalMoneyOrigin').text(formatCurrencyVND(total_money_origin));
                        $('#totalMoney').text(formatCurrencyVND(total_money));
                        $('#totalProduct').text(total_product);
                        $('#salePriceTotal').text(formatCurrencyVND(total_discount));
                    }
                }
                if(response.data.length > 0){
                    totalProduct = parseInt(response.meta.total_product_cart);
                    $('#cartCount').text(totalProduct);
                    $('#cartCountM').text(totalProduct);
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
	
	$('.btn-increment-cart').click(function() {
		var productId = $(this).data('product-id');
		let productCount = $("#productCount"+productId).val();
        $.ajax({
            url: '/cart',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount,
				is_add_more: 1
            },
            success: function(response) {
				console.log(response);
				totalProduct = parseInt(response.meta.total_product_cart);
				if(totalProduct > 0){
					location.reload();
				}
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
	});
	
	$('.btn-increment-order').click(function() {
        var productId = $(this).data('product-id');
        let productCount = 1;
        $.ajax({
            url: '/cart',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount,
				is_add_more: 1
            },
            success: function(response) {
				totalProduct = parseInt(response.meta.total_product_cart);
				if(totalProduct > 0){
					window.location.href = '/cart';
				}
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
	
	$('.btn-product-delete, .btn-product-delete-m').click(function() {
        var productId = $(this).data('product-id');
		$.ajax({
            url: '/cart/delete',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId
            },
            success: function(response) {
				console.log(response);
				if(response){
					alert("Xóa thành công sản phẩm.");
					location.reload();
				}
			}
		})
	});

    $('#btnOrder').click(function() {
        var productId = $(this).data('product-id');
        let productCount = $("#productCount"+productId).val();
        let noteValue = $("#exampleFormControlTextarea1").val();
        let ship_name = $("#ship_name").val();
        let ship_address = $("#ship_address").val();
        if(noteValue == ""){
            alert("Bạn phải nhập ghi chú.");
            return;
        }
        $.ajax({
            url: '/order',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount,
				note:noteValue,
				ship_name:ship_name,
				ship_address:ship_address,
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
	
	$('#btnOrder2').click(function() {
        var productId = $(this).data('product-id');
        let productCount = $("#productCount"+productId).val();
        let noteValue = $("#exampleFormControlTextarea1").val();
		let username = $("#username").val();
        let phone = $("#phone").val();
        if(noteValue == ""){
            alert("Bạn phải nhập ghi chú.");
            return;
        }
		if(username == ''){
			alert("Bạn phải nhập Họ và tên.");
			return;
		}
		if(phone == ''){
			alert("Bạn phải nhập Số điện thoại.");
			return;
		}
		
		if(noteValue == ''){
			alert("Bạn phải nhập địa chỉ nhận hàng.");
			return;
		}
		if (!validatePhoneNumber(phone)) {
			alert("Số điện thoại không hợp lệ.");
			return;
		}
		
        $.ajax({
            url: '/order',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount,
				note:noteValue,
				phone:phone,
				username:username
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
$(document).ready(function(){
	// Hàm tạo cookie
	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
	}

	// Hàm lấy giá trị của cookie
	function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}

	// Kiểm tra cookie, nếu chưa tồn tại thì hiển thị popup
	if (getCookie('acceptedRule')) {
		$('#fixed-bottom-bar').hide();
	}

	// Khi người dùng nhấn nút "Tôi đồng ý"
	$('#acceptRule').click(function(){
		setCookie('acceptedRule', 'yes', 30); // Lưu cookie trong 30 ngày
		$('#fixed-bottom-bar').hide(); // Ẩn popup
	});
	$('#iconUpdateShip').click(function(){
		$('#fixed-bottom-bar').hide(); // Ẩn popup
	});
    // Hàm xử lý khi người dùng nhập vào textbox
    $('#keyword').on('keyup', function() {
        let query = $(this).val().trim();
        console.log(query);
        if (query.length > 0) {
            $.ajax({
                url: '/api/products/search', // Thay thế bằng URL trả về JSON
                type: 'GET',
                data: { keyword: query,type:"ORDERFAST"},
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if(response.data){
                        let products = response.data;
                        let htmlContent = '<div class="dropdown-list-suggest d-block"><ul class="list-unstyled">';
                        products.forEach(function(product) {
                            let htmlPrice = '';
                            if(product.discount_price){
                                htmlPrice = `<span class="price text-decoration-line-through color-gray px-1">${formatCurrencyVND(product.price)}</span>
                                            <span class="price px-4">${formatCurrencyVND(product.discount_price)}</span>`;
                            }else{
                                htmlPrice = `<span class="price px-4">${formatCurrencyVND(product.price)}</span>`;
                            }
                            let htmlPricePolicies = '';
                            if(typeof product.pricePolicies[0].price !== 'undefine' && product.pricePolicies[0].price){
                                htmlPricePolicies = `<div class="price-sl">Mua số lượng từ 5 giá ${formatCurrencyVND(product.pricePolicies[0].price)}</div>`;
                            }
                            htmlContent += `
                                <li>
                                    <div style="display: flex;align-items: center;">
                                        <div>
                                            <img src="${product.avatar.url}" alt="${product.name}" style="height: 70px;">
                                        </div>
                                        <div>
                                            <a class="w-100" href="/san-pham/${product.rewrite}-${product.id}">${product.name}</a>
                                            <div>
                                                ${htmlPrice}
                                            </div>
                                            ${htmlPricePolicies}
                                        </div>
                                    </div>
                                </li>`;
                        });                                    
                        htmlContent += '</ul></div>';
                        console.log(htmlContent);
                        // Hiển thị dữ liệu với hiệu ứng mượt mà
                        $('#dropdown-list-suggest').html(htmlContent).fadeIn('fast');
                    }
                },
                error: function() {
                    console.error('Error fetching data');
                }
            });
        } else {
            // Ẩn dropdown khi textbox trống
            $('#dropdown-list-suggest').fadeOut('fast');
        }
    });
    $('#keywordm').on('keyup', function() {
        let query = $(this).val().trim();
        if (query.length > 0) {
            $.ajax({
                url: '/api/products/search', // Thay thế bằng URL trả về JSON
                type: 'GET',
                data: { keyword: query,type:"ORDERFAST"},
                dataType: 'json',
                success: function(response) {
                    if(response.data){
                        let products = response.data;
                        let htmlContent = '<div class="dropdown-list-suggest d-block"><ul class="list-unstyled">';
                        products.forEach(function(product) {
                            let htmlPrice = '';
                            if(product.discount_price){
                                htmlPrice = `<span class="price text-decoration-line-through color-gray px-1">${formatCurrencyVND(product.price)}</span>
                                            <span class="price px-4">${formatCurrencyVND(product.discount_price)}</span>`;
                            }else{
                                htmlPrice = `<span class="price px-4">${formatCurrencyVND(product.price)}</span>`;
                            }
                            let htmlPricePolicies = '';
                            if(typeof product.pricePolicies[0].price !== 'undefine' && product.pricePolicies[0].price){
                                htmlPricePolicies = `<div class="price-sl">Mua số lượng từ 5 giá ${formatCurrencyVND(product.pricePolicies[0].price)}</div>`;
                            }
                            htmlContent += `
                                <li>
                                    <div style="display: flex;align-items: center;">
                                        <div>
                                            <img src="${product.avatar.url}" alt="${product.name}" style="height: 70px;">
                                        </div>
                                        <div>
                                            <a class="w-100" href="/san-pham/${product.rewrite}-${product.id}">${product.name}</a>
                                            <div>
                                                ${htmlPrice}
                                            </div>
                                            ${htmlPricePolicies}
                                        </div>
                                    </div>
                                </li>`;
                        });
                        htmlContent += '</ul></div>';
                        // Hiển thị dữ liệu với hiệu ứng mượt mà
                        $('#dropdown-list-suggest-mobile').html(htmlContent).fadeIn('fast');
                    }
                },
                error: function() {
                    console.error('Error fetching data');
                }
            });
        } else {
            // Ẩn dropdown khi textbox trống
            $('#dropdown-list-suggest').fadeOut('fast');
        }
    });
});
function validatePhoneNumber(phoneNumber) {
	var regex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
	return regex.test(phoneNumber);
}
// Thực hiện load lại trang
function loadPagePagination(obj){
	let page = parseInt(obj.getAttribute("data-page"));
	const inputElement = document.getElementById('keyword');
	let inputValue = inputElement.value;
	if(inputValue == ''){
		inputElementm = document.getElementById('keywordm');
		inputValue = inputElementm.value;
	}
	const baseUrl = window.location.origin + window.location.pathname;
	let fullUrl = "";
	// Append the input value as a query parameter
	if(inputValue != ""){
		fullUrl = `${baseUrl}?page=${page}&keyword=${encodeURIComponent(inputValue)}`;
	}else{
		fullUrl = `${baseUrl}?page=${page}`;
	}
	console.log(fullUrl);

	// Redirect to the new URL
	window.location.href = fullUrl;
}
$(document).ready(function(){
    // Hàm xử lý khi người dùng nhập vào textbox
    $('#keywordSearchFast').on('keyup', function() {
        let query = $(this).val().trim();
        if (query.length > 0) {
            loadProductFast(query);
            $('#titleFastOrder').hide();
        } else {
            // Ẩn dropdown khi textbox trống
            loadProductFast("");
            $('#titleFastOrder').show();
        }
    });
    loadProductCartOrderfast();
    loadProductFast("");
    $('#clearSearchIconFast').click(function() {
        $('#keywordSearchFast').val('').trigger('keyup');
    });
});
function loadProductFast(query){
    $.ajax({
        url: '/api/products/search', // Thay thế bằng URL trả về JSON
        type: 'GET',
        data: { keyword: query,type:"ORDERFAST",page_size:30},
        dataType: 'json',
        success: function(response) {
            if(response.data){
                let products = response.data;
                let htmlContent = '';
                $('#dropdown-list-suggest-fast').empty();
                products.forEach(function(product) {
                let htmlPrice = '<div><span class="badge rounded-pill bg-success text-end mb-3">Liên hệ để có giá tốt</span></div>';
                let htmlPriceRight = '';
                if(product.price){
                    htmlPrice = `<div class="price"><span class="text-decoration-line-through price fw-normal color-gray">${formatCurrencyVND(product.price)}</span></div><div class="badge rounded-pill btn-warning text-end mb-2" style="width: fit-content;">Giá tốt nhất thị trường</div>`;
                    htmlPriceRight = `<div class="price-fast text-center">${formatCurrencyVND(product.db_discount_price)}</div>`;
                }
                let buy_quality = 0;
                if(document.getElementById('productCountCart'+product.id)){
                    buy_quality = $('#productCountCart'+product.id).val();
                }
                htmlContent += `
                    <div class="content-box mb-3">
                        <div class="cart-item d-flex align-items-start justify-content-between">
                            <div class="left d-flex align-items-center gap-3">
                                <div class="thumb">
                                    <img width="100" height="100" src="${product.avatar.url}" alt="${product.name}" />
                                </div>
                                <div class="info d-flex flex-column">
                                    <h3 class="cart-item-title">${product.name}</h3>
                                    ${htmlPrice}
                                    ${product.pricePolicies.map(price => `<div class="price-sl mb-2">Mua số lượng từ ${price.quantity} giá ${formatCurrencyVND(price.price)}</div>`).join('')}
                                </div>
                            </div>
                            <div class="right h-100">
                                ${htmlPriceRight}
                                <div class="input-group number-input">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-decrement-fast" type="button" onclick="incrementDecrementCartFast(${product.id},1,1)" data-product-id="${product.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path d="M5 10H15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="number" id="productCount${product.id}" class="form-control inputNumber" value="${buy_quality}" min="0">
                                    <div class="input-group-append">
                                        <button class="btn btn-increment-fast" onclick="incrementDecrementCartFast(${product.id},2,1)" type="button" data-product-id="${product.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path d="M5 10H15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M10 15V5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
                // Hiển thị dữ liệu với hiệu ứng mượt mà
                $('#dropdown-list-suggest-fast').append(htmlContent).fadeIn('fast');
            }
        },
        error: function() {
            console.error('Error fetching data');
        }
    });
}
function loadProductCartOrderfast(){
    $.ajax({
        url: '/api/cart', // Thay thế bằng URL trả về JSON
        type: 'GET',
        data: {},
        dataType: 'json',
        success: function(response) {
            // Xóa nội dung cũ trước khi thêm mới
            $('#content-box-order-fast-scroll').empty();
            if(response.data.length <=0){
                return;
            }
            // Lặp qua danh sách sản phẩm từ kết quả trả về
            response.data.forEach(function(productData) {
                let product = productData.product;
                let price = product.db_discount_price ? product.db_discount_price : product.price;
                price = product.price_policy ? product.price_policy : price;
                let productHTML = `
                    <div class="content-box-order-fast mb-2">
                        <div class="cart-item d-flex align-items-start justify-content-between mb-2">
                            <div class="left d-flex align-items-center gap-3">
                                <div class="thumb">
                                    <img width="100" height="100" src="${product.avatar.url}" alt="prod-item">
                                </div>
                                <div class="info d-flex flex-column">
                                    <h3 class="cart-item-title">${product.name}</h3>
                                    <div class="price-fast" id="priceDetail${product.id}">${formatCurrencyVND(price)}</div>
                                    <div class="input-group number-input">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-decrement-fast-cart" type="button" onclick="incrementDecrementCartFast(${product.id},1,2)" data-product-id="${product.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M5 10H15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="number" id="productCountCart${product.id}" class="form-control inputNumber" value="${product.buy_quantity}" min="0">
                                        <div class="input-group-append">
                                            <button class="btn btn-increment-fast-cart" onclick="incrementDecrementCartFast(${product.id},2,2)" type="button" data-product-id="${product.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M5 10H15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M10 15V5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right h-100">
                                <div class="cta d-flex justify-content-start flex-column text-end h-100 align-items-end h-100">
                                    <div class="d-sm-flex d-none align-items-center btn-product-delete" onclick="deleteProductCartFast(${product.id})" data-product-id="${product.id}"> 
                                        <button><img src="https://vuaduoc.com/assets//images/icons/Delete.svg" alt="heart">Xóa</button>
                                    </div>
                                    <div class="close d-sm-none d-block" data-product-id="${product.id}" onclick="deleteProductCartFast(${product.id})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.5299 6.21195C16.7252 6.01678 16.7252 5.7002 16.5299 5.50502C16.3347 5.30985 16.0182 5.30985 15.823 5.50502L11.5 9.82801L7.17695 5.50502C6.98177 5.30985 6.6652 5.30985 6.47002 5.50502C6.27485 5.7002 6.27485 6.01678 6.47002 6.21195L10.793 10.535L6.47002 14.858C6.27485 15.0532 6.27485 15.3698 6.47002 15.5649C6.6652 15.7601 6.98177 15.7601 7.17695 15.5649L11.5 11.2419L15.823 15.5649C16.0182 15.7601 16.3347 15.7601 16.5299 15.5649C16.7252 15.3698 16.7252 15.0532 16.5299 14.858L12.2069 10.535L16.5299 6.21195Z" fill="#C4C4C4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                // Thêm HTML vào danh sách sản phẩm
                $('#content-box-order-fast-scroll').append(productHTML);
            });
            $("#totalMoney").text(formatCurrencyVND(response.meta.total_money));
            $("#totalProduct").text(response.meta.total_product);
            $("#cartCount").text(response.meta.total_product_cart);
        },
        error: function() {
            console.log('Error fetching data');
        }
    });
}
function deleteProductCartFast(productId){
    $.ajax({
        url: '/cart/delete',
        method: 'POST',
        //contentType: 'application/json',
        data: {
            product_id: productId
        },
        success: function(response) {
            if(response){
                alert("Xóa thành công sản phẩm.");
                loadProductCartOrderfast();
            }
        }
    })
}
function deleteProductCartFastAll(){
    $.ajax({
        url: '/cart/deleteall',
        method: 'POST',
        //contentType: 'application/json',
        data: {},
        success: function(response) {
            if(response){
                alert("Xóa thành công giỏ hàng.");
                location.reload();
            }
        }
    })
}
function incrementDecrementCartFast(productId,typeinde,typeCart){
    let productCount = 0;
    if(typeCart == 1){
        productCount = parseInt($("#productCount"+productId).val());
    }else{
        productCount = parseInt($("#productCountCart"+productId).val());
    }
    if(document.getElementById('content-box-order-fast-scroll')){
        if(typeinde == 1){
            productCount = productCount-1;
        }else{
            productCount = productCount+1;
        }
        
        if(!productCount){
            return;
        }
        
        let is_add_more = 3;
        $.ajax({
            url: '/cart',
            method: 'POST',
            //contentType: 'application/json',
            data: {
                product_id: productId,
                quantity: productCount,
                is_add_more: is_add_more,
            },
            success: function(response) {
                loadProductCartOrderfast();
            }
        });
        if(typeCart == 1){
            $("#productCount"+productId).val(productCount);
        }
    }
}
$(document).ready(function(){
    setTimeout(function(){
        if(isDesktopScreen){
            let fixPosRight = findYPos(document.getElementById('container_cart_fast'));
            window.onscroll = function() {
                doScroll('container_cart_fast',fixPosRight,'left-cart-fast');
            }
        }
    },1500);
    
    $('#iconUpdateShip').click(function(){
        let ship_name = $("#ship_name").val();
        let ship_address = $("#ship_address").val();
        if(ship_address == ""){
            alert("Bạn phải nhập địa chỉ.");
            return;
        }
        if(ship_name == ""){
            alert("Bạn phải tên.");
            return;
        }
        $.ajax({
            url: '/api/auth/update-address',
            method: 'POST',
            //contentType: 'application/json',
            data: {
				ship_name:ship_name,
				ship_address:ship_address,
            },
            success: function(response) {
                console.log(response);
                if(response.code == 200){
                    alert("Sửa thông tin người Nhận hàng thành công.");
					location.reload();
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

function f_filterResults(e, t, n) {
    e = e || 0;
    return t && (!e || t < e) && (e = t), n && 300 < n && (!e || n < e) ? n : e;
}
function f_scrollTop() {
    return f_filterResults(window.pageYOffset || 0, document.documentElement ? document.documentElement.scrollTop : 0, document.body ? document.body.scrollTop : 0);
}
function f_clientHeight() {
    return f_filterResults(window.innerHeight || 0, document.documentElement ? document.documentElement.clientHeight : 0, document.body ? document.body.clientHeight : 0);
}
function findPos(e) {
    if ("" != e && void 0 !== e && null != e) {
        for (var t = e.offsetLeft, n = e.offsetTop; e.offsetParent && e != document.getElementsByTagName("body")[0]; ) (t += e.offsetParent.offsetLeft), (n += e.offsetParent.offsetTop), (e = e.offsetParent);
        return [t, n];
    }
}
function findYPos(e) {
    e = findPos(e);
    return "" != e && void 0 !== e && null != e ? e[1] : "";
}
// Hàm Scroll tôi lấy từ 24h
function doScroll(divID, fixPos, parentID) {
    var obj= document.getElementById(divID);
    var objParent= document.getElementById(parentID);
    var parentPos = findYPos(objParent);
    var floorPos = parentPos+objParent.offsetHeight;
    
    if (  f_scrollTop()>fixPos && fixPos+obj.offsetHeight!=floorPos) {
        if (f_scrollTop()+obj.offsetHeight >= floorPos) {
            obj.style.position = 'absolute';
            obj.style.top = (floorPos-obj.offsetHeight)+'px';
        }
        else {
            obj.style.position = 'fixed';
            var heightTop = 0;
            obj.style.top = heightTop+'px';
        }
    }
    else {
        obj.removeAttribute("style");
    }
}
function isDesktopScreen() {
    return window.innerWidth >= 760;
}
function onOffEditShip(){
    let infoShipDefau = document.getElementsByClassName('infoShipDefau');
    if(infoShipDefau.length >0){
        let v_fag_on_off_button_edit = true;
        for(let i = 0;i<infoShipDefau.length;i++){
            let objShipDefau = infoShipDefau[i];
            if(objShipDefau.style.display == 'none'){
                objShipDefau.style.display = "block";
            }else{
                objShipDefau.style.display = "none";
                v_fag_on_off_button_edit = false;
            }
        }
        let infoShipEdit = document.getElementsByClassName('infoShipEdit');
        if(infoShipEdit.length >0){
            for(let j = 0;j<infoShipEdit.length;j++){
                let objShipEdit = infoShipEdit[j];
                if(objShipEdit.style.display == 'block'){
                    objShipEdit.style.display = "none";
                }else{
                    objShipEdit.style.display = "block";
                }
            }
        }
        if(v_fag_on_off_button_edit){
            document.getElementById("iconEditShip").style.display = "block";
            document.getElementById("iconUpdateShip").style.display = "none";
        }else{
            document.getElementById("iconUpdateShip").style.display = "block";
            document.getElementById("iconEditShip").style.display = "none";
        }
    }
}