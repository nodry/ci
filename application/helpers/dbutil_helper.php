<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('makeResult')) {
    function makeResult($success, $message, $object=null) {
        return array(
            'success' => $success,
            'message' => $message,
            'object' => $object
        );
    }
}

if (!function_exists('makeResultForQuery')) {
    function makeResultForQuery($success, $message, $objQuery) {
        // select or delete
        return array(
            'success' => $success,
            'message' => $message,
            'query' => $objQuery
        );
    }
}

if (!function_exists('makeResultForInsert')) {
    function makeResultForInsert($success, $message, $insert_id, $objQuery) {
        return array(
            'success' => $success,
            'message' => $message,
            'insert_id' => $insert_id,
            'query' => $objQuery		// 삽입된 row를 다시 select한 결과
        );
    }
}

if (!function_exists('makeResultForUpdate')) {
    function makeResultForUpdate($success, $message, $affected_rows) {
        return array(
            'success' => $success,
            'message' => $message,
            'affected_rows' => $affected_rows
        );
    }
}
