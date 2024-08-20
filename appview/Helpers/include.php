<?php

/**
 * Chứa các function helpers do bạn tự định nghĩa
 */

use AppView\Repository\SettingRepository;

if (!function_exists('locale')) {
    function locale($locale = null)
    {
        if ($locale) {
            return app('translator')->setLocale($locale);
        }

        return app('translator')->getLocale();
    }
}

if (!function_exists('locales')) {
    function locales()
    {
        $locales = config('app.locales');

        return collect_recursive($locales);
    }
}

if (!function_exists('locale_from_cookie')) {
    function locale_from_cookie()
    {
        $locale = $_COOKIE['locale'] ?? config('app.locale');
        $locales = config('app.locales');

        return array_key_exists($locale, $locales) ? $locale : config('app.locale');
    }
}

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {

        if (!app()->has('settings')) {
            $settings = (new SettingRepository())->all();
            app()->singleton('settings', $settings->keyBy('key'));
        }
        $settings = app('settings');

        return isset($settings[$key]) ? $settings[$key]->value : $default;
    }
}

if (!function_exists('setting_image')) {
    function setting_image($key, $default = null)
    {
        $image_name = setting($key, $default);

        return url() . '/upload/settings/' . $image_name;
    }
}

if (!function_exists('url_back')) {

    function url_back()
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

}
if (!function_exists('show_paginate')) {
    /**
     * Tao ra môt doan phan trang
     *
     * @param array $data : mảng truyền vào để kiểm tra phân trang
     * @param array $append
     * @return string
     * @throws Exception
     */
    function show_paginate($data = array(), $append = array())
    {
        $lastPage = $data['vars']['lastPage'];
        $lpm1 = $lastPage - 1;
        $currentPage = $data['vars']['currentPage'];
        $adjacents = 2;
        $next = $currentPage + 1;
        $dot = "<li class='dot'><a>...</a></li>";
        $previous = $currentPage - 1;

        $html_first_two = "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => 1]) . "'>1</a></li>
                           <li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => 2]) . "'>2</a></li>";
        $html_near_last = "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $lpm1]) . "'>$lpm1</a></li>
                           <li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $lastPage]) . "'>$lastPage</a></li>";

        $pagination = '';
        if ($lastPage > 6) {
            $pagination .= "<ul class='pagination'>";
            if ($currentPage > 1) {
                $pagination .= "<li class='page-item'><a class='page-link current' title='Về trang đầu' href='" . append_url($append, ['page' => 1]) . "'><i class='icon-double-angle-left' aria-hidden='true'></i></a></li>";
                $pagination .= "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $previous]) . "'><i class='icon-angle-left' aria-hidden='true'></i></a></li>";
            }

            if ($lastPage < 3 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastPage; $counter++) {
                    $pagination .= ($counter == $currentPage)
                        ? "<li class='active page-item'><a class='page-link'>$counter</a></li>"
                        : "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $counter]) . "'>$counter</a></li>";
                }
            } elseif ($lastPage > 3 + ($adjacents * 2)) {
                // trường hợp dành cho việc phân trang lúc đầu nhỏ hơn 5
                if ($currentPage < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 2 + ($adjacents * 2); $counter++) {
                        $pagination .= ($counter == $currentPage)
                            ? "<li class='active page-item'><a class='page-link'>$counter</a></li>"
                            : "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $counter]) . "'>$counter</a></li>";
                    }
                    //$pagination .= $dot . $html_near_last;
                } //trường hợp dành cho page cuối cùng -4 lớn hơn page đang click
                elseif ($lastPage - ($adjacents * 2) > $currentPage && $currentPage > ($adjacents * 2)) {
                    //$pagination .= $html_first_two;
                    //$pagination .= $dot;

                    for ($counter = $currentPage - $adjacents; $counter <= $currentPage + $adjacents; $counter++) {
                        $pagination .= ($counter == $currentPage)
                            ? "<li class='active page-item'><a class='page-link'>$counter</a></li>"
                            : "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $counter]) . "'>$counter</a></li>";
                    }
                    //$pagination .= $dot . $html_near_last;
                } // trường hợp click vào các page cuối cùng
                else {
                    //$pagination .= $html_first_two;
                    //$pagination .= $dot;

                    for ($counter = $lastPage - (2 + ($adjacents * 2)); $counter <= $lastPage; $counter++) {
                        $pagination .= ($counter == $currentPage)
                            ? "<li class='active page-item'><a class='page-link'>$counter</a></li>"
                            : "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $counter]) . "'>$counter</a></li>";
                    }
                }
            }

            if ($currentPage < $lastPage - 2) {
                $pagination .= "<li class='page-item'><a class='page-link' title='>' href='" . append_url($append, ['page' => $next]) . "'><i class='icon-angle-right' aria-hidden='true'></i></a></li>";
                $pagination .= "<li class='page-item'><a class='page-link' title='>>' href='" . append_url($append, ['page' => $lastPage]) . "'><i class='icon-double-angle-right' aria-hidden='true'></i></a></li>";
            }
            $pagination .= "</ul>";
        } elseif ($lastPage > 1) {
            $pagination .= "<ul class='pagination'>";
            for ($counter = 1; $counter <= $lastPage; $counter++) {
                $pagination .= ($counter == $currentPage)
                    ? "<li class='active page-item'><a class='page-link'>$counter</a></li>"
                    : "<li class='page-item'><a class='page-link' href='" . append_url($append, ['page' => $counter]) . "'>$counter</a></li>";
            }
        } else {
            $pagination = '';
        }

        return $pagination;
    }
}

