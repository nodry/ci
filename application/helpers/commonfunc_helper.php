<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('sendError')) {
    function sendError($msg, $redirect=null) {
        if ($redirect === null)
            exit("<script type='text/javascript'>alert('{$msg}');history.go(-1);</script>");
        else
            exit("<script type='text/javascript'>alert('{$msg}');document.location='{$redirect}';</script>");
    }
}
if (!function_exists('sendJsonError')) {
    function sendJsonError($msg) {
        $ret = array();
        $ret['result'] = false;
        $ret['message'] = $msg;
        $ret['data'] = null;

        $CI =& get_instance();
        $CI->output->set_content_type('application/json');
        $CI->output->set_output(json_encode($ret, JSON_UNESCAPED_UNICODE))->_display();
        exit();
    }
}

if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}
if (!function_exists('endsWith')) {
    function endsWith($haystack, $needle) {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
}

if (!function_exists('objectToArray')) {
    function objectToArray($obj) {
        if (is_object($obj)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $obj = get_object_vars($obj);
        }

        if (is_array($obj)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $obj);
            //return array_map(array($this, 'objectToArray'), $obj);
            //$this->d = get_object_vars($d);
        }
        else {
            // Return array
            return $obj;
        }
    }
}

if (!function_exists('static_filepath')) {
    function static_filepath($filepath) {
        if(file_exists('.'.$filepath)) return $filepath.'?v='.filemtime('.'.$filepath);
        return $filepath;
    }
}

if(!function_exists('make_token')) {
    // token
    function make_token($length=30)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
            //$token .= $codeAlphabet[mt_rand(0, $max-1)];
        }

        return $token;
    }
}
if(!function_exists('crypto_rand_secure')){
    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
}
if(!function_exists('get_dev_server_name')){
    function get_dev_server_name() {
        //return null > 상용서버
        //return 'xxx' > 개발서버
        if (isset($_SERVER['SERVER_NAME'])) {
            $server_name = $_SERVER['SERVER_NAME'];
            $dev_servers = array('dev.kookmean.com', 'test.kookmean.com', 'local.kookmean.com');
            if (in_array($server_name, $dev_servers)) {
                return $server_name;//개발서버
            }
            foreach ($dev_servers as $dev_server)
                if (endsWith($server_name, '.' . $dev_server)) return $dev_server;//개발서버
            return null;
        }else{
            $ip = gethostbyname(gethostname());
            $liveServerIP = array('172.31.22.77');
            $devServerIP = array('172.31.25.115');
            if (in_array($ip, $liveServerIP)) {
                return null;//상용서버
            }else if (in_array($ip, $devServerIP)) {
                return 'dev.kookmean.com';//개발서버
            }
            return $ip;//그외 개발서버
        }
        return null;//상용서버
    }
}

if(!function_exists('get_http_host_url')){
    function get_http_host_url() {
        $http_host = "http";
        if (get_dev_server_name() == null) {
            $http_host = "https";
        }
        $http_host .= "://".$_SERVER['HTTP_HOST'];
        return $http_host;
    }
}

if (!function_exists('get_weekday')) {
    function get_weekday($yyyymmdd = null)
    {
        date_default_timezone_set('Asia/Seoul');
        if ($yyyymmdd != null && strlen($yyyymmdd) > 0) {
            $yyyymmdd = str_replace('-', '', $yyyymmdd);
        }
        $daily = array('일', '월', '화', '수', '목', '금', '토');
        if (strlen($yyyymmdd) == 8 && is_numeric($yyyymmdd)) {
            $date = date('w', strtotime($yyyymmdd));
        } else if ($yyyymmdd == null) {
            $date = date('w');
        }
        return $daily[$date];
    }
}

if (!function_exists('get_yyyymmdd')) {
    function get_yyyymmdd($yyyymmdd = null, $diff_days = 0, $has_dash=false)
    {
        date_default_timezone_set('Asia/Seoul');
        if ($diff_days == null) {
            $diff_days = 0;
        }

        $date_format = "Ymd";
        if ($has_dash) {
            $date_format = "Y-m-d";
        }

        if ($yyyymmdd != null && strlen($yyyymmdd) > 0) {
            $yyyymmdd = str_replace('-', '', $yyyymmdd);
        }
        if (strlen($yyyymmdd) == 8 && is_numeric($yyyymmdd)) {
            $yyyymmdd = date($date_format, strtotime($diff_days . " days", strtotime($yyyymmdd)));
        } else if ($yyyymmdd == null) {
            $yyyymmdd = date($date_format, strtotime($diff_days . " days"));
        }
        return $yyyymmdd;
    }
}

