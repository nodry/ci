<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_DB_query_builder $rdb
 * @property CI_Loader $load

 * @property Code_model $codes
 *
 */

class CW_Model extends CI_Model
{
    // 캐시 생성 여부를 클래스 전체에서 1회만 체크하기 위한 static 변수
    protected static $code_cache_initialized = false;

    public function __construct()
    {
        parent::__construct();

        // 필요시 서브 DB 로드 가능 (예: 읽기 전용 DB)
        // $this->rdb = $this->loadDB('reader', 'rdb');

    }

    protected function loadDB($name, $alias=null){
        if ($alias == null) {
            $alias = $name;
        }
        $alias = 'db_'.$alias;

        $CI =& get_instance();

        foreach (get_object_vars($CI) as $CI_object_name => $CI_object)
        {
            if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') && $CI_object_name == $alias)
            {
                return $CI_object;
            }
        }

        $db = $CI->load->database($name, TRUE);
        if (!isset($CI->$alias)) {
            $CI->$alias = $db;
        }
        return $db;
    }


}
