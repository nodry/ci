<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge $dbforge
 * @property CI_Benchmark $benchmark
 * @property CI_Calendar $calendar
 * @property CI_Cart $cart
 * @property CI_Config $config
 * @property CI_Controller $controller
 * @property CI_Email $email
 * @property CI_Encrypt $encrypt
 * @property CI_Exceptions $exceptions
 * @property CI_Form_validation $form_validation
 * @property CI_Ftp $ftp
 * @property CI_Hooks $hooks
 * @property CI_Image_lib $image_lib
 * @property CI_Input $input
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Log $log
 * @property CI_Model $model
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Parser $parser
 * @property CI_Profiler $profiler
 * @property CI_Router $router
 * @property CI_Session $session
 * @property CI_Table $table
 * @property CI_Trackback $trackback
 * @property CI_Typography $typography
 * @property CI_Unit_test $unit_test
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $user_agent
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Xmlrpcs $xmlrpcs
 * @property CI_Zip $zip
 *
 * @property Code_model $codes
 *
 */
class CW_Controller extends CI_Controller
{
    private $allowUrls = array();
    private $attribute = array();
    public $is_debug = false;

    public function __construct()
    {
        parent::__construct();

        //기본설정 로드
    }

    protected function addAllowUrls($arrays)
    {
        array_push($this->allowUrls, $arrays);
    }

    protected function setAttribute($key, $value)
    {
        $this->attribute[$key] = $value;
    }

    protected function addAttribute($arrays)
    {
        array_push($this->attribute, $arrays);
    }

    protected function setView($view)
    {
        $this->load->view($view, $this->attribute);
    }

    protected function setJsonResponse($result, $message, $data=null)
    {
        $ret = array(
            'result' => $result,
            'message' => $message,
            'data' => $data,
        );
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($ret, JSON_UNESCAPED_UNICODE))->_display();
        exit();
    }

    protected function tokenCheck() {
        $header = apache_request_headers();
        if (!isset($header['Authorization'])) {
            $this->output->set_status_header('401');
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'result' => false,
                'message' => '권한이 없습니다.1'
            ), JSON_UNESCAPED_UNICODE))->_display();
            exit();
        } else {
            $authCode = str_replace('Bearer ', '', $header['Authorization']);
            $hashed = hash('sha512', $authCode);
			echo "$hashed ::: ". $hashed ."\n";
            if ($hashed != '0a4cab6f323c35e1f00ecc00685aabb3cedec3326f712a63b8f47bb82e211251c34f9b5d58570666f9c85f95b40aca6b5c2696a26ea345a0f08998274faad234') {
                $this->output->set_status_header('401');
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode(array(
                    'result' => false,
                    'message' => '권한이 없습니다.2',
                    'authcode' => $hashed
                ), JSON_UNESCAPED_UNICODE))->_display();
                exit();
            }
        }
    }

    protected function tokenCheckForIntra() {
        $header = apache_request_headers();
        if (!isset($header['Authorization'])) {
            $this->output->set_status_header('401');
            $this->output->set_header("Access-Control-Allow-Origin: *");
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'result' => false,
                'message' => '권한이 없습니다.'
            ), JSON_UNESCAPED_UNICODE))->_display();
            exit();
        } else {
            $authCode = str_replace('Bearer ', '', $header['Authorization']);
            $hashed = hash('sha512', $authCode);
            if ($hashed != '0a4cab6f323c35e1f00ecc00685aabb3cedec3326f712a63b8f47bb82e211251c34f9b5d58570666f9c85f95b40aca6b5c2696a26ea345a0f08998274faad234') {
                $this->output->set_status_header('401');
                $this->output->set_header("Access-Control-Allow-Origin: *");
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode(array(
                    'result' => false,
                    'message' => '권한이 없습니다.',
                    'authcode' => $hashed
                ), JSON_UNESCAPED_UNICODE))->_display();
                exit();
            }
        }
    }
}