if (!function_exists('get_month_yyyymm')) {
    function get_month_yyyymm($yyyymm = null, $diff_month = 0, $has_dash=false)
    {
        date_default_timezone_set('Asia/Seoul');
        if ($diff_month == null) {
            $diff_month = 0;
        }

        $date_format = "Ym";
        if ($has_dash) {
            $date_format = "Y-m";
        }

        if ($yyyymm != null && strlen($yyyymm) > 0) {
            $yyyymm = str_replace('-', '', $yyyymm);
        }
        if (strlen($yyyymm) > 6) {
            $yyyymm = substr($yyyymm, 0, 6);
        }
        if (strlen($yyyymm) == 6 && is_numeric($yyyymm)) {
            $yyyymm = date($date_format, strtotime($diff_month . " months", strtotime($yyyymm . '01')));
        } else if ($yyyymm == null) {
            $yyyymm = date($date_format, strtotime($diff_month . " months"));
        }
        return $yyyymm;
    }
}

if (!function_exists('get_year_yyyy')) {
    function get_year_yyyy($yyyy = null, $diff_year = 0)
    {
        date_default_timezone_set('Asia/Seoul');
        if ($diff_year == null) {
            $diff_year = 0;
        }

        $date_format = "Y";

        if (strlen($yyyy) > 4) {
            $yyyy = substr($yyyy, 0, 4);
        }
        if (strlen($yyyy) == 4 && is_numeric($yyyy)) {
            $yyyy = date($date_format, strtotime($diff_year . " years", strtotime($yyyy . '0101')));
        } else if ($yyyy == null) {
            $yyyy = date($date_format, strtotime($diff_year . " years"));
        }
        return $yyyy;
    }
}

if (!function_exists('get_now')) {
    function get_now($diff_second=null, $date_format='Y-m-d H:i:s')
    {
        date_default_timezone_set('Asia/Seoul');
        if ($diff_second == null) {
            $diff_second = 0;
        }
        $now = date($date_format, strtotime($diff_second . " seconds"));
        return $now;
    }
}

if (!function_exists('get_date_diff')) {
    function get_date_diff($sdate, $edate)
    {
        date_default_timezone_set('Asia/Seoul');
        if ($sdate == null OR validate_date($sdate) == false OR $edate == null OR validate_date($edate) == false) {
            return null;
        }

        $sdatetime = new DateTime(get_yyyymmdd(str_replace('-', '', $sdate),0,true));
        $edatetime = new DateTime(get_yyyymmdd(str_replace('-', '', $edate),0,true));
        $diff = date_diff($sdatetime, $edatetime);

        return $diff;
    }
}

if (!function_exists('get_days_date_diff')) {
    function get_days_date_diff($sdate, $edate)
    {
        return get_date_diff($sdate, $edate)->days;
    }
}

if (!function_exists('validate_date')) {
    /**
     * 날짜 체크
     * @param $date
     * @param bool $is_include_dash
     * @return bool
     */
    function validate_date($date){
        $date = str_replace('-','', $date);
        if (is_numeric($date) == false) {
            return false;
        }

        if (strlen($date) != 8) {
            return false;
        }
        $year = intval(substr($date, 0, 4));
        $month = intval(substr($date, 4, 2));
        $day = intval(substr($date, 6, 2));
        return checkdate($month, $day, $year);
    }
}