if (!function_exists('append_url')) {
    /**
     * render ra url muốn append vào url phân tr
     *
     * @param array $append : mang truyen vao can append
     * @param array $page
     * @return str
     * @throws Exception
     */
    function append_url($append = array(), $page = array())
    {
        $r = null;
        $urlUri = getQueryUri();
        $dataUrl = explode('?', $urlUri);
        $redirectUrl = $dataUrl[0] && $dataUrl[0] != '/' ? $dataUrl[0] : '';
        $urlQuerry = isset($dataUrl[1]) ? $dataUrl[1] : '';
        parse_str($urlQuerry, $data);

        // check mảng append link để xuất ra link
        $appendLink = array();
        $append = $page ? array_merge($append, $page) : $append;

        foreach ($append as $ka => $va) {
            if (preg_match('/^\d+$/', $ka))
                throw new Exception('Giá trị ' . $va . ' gán link có key= ' . $ka . ' phải là một chuoi');

            if ($va) $appendLink[urlencode($ka)] = urlencode($ka) . '=' . urlencode($va);
        }

        // check mang param query để thay đổi nếu có k
        if ($data) {
            foreach ($data as $k => $value) {
                if ($appendLink) {
                    foreach ($appendLink as $ka => $va) {
                        //nếu trùng key thì đổi value
                        if ($k == $ka) {
                            $r[$k] = $appendLink[$ka];
                        } // khác key thì thêm value
                        else {
                            $r[$ka] = $appendLink[$ka];
                        }
                    }
                }
            }
        }
        $r = $r ? $r : $appendLink;

        return $redirectUrl . '?' . implode('&', $r);
    }
}

if (!function_exists('getQueryUri')) {
    /**
     * Tra ve cac tham so query tren url
     *
     * @return string
     */
    function getQueryUri()
    {
        return $_SERVER['REQUEST_URI'];
    }
}


if (!function_exists('fb_share')) {

    function fb_share()
    {
        return 'https://www.facebook.com/sharer/sharer.php?u=' . current_url();
    }

}
if (!function_exists('gplus_share')) {

    function gplus_share()
    {
        return 'https://plus.google.com/share?url=' . current_url();
    }

}

if (!function_exists('limit_text')) {

    function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }

        return $text;
    }
}

