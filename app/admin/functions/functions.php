<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * Created by PhpStorm.
 * User: justin
 * Date: 16/02/17
 * Time: 10:52
 */

if( ! function_exists('build_sort_link') ) {
    /**
     * Build sort link for sort
     * @param $link
     * @param $sortKey
     */
    function build_sort_link($link, $sortKey) {
        // Parse url
        $parseUrl = parse_url($link);
        if(!isset($parseUrl['query'])) {
            $queryParams = [];
        } else {
            parse_str($parseUrl['query'], $queryParams);
        }

        // Attach action
        $queryParams['_action'] = 'sort';
        $queryParams['sort_key'] = $sortKey;

        // Domain url
        if(80 !== (int) $parseUrl['port']) {
            $url = $parseUrl['scheme'] . '://' . $parseUrl['host'] . ':' . $parseUrl['port'] . $parseUrl['path'];
        } else {
            $url = $parseUrl['scheme'] . '://' . $parseUrl['host'] . $parseUrl['path'];
        }

        // Default sort
        if(!isset($queryParams['sort_value'])) {
            $queryParams['sort_value'] = "DESC";
        }

        // To lower
        foreach($queryParams as $key => $value) {
            $queryParams[$key] = strtolower($value);
        }

        // Switch sort value
        if ($queryParams['sort_value'] == "asc") {
            $queryParams['sort_value'] = "desc";
        } else {
            $queryParams['sort_value'] = "asc";
        }


        return $url . '?' . http_build_query($queryParams);
    }
}


