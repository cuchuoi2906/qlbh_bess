<?php
/**
 * ĐẾM & LOG CÂU SQL CỦA MỖI REQUEST — CÔNG CỤ TẠM, GỠ SAU KHI ĐO XONG.
 *
 * Bật:  thêm ?sqllog=<TOKEN> vào URL bất kỳ (set cookie 30 phút, áp dụng cả /app/admin)
 * Tắt:  thêm ?sqllog=off vào URL bất kỳ
 * Log:  storage/logs/sql-YYYY-mm-dd.log
 *
 * Xem đầy đủ từng câu SQL (không gộp): thêm &sqlfull=1
 */

if (!defined('SQL_LOG_TOKEN')) {
    // ĐỔI CHUỖI NÀY trước khi upload lên server
    define('SQL_LOG_TOKEN', 'doi-token-nay-di-9f3a7c');
}

if (!function_exists('sql_logger_boot')) {

    function sql_logger_normalize_query($sql)
    {
        $sql = preg_replace('/\s+/', ' ', trim($sql));
        $sql = preg_replace("/'[^']*'/", "'?'", $sql);
        $sql = preg_replace('/"[^"]*"/', '"?"', $sql);
        $sql = preg_replace('/\b\d+\b/', '?', $sql);

        return $sql;
    }

    function sql_logger_write()
    {
        $queries = array();
        $debugKeys = array();

        try {
            if (function_exists('debug')) {
                $output = debug()->output();
                if (is_array($output)) {
                    $debugKeys = array_keys($output);
                    if (isset($output['queries']) && is_array($output['queries'])) {
                        $queries = $output['queries'];
                    }
                }
            }
        } catch (Throwable $e) {
            $debugKeys = array('LỖI khi gọi debug(): ' . $e->getMessage());
        }

        $dir = (defined('ROOT') ? ROOT : dirname(__DIR__)) . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'cli';
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '-';
        $elapsed = defined('APP_START') ? round((microtime(true) - APP_START) * 1000, 1) : 0;

        $totalTime = 0;
        $groups = array();
        foreach ($queries as $q) {
            $sql = isset($q['query']) ? $q['query'] : '';
            $dur = isset($q['duration']) ? floatval($q['duration']) : 0;
            $line = isset($q['file_line']) ? $q['file_line'] : '';
            $totalTime += $dur;

            $key = sql_logger_normalize_query($sql);
            if (!isset($groups[$key])) {
                $groups[$key] = array('count' => 0, 'time' => 0, 'sql' => $sql, 'line' => $line);
            }
            $groups[$key]['count']++;
            $groups[$key]['time'] += $dur;
        }
        uasort($groups, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $out = str_repeat('=', 100) . PHP_EOL;
        $out .= sprintf(
            '[%s] %s %s%s  |  SỐ CÂU SQL: %d  |  câu khác nhau: %d  |  time SQL: %sms  |  time request: %sms',
            date('Y-m-d H:i:s'), $method, $uri, PHP_EOL,
            count($queries), count($groups), round($totalTime * 1000, 1), $elapsed
        ) . PHP_EOL;

        if (!$queries) {
            $out .= '  !! Không thu được câu SQL nào. Các key debug() trả về: '
                . ($debugKeys ? implode(', ', $debugKeys) : '(rỗng)') . PHP_EOL
                . '  !! Nếu không có key "queries": bật APP_DEBUG=true và APP_ENV=local trong .env của server dev.' . PHP_EOL;
        }

        if (isset($_GET['sqlfull'])) {
            $i = 0;
            foreach ($queries as $q) {
                $i++;
                $out .= sprintf(
                    '  #%-4d %sms  %s%s        ↳ %s%s',
                    $i,
                    round(floatval(isset($q['duration']) ? $q['duration'] : 0) * 1000, 2),
                    isset($q['query']) ? $q['query'] : '',
                    PHP_EOL,
                    isset($q['file_line']) ? $q['file_line'] : '',
                    PHP_EOL
                );
            }
        } else {
            $out .= '  --- gộp theo câu giống nhau (nghi N+1 = cột "lần" lớn) ---' . PHP_EOL;
            foreach (array_slice($groups, 0, 30) as $g) {
                $out .= sprintf(
                    '  %4d lần  %8sms  %s%s            ↳ %s%s',
                    $g['count'],
                    round($g['time'] * 1000, 1),
                    substr(preg_replace('/\s+/', ' ', $g['sql']), 0, 220),
                    PHP_EOL,
                    $g['line'],
                    PHP_EOL
                );
            }
            if (count($groups) > 30) {
                $out .= '  ... còn ' . (count($groups) - 30) . ' câu khác' . PHP_EOL;
            }
        }

        @file_put_contents($dir . '/sql-' . date('Y-m-d') . '.log', $out, FILE_APPEND | LOCK_EX);
    }

    function sql_logger_boot()
    {
        $param = isset($_GET['sqllog']) ? $_GET['sqllog'] : null;

        if ($param === 'off') {
            setcookie('sqllog', '', time() - 3600, '/');
            unset($_COOKIE['sqllog']);

            return;
        }

        if ($param !== null && hash_equals(SQL_LOG_TOKEN, (string)$param)) {
            setcookie('sqllog', SQL_LOG_TOKEN, time() + 1800, '/');
            $_COOKIE['sqllog'] = SQL_LOG_TOKEN;
        }

        if (!isset($_COOKIE['sqllog']) || !hash_equals(SQL_LOG_TOKEN, (string)$_COOKIE['sqllog'])) {
            return;
        }

        register_shutdown_function('sql_logger_write');
    }
}

sql_logger_boot();