if (!function_exists('get_name_from_number')) {
    function get_name_from_number($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return get_name_from_number($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }
}
if (!function_exists('output_excel')) {
    function output_excel($excel_array, $filename, $sheet_title_array=null){
        if (count($excel_array) > 0) {
            if ($sheet_title_array == null) {
                $sheet_title_array = array();
            }
            if (count($sheet_title_array) < count($excel_array)) {
                for ($i = count($sheet_title_array); $i < count($excel_array); $i++) {
                    $sheet_title_array[] = 'Worksheet ' . ($i + 1);
                }
            }

            // 엑셀로 내보내기
            require_once("./vendor/autoload.php");

            $CI =& get_instance();
            $CI->load->library('PHPExcel');

            $excel = new PHPExcel();
            $excelFileName = iconv('UTF-8', 'EUC-KR', $filename);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $excelFileName . '.xls"');
            header('Cache-Control: max-age=0');


            if (!empty($excel_array)) {
                foreach ($excel_array as $row => $item) {
                    $sheet_title = @$sheet_title_array[$row];
                    if (!empty($item)) {
                        $sheet = $excel->createSheet($row); //Setting index when creating
                        $sheet->fromArray($item);

                        //$latestBLColumn = get_name_from_number(count($item[0]));
                        //$range = 'A0:'.$latestBLColumn.count($item);
                        //$sheet->getStyle($range)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
                        $sheet->setTitle($sheet_title);
                    }
                }
            }
            $excel->setActiveSheetIndex(0);
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            $writer->save('php://output');
            return true;
        }
        return false;
    }
}

if (!function_exists('display_xml_error')) {
    function display_xml_error($error, $xml)
    {
        $return  = $xml[$error->line - 1] . "\n";
        $return .= str_repeat('-', $error->column) . "^\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message) .
            "\n  Line: $error->line" .
            "\n  Column: $error->column";
        if ($error->file) {
            $return .= "\n  File: $error->file";
        }
        return "$return\n\n--------------------------------------------\n\n";
    }
}

if (!function_exists('str_replace_with')) {
    /**
     * UTIL - str replace with (배열, prefix, subfix)
     * @param $subject
     * @param $replace
     * @param null $all_search
     * @param null $prefix_search
     * @param null $subfix_search
     * @return mixed|string
     */
    function str_replace_with($subject, $replace, $all_search=null, $prefix_search=null, $subfix_search=null){
        if ($subject === null OR strlen($subject) == 0) {
            return $subject;
        }
        if ($replace === null) {
            return $subject;
        }

        if ($all_search != null) {
            if (is_string($all_search) && strlen($all_search) > 0) {
                $all_search = array($all_search);
            }
            if (is_array($all_search) && count($all_search) > 0) {
                foreach ($all_search as $search) {
                    $subject = str_replace($search, $replace, $subject);
                }
            }
        }
        if ($prefix_search != null) {
            if (is_string($prefix_search) && strlen($prefix_search) > 0) {
                $prefix_search = array($prefix_search);
            }
            if (is_array($prefix_search) && count($prefix_search) > 0) {
                foreach ($prefix_search as $s_search) {
                    if (substr($subject, 0, strlen($s_search)) == $s_search) {
                        $subject = $replace.substr($subject, strlen($s_search));
                    }
                }
            }
        }
        if ($subfix_search != null) {
            if (is_string($subfix_search) && strlen($subfix_search) > 0) {
                $subfix_search = array($subfix_search);
            }
            if (is_array($subfix_search) && count($subfix_search) > 0) {
                foreach ($subfix_search as $e_search) {
                    if (substr($subject, strlen($subject) - strlen($e_search), strlen($e_search)) == $e_search) {
                        $subject = substr($subject, 0, strlen($subject) - strlen($e_search)) . $replace;
                    }
                }
            }
        }
        return $subject;
    }
}

if (!function_exists('get_viewpath')) {
    function get_viewpath($filepath){
        return VIEWPATH . $filepath . '.php';
    }
}

// 쿠키 굽기 3년 홈페이지와 동일
if (!function_exists('get_cookie_expire')) {
    function get_cookie_expire() {
        return time()+60*60*24*365*3;
    }
}

if (!function_exists('array_reduce_name')) {
    /**
     * 배열 reduce with name
     * @param $data
     * @param $att_key_name
     * @param null $att_value_name
     * @param bool $is_unique
     * @return array|mixed|null
     */
    function array_reduce_name($data, $att_key_name, $att_value_name=null, $is_unique=false){
        $res = null;
        if ($data != null && is_array($data) && count($data) > 0) {
            $res = array_reduce(
                $data,
                function ($carry, $item) use ($data, $att_key_name, $att_value_name) {
                    $value = $item[$att_key_name];
                    if ($value != null && strlen($value) > 0) {
                        $att_value = null;
                        if ($att_value_name != null) {
                            $att_value = $item[$att_value_name];
                        }
                        if ($att_value == null) {
                            $carry[] = $value;
                        }else{
                            $carry[$value][] = $att_value;
                        }
                    }
                    return $carry;
                },
                array());
        }

        if ($is_unique) {
            if ($res != null && is_array($res) && count($res) > 0) {
                $res = array_unique($res);
            }
        }
        return $res;
    }
}