if( ! function_exists('get_client_ip') ) {
    function get_client_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
if( ! function_exists('export_custom') ) {
    function export_custom($datas = [],$nameFile = 'export')
    {
		ini_set("memory_limit","500M");
        disable_debug_bar();
        if(!check_array($datas)){
            return;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 1;
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J','K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF'];
        foreach ($datas as $data) {
            $j = 0;
            foreach ($data as $value) {
                $sheet->setCellValue($columns[$j] . $i, (string)html_entity_decode(strip_tags($value)));
                
                $j++;
            }
            $i++;
        }
        //pre($sheet);die;

        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="'.$nameFile.'.xlsx"');
        if (ob_get_contents()) {
            ob_end_clean();
        }
        $writer->save('php://output');
        

        die();

    }
}
if( ! function_exists('fw24h_replace_bad_char') ) {
	function fw24h_replace_bad_char($p_string)
	{
		$p_string = trim($p_string);
		$p_string = stripslashes($p_string);
		$p_string = str_replace('<', '&lt;', $p_string);
		$p_string = str_replace('>', '&gt;', $p_string);
		$p_string = str_replace('"', '&#34;', $p_string);
		$p_string = str_replace("'", '&#39;', $p_string);
		$p_string = str_replace('\\', '&#92;', $p_string);
		$p_string = str_replace('=', '&#61;', $p_string);
		$p_string = str_replace('(', '&#40;', $p_string);
		$p_string = str_replace(')', '&#41;', $p_string);
		$p_string = str_replace("|", '&#124;', $p_string);
		return $p_string;
	}
}

if ( ! function_exists('numberToVietnamese') ) {
    /**
     * Chuyển số tiền thành chữ tiếng Việt — đọc tự nhiên như người Việt.
     *
     * Ví dụ:
     *   0           => "Không đồng"
     *   1000        => "Một nghìn đồng chẵn"
     *   1005        => "Một nghìn không trăm lẻ năm đồng"
     *   7000000     => "Bảy triệu đồng chẵn"
     *   1500000     => "Một triệu năm trăm nghìn đồng chẵn"
     *   2000000015  => "Hai tỷ lẻ mười lăm đồng"
     *
     * @param float|int $number
     * @return string
     */
    function numberToVietnamese($number) {
        if ($number == 0) return 'Không đồng';

        $number = round((float)$number);
        $isNegative = $number < 0;
        $number = abs($number);

        $ones  = ['', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
        // Tên đơn vị theo vị trí nhóm 3 chữ số (index 0 = hàng đơn vị)
        $units = ['', 'nghìn', 'triệu', 'tỷ'];

        /**
         * Đọc nhóm 3 chữ số.
         * @param int  $num              Giá trị 0-999
         * @param bool $showZeroHundred  true => đọc "không trăm" (khi nhóm cao hơn > 0)
         * @return string
         */
        $readThreeDigits = function ($num, $showZeroHundred = false) use ($ones) {
            if ($num == 0) return '';

            $hundred = intval($num / 100);
            $ten     = intval(($num % 100) / 10);
            $unit    = $num % 10;
            $parts   = [];

            // --- Hàng trăm ---
            if ($hundred > 0) {
                $parts[] = $ones[$hundred] . ' trăm';
            } elseif ($showZeroHundred) {
                // 1,005 => "một nghìn *không trăm* lẻ năm"
                $parts[] = 'không trăm';
            }

            // --- Hàng chục + đơn vị ---
            if ($ten > 1) {
                // 20-99 (trừ x0, x1, x5 đặc biệt)
                $s = $ones[$ten] . ' mươi';
                if ($unit == 1) {
                    $s .= ' mốt';       // 21 => "hai mươi mốt"
                } elseif ($unit == 5) {
                    $s .= ' lăm';        // 25 => "hai mươi lăm"
                } elseif ($unit > 0) {
                    $s .= ' ' . $ones[$unit];
                }
                $parts[] = $s;
            } elseif ($ten == 1) {
                // 10-19
                $s = 'mười';
                if ($unit == 5) {
                    $s .= ' lăm';        // 15 => "mười lăm"
                } elseif ($unit > 0) {
                    $s .= ' ' . $ones[$unit];
                }
                $parts[] = $s;
            } elseif ($unit > 0) {
                // Hàng chục = 0, hàng đơn vị > 0 => cần "lẻ" nếu có trăm phía trước
                if ($hundred > 0 || $showZeroHundred) {
                    $parts[] = 'lẻ ' . $ones[$unit];  // 105 => "một trăm lẻ năm"
                } else {
                    $parts[] = $ones[$unit];
                }
            }

            return implode(' ', $parts);
        };

        // --- Tách số thành các nhóm 3 chữ số (từ thấp đến cao) ---
        $groups = [];
        $tmp = $number;
        while ($tmp > 0) {
            $groups[] = $tmp % 1000;
            $tmp = intval($tmp / 1000);
        }
        $groupCount = count($groups);

        // --- Xử lý hàng tỷ trở lên ---
        // Cấu trúc VND: [... tỷ] [triệu] [nghìn] [đơn vị]
        // Nếu > 999 tỷ thì phần trước "tỷ" lại đọc đệ quy bình thường
        // groups[3] = hàng tỷ, groups[4] = hàng nghìn tỷ, groups[5] = hàng triệu tỷ...

        /**
         * Lấy tên đơn vị cho nhóm thứ $i.
         * i=0: (đơn vị), i=1: nghìn, i=2: triệu, i=3: tỷ
         * i=4: nghìn tỷ, i=5: triệu tỷ, i=6: tỷ tỷ (hiếm)
         */
        $getUnit = function ($i) use ($units) {
            if ($i < 4) return $units[$i];
            // Trên tỷ: lặp lại chu kỳ nghìn/triệu/tỷ
            $sub = $i - 3; // 1 => nghìn, 2 => triệu, 3 => tỷ
            $base = isset($units[$sub]) ? $units[$sub] : '';
            return ($base ? $base . ' ' : '') . 'tỷ';
        };

        // --- Ghép các nhóm thành chuỗi ---
        $resultParts = [];
        for ($i = $groupCount - 1; $i >= 0; $i--) {
            $val = $groups[$i];
            if ($val == 0) {
                // Nhóm = 000: bỏ qua, NHƯNG nếu nhóm tiếp theo (thấp hơn) có giá trị
                // và < 100 thì nhóm đó cần showZeroHundred => xử lý ở dưới
                continue;
            }

            // Cần "không trăm" khi: có nhóm cao hơn đã đọc VÀ nhóm hiện tại < 100
            // Ví dụ: 1,005 => nhóm nghìn=1 (đã đọc), nhóm đơn vị=5 => "không trăm lẻ năm"
            // Ví dụ: 2,000,000,015 => nhóm tỷ=2 (đã đọc), các nhóm triệu+nghìn=0 (skip),
            //         nhóm đơn vị=15 => cần "lẻ" chứ không phải "không trăm"
            $needZeroHundred = false;
            if ($val < 100 && count($resultParts) > 0) {
                // Kiểm tra nhóm ngay phía trên có giá trị không (để quyết định "không trăm" hay "lẻ")
                // Nếu nhóm liền trên cũng = 0 (bị skip), chỉ cần "lẻ" thay vì "không trăm"
                $hasDirectUpperGroup = ($i + 1 < $groupCount && $groups[$i + 1] > 0);
                if ($hasDirectUpperGroup) {
                    $needZeroHundred = true;
                } else {
                    // Nhóm liền trên = 0, nhưng có nhóm cao hơn nữa đã đọc
                    // => chỉ dùng "lẻ" (không đọc "không trăm")
                    // Trick: thêm "lẻ" trước nếu val < 10 và chưa có trăm
                    if ($val < 10) {
                        $text = 'lẻ ' . $ones[$val];
                        $unitName = $getUnit($i);
                        if ($unitName) $text .= ' ' . $unitName;
                        $resultParts[] = $text;
                        continue;
                    }
                    // val 10-99: readThreeDigits xử lý bình thường (không cần "không trăm")
                }
            }

            $text = $readThreeDigits($val, $needZeroHundred);
            if ($text !== '') {
                $unitName = $getUnit($i);
                if ($unitName) $text .= ' ' . $unitName;
                $resultParts[] = $text;
            }
        }

        // --- Ghép kết quả + hậu tố ---
        $result = implode(' ', $resultParts);

        // Số tròn (chia hết cho 1000, 1000000...) => thêm "đồng chẵn"
        // Đơn giản: nếu nhóm đơn vị (groups[0]) = 0 => số tròn ít nhất hàng nghìn
        $isRound = ($groups[0] == 0);
        $result .= $isRound ? ' đồng chẵn' : ' đồng';

        // Viết hoa chữ cái đầu
        $result = mb_strtoupper(mb_substr($result, 0, 1, 'UTF-8'), 'UTF-8')
                . mb_substr($result, 1, null, 'UTF-8');

        if ($isNegative) {
            $result = 'Âm ' . $result;
        }

        return $result;
    }
}