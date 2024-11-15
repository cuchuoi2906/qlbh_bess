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