if (!function_exists('array_reduce_add_key_value')) {
    /**
     * 배열 reduce add key value
     * @param $data
     * @param $att_key
     * @param $att_value
     * @return mixed|null
     */
    function array_reduce_add_key_value($data, $att_key, $att_value){
        $res = null;
        if ($data != null && is_array($data) && count($data) > 0) {
            $res = array_reduce(
                $data,
                function ($carry, $item) use ($data, $att_key, $att_value) {
                    $item[$att_key] = $att_value;
                    $carry[] = $item;
                    return $carry;
                },
                array());
        }

        return $res;
    }
}

if (!function_exists('data_download')) {
    /**
     * @param string $filename
     * @param string $data
     * @param bool $set_mime
     */
    function data_download($filename = '', $data = '', $set_mime = FALSE){
        $CI =& get_instance();
        $CI->load->helper('download');
        if (!empty($filename)) {
            if(preg_match('#(?:Chrome|Edge)/(\d+)\.#', $_SERVER['HTTP_USER_AGENT'], $matches) && $matches[1] >= 11) {
                //if(strstr($_SERVER['HTTP_USER_AGENT'], "Android") && preg_match('#\bwv\b|(?:Version|Browser)/\d+#', $_SERVER['HTTP_USER_AGENT'])) {
                //}else {
                //    //$filename = rawurlencode($filename);
                //}
            } else if(preg_match('#(?:Firefox|Safari|Trident)/(\d+)\.#', $_SERVER['HTTP_USER_AGENT'], $matches) && $matches[1] >= 6) {
                $filename = rawurlencode($filename);
            } else if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
                $filename = rawurlencode($filename);
                $filename = preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1);
            }

            $encode = array('ASCII', 'UTF-8', 'EUC-KR');
            $str_encode = mb_detect_encoding($filename, $encode);
            if (strtoupper($str_encode) == 'EUC-KR') {
                $filename = mb_convert_encoding(urldecode($filename), 'utf-8', 'euc-kr');
            }
            $filename = filename_normalize($filename);
        }
        force_download($filename, $data, $set_mime);
    }
}

if (!function_exists('file_download')) {
    /**
     * @param string $filename
     * @param string $fileurl
     * @param bool $set_mime
     */
    function file_download($filename = '', $fileurl = '', $set_mime = FALSE){
        data_download($filename, file_get_contents($fileurl), $set_mime);
    }
}

if (!function_exists('array_value_recursive')) {
    function array_value_recursive($key, array $arr)
    {
        $val = array();
        array_walk_recursive($arr, function ($v, $k) use ($key, &$val) {
            if ($k == $key) array_push($val, $v);
        });
        return count($val) > 1 ? $val : array_pop($val);
    }
}

if (!function_exists('filename_normalize')) {
    function filename_normalize($str)
    {
        if (Normalizer::isNormalized($str, Normalizer::FORM_C) === false) {
            $str = Normalizer::normalize($str, Normalizer::FORM_C);
        }
        return $str;
    }
}

if (!function_exists('url_download')) {
    function url_download($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $retValue = curl_exec($ch);
        curl_close($ch);
        return $retValue;
    }

    //디버깅
    if (!function_exists('debug_var')) {
        function debug_var($param)
        {
            // echo "start : ".date("Y-m-d H:i:s",time());
            ob_start();
            var_export($param) ;
            $dump = ob_get_contents();
            ob_end_clean();
        
            highlight_string("<?php \n" . $dump . "\n?>");
            // echo "end : ".date("Y-m-d H:i:s",time())."<br>";
            exit;
        }
    }

    //validation check
    if (!function_exists('validation_check')) {
        function validation_check($param)
        {
            foreach($param as $k=>$v) {
                if(empty($v)) return $k;
            }
            return true;
        }
    }

    //validation message
    if (!function_exists('validation_msg')) {
        function validation_msg($code,$error)
        {
            return "[서버오류 발생]\n 문제가 발생할 경우 관리자 에게 문의하세요 [{$code}:{$error}]";
        }
    }
}
