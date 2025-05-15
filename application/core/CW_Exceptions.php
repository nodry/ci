<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CW_Exceptions extends CI_Exceptions
{
    function __construct()
    {
        parent::__construct();
    }

    function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        return parent::show_error($heading, $message, $template, $status_code);
    }

    function show_exception($exception)
    {
        parent::show_exception($exception);
    }

    function show_php_error($severity, $message, $filepath, $line)
    {
        parent::show_php_error($severity, $message, $filepath, $line);
    }
}
