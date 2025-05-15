<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("./vendor/autoload.php");

if (!function_exists('checkURL')) {
    function checkURL($url) {
        $apiKey = '36fcdbed8dae423ea0e96348ee5c273da1f1a74f271c4a7d7e0ea00571e9244d';

        $urlToScan = new VirusTotal\Url($apiKey);
        $res = $urlToScan->getReport($url);

        //var_dump($res['scans']);
        if (isset($res['scans'])) {
            foreach ($res['scans'] as $key=>$val) {
                //echo($key);
                //var_dump($val['detected']);
                if ($val['detected']) return false;
            }
            return true;
        }
        return false;
    }
}