function send_message_to_firebase($topic_name, $title, $content, $link = '')
{

    $topic_name = config('app.env') . '_' . $topic_name;
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'to' => '/topics/' . $topic_name,
        'notification' => array(
            "body" => strip_tags($content),
            "title" => $title,
//            "icon" => "myicon",
            "sound" => "default",
//            "click_action" => ''
        ),
        'data' => [
            'link' => ''
        ]
    );
    $fields = json_encode($fields);
    $headers = array(
        'Authorization: key=' . config('fcm.api_key'),
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

/**
 * https://developers.google.com/instance-id/reference/server
 */
function fcm_add_device_to_topic($registration_id, $topic_name = 'TOPIC_USER_ALL')
{

    $topic_name = config('app.env') . '_' . $topic_name;

    $data = [
        'to' => '/topics/' . trim($topic_name),
        'registration_tokens' => [$registration_id]
    ];
    $uri = 'https://iid.googleapis.com/iid/v1:batchAdd';
    $ch = curl_init($uri);

    $data_string = json_encode($data);

    curl_setopt_array($ch, array(
        CURLOPT_HTTPHEADER => array(
            'Authorization: key=' . config('fcm.api_key'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => 1
    ));

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $out = curl_exec($ch);
    curl_close($ch);

    return $out;
}

function fcm_delete_device_in_topic($registration_id, $topic_name = 'TOPIC_USER_ALL')
{
    $topic_name = config('app.env') . '_' . $topic_name;

    $data = [
        'to' => '/topics/' . trim($topic_name),
        'registration_tokens' => [$registration_id]
    ];
    $uri = 'https://iid.googleapis.com/iid/v1:batchRemove';
    $ch = curl_init($uri);

    $data_string = json_encode($data);

    curl_setopt_array($ch, array(
        CURLOPT_HTTPHEADER => array(
            'Authorization: key=' . config('fcm.api_key'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => 1
    ));

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $out = curl_exec($ch);
    curl_close($ch);

    return $out;
}


/**
 * @param $user_id
 * @param $amount
 * @param int $wallet_type
 * @param $note
 * @return bool
 */
function sub_money($user_id, $amount, $wallet_type = 0, $note, $source = 0, $source_type = 0)
{
    $user = \App\Models\Users\Users::findByID($user_id);
    if ($user) {

        //Check chưa có ví thì tạo ví
        if (!$user->wallet ?? false) {
            \App\Models\Users\UserWallet::insert([
                'usw_user_id' => $user->id,
                'usw_money_main' => 0,
                'usw_money_hold' => 0
            ]);
            $user = \App\Models\Users\Users::findByID($user_id);
        }

        if ($wallet_type == \App\Models\UserMoneyLog::TYPE_MONEY_ADD) {
            $user->wallet->charge -= $amount;
            \App\Models\UserMoneyLog::insert([
                'uml_user_id' => $user->id,
                'uml_amount' => (int)($amount * -1),
                'uml_current' => $user->wallet->charge,
                'uml_note' => $note,
                'uml_type' => $wallet_type,
                'uml_source' => $source,
                'uml_source_type' => $source_type,
                'uml_log_type' => ($source_type == \App\Models\UserMoneyLog::SOURCE_TYPE_ORDER) ? 1 : 0
            ]);
        } elseif ($wallet_type == \App\Models\UserMoneyLog::TYPE_COMMISSION) {
            $user->wallet->commission -= $amount;
            \App\Models\UserMoneyLog::insert([
                'uml_user_id' => $user->id,
                'uml_amount' => (int)($amount * -1),
                'uml_current' => $user->wallet->commission,
                'uml_note' => $note,
                'uml_type' => $wallet_type,
                'uml_source' => $source,
                'uml_source_type' => $source_type,
                'uml_log_type' => ($source_type == \App\Models\UserMoneyLog::SOURCE_TYPE_ORDER) ? 1 : 0
            ]);
        } elseif ($wallet_type == \App\Models\UserMoneyLog::TYPE_TRANSFER) {
            $user->wallet->charge += $amount;
            $user->wallet->commission -= $amount;
            \App\Models\UserMoneyLog::insert([
                'uml_user_id' => $user->id,
                'uml_amount' => $amount,
                'uml_current' => $user->wallet->charge,
                'uml_note' => $note,
                'uml_type' => \App\Models\UserMoneyLog::TYPE_MONEY_ADD,
                'uml_source' => $source,
                'uml_source_type' => $source_type,
                'uml_log_type' => ($source_type == \App\Models\UserMoneyLog::SOURCE_TYPE_ORDER) ? 1 : 0
            ]);
            \App\Models\UserMoneyLog::insert([
                'uml_user_id' => $user->id,
                'uml_amount' => (int)($amount * -1),
                'uml_current' => $user->wallet->commission,
                'uml_note' => $note,
                'uml_type' => \App\Models\UserMoneyLog::TYPE_TRANSFER,
                'uml_source' => $source,
                'uml_source_type' => $source_type,
                'uml_log_type' => ($source_type == \App\Models\UserMoneyLog::SOURCE_TYPE_ORDER) ? 1 : 0
            ]);
        }

        $affected = $user->wallet->update();

        \AppView\Helpers\Notification::to($user->id, 'Thay đổi số dư tài khoản', $note);

        return $affected;
    }

    return false;
}

/**
 * @param $user_id
 * @param $amount
 * @param int $wallet_type
 * @param $note
 * @return bool
 * @throws RuntimeException
 */
function add_money($user_id, $amount, $wallet_type = \App\Models\UserMoneyLog::TYPE_MONEY_ADD, $note, $money_source = 0, $source_type = 0, $log_type = 0)
{
    $user = \App\Models\Users\Users::findByID($user_id);
    if ($user) {

        //Check chưa có ví thì tạo ví
        if (!$user->wallet ?? false) {
            \App\Models\Users\UserWallet::insert([
                'usw_user_id' => $user->id,
                'usw_charge' => 0,
                'usw_commission' => 0
            ]);
            $user = \App\Models\Users\Users::findByID($user_id);
        }

        if ($wallet_type == \App\Models\UserMoneyLog::TYPE_MONEY_ADD) {
            $user->wallet->charge += $amount;
            \App\Models\UserMoneyLog::insert([
                'uml_user_id' => $user->id,
                'uml_amount' => $amount,
                'uml_current' => $user->wallet->charge,
                'uml_note' => $note,
                'uml_type' => $wallet_type,
                'uml_source' => (int)$money_source,
                'uml_source_type' => $source_type,
                'uml_log_type' => $log_type
            ]);
        } elseif ($wallet_type == \App\Models\UserMoneyLog::TYPE_COMMISSION) {
            $user->wallet->commission += $amount;
            \App\Models\UserMoneyLog::insert([
                'uml_user_id' => $user->id,
                'uml_amount' => $amount,
                'uml_current' => $user->wallet->commission,
                'uml_note' => $note,
                'uml_type' => $wallet_type,
                'uml_source' => (int)$money_source,
                'uml_source_type' => $source_type,
                'uml_log_type' => $log_type
            ]);
        } else {
            throw new RuntimeException('Loại ví không đúng', 500);
        }

        $affected = $user->wallet->update();

        \AppView\Helpers\Notification::to($user->id, 'Thay đổi số dư tài khoản', $note);

        return $affected;
    }

    return false;
}


if (!function_exists('get_user_ip')) {
    function get_user_ip()
    {
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}


if (!function_exists('format_money')) {
    function format_money($price)
    {
        return number_format($price, 0, ',', '.') . " đ";
    }
}

if (!function_exists('disable_debug_bar')) {
    function disable_debug_bar()
    {
        $app_config = config('app');
        $app_config['debugbar'] = false;
        app('config')->set('app', $app_config);
    }
}

if (!function_exists('api_404')) {
    function api_404($message = '')
    {
        throw new RuntimeException($message ?: 'Không tìm thấy tài nguyên', 404);
    }

    function user_400()
    {
        api_404('Người dùng không tồn tại');
    }
}

if (!function_exists('compare_user')) {
    function compare_user($all_level, $parent_id, $child_id)
    {
        if (!$parent_id) {
            return 0;
        }
        $all_level = explode('.', $all_level);
        $all_level = array_filter($all_level);
        $all_level = array_reverse($all_level);

        foreach ($all_level AS $key => $user_id) {

            if ($user_id != $parent_id) {
                unset($all_level[$key]);
            } else {
                break;
            }
        }
        return count($all_level);
    }
}
if (!function_exists('pre')) {
    function pre( $array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}
if (!function_exists('renderPagination')) {
    function renderPagination($pagination) {
        $total_pages = max(1, intval($pagination->total_pages));
        $current_page = max(1, intval($pagination->current_page));
    
        $html = '<div class="pagination">';
        $html .= '<nav aria-label="Page navigation example">';
        $html .= '<ul class="pagination gap-1 m-0">';
    
        // Previous button
        if ($current_page > 1) {
            $prev_page = $current_page - 1;
            $html .= '<li class="page-item">
                        <a class="page-link" href="?page=' . $prev_page . '" aria-label="Previous">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.33332 12L8.27332 11.06L5.21998 8L8.27331 4.94L7.33331 4L3.33332 8L7.33332 12Z" fill="#1C274C" />
                            </svg>
                        </a>
                    </li>';
        }
    
        // Always show first page
        $html .= '<li class="page-item"><a class="page-link' . ($current_page == 1 ? ' active' : '') . '" href="?page=1">1</a></li>';
    
        // Add ellipsis if needed
        if ($current_page > 3) {
            $html .= '<li class="page-item"><span class="page-link">...</span></li>';
        }
    
        // Pages around the current page
        $start = max(2, $current_page - 1);
        $end = min($current_page + 1, $total_pages - 1);
    
        for ($page = $start; $page <= $end; $page++) {
            $active = ($page == $current_page) ? ' active' : '';
            $html .= '<li class="page-item"><a class="page-link' . $active . '" href="?page=' . $page . '">' . $page . '</a></li>';
        }
    
        // Add ellipsis if needed before the last page
        if ($current_page < $total_pages - 2) {
            $html .= '<li class="page-item"><span class="page-link">...</span></li>';
        }
    
        // Always show last page
        if ($total_pages > 1) {
            $html .= '<li class="page-item"><a class="page-link' . ($current_page == $total_pages ? ' active' : '') . '" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }
    
        // Next button
        if ($current_page < $total_pages) {
            $next_page = $current_page + 1;
            $html .= '<li class="page-item">
                        <a class="page-link" href="?page=' . $next_page . '" aria-label="Next">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.94 4L6 4.94L9.05333 8L6 11.06L6.94 12L10.94 8L6.94 4Z" fill="#1C274C"></path>
                            </svg>
                        </a>
                    </li>';
        }
    
        $html .= '</ul>';
        $html .= '</nav>';
        $html .= '</div>';
    
        return $html;
    }    
}
if (!function_exists('check_array')) {
    function check_array($p_array){
        if(is_array($p_array) and sizeof($p_array)>0){
            return true;
        }else{
            return false;
        }
    }
}
if (!function_exists('checkLoginFe')) {
    function checkLoginFe(){
        if(isset($_SESSION["loggedFe"]) && intval($_SESSION["loggedFe"]) ==1){
            return true;
        }
        return false;
    }
}
if (!function_exists('formatCurrencyVND')) {
    function formatCurrencyVND($number) {
        // Format number with comma as thousand separator and no decimals
        $formattedNumber = number_format($number, 0, ',', '.');
        
        // Append the currency symbol for Vietnamese Dong
        return $formattedNumber . ' đ';
    }
}