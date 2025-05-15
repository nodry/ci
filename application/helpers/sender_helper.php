<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("./vendor/autoload.php");

if (!function_exists('sendMail'))
{
    function sendMail($mailContents, $subject, $arrTo, $fromEmail='noreply@kookmean.com', $fromName='국민의뜻')
    {

        $arrTo = (is_array($arrTo)) ? $arrTo : array($arrTo);

        foreach ($arrTo as $to) {
            if (!is_array($to)) return false;
            if (empty($to['email']) OR empty($to['name']) OR empty($to['type'])) return false;
        }

        $message = array(
            'html' => $mailContents,
            'subject' => $subject,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'to' => $arrTo,
            'headers' => array(
                'Reply-To' => $fromEmail
            ),
            'important' => true,
        );

        $async = true;
        $ip_pool = null;
        $sent_at = null;
        try {
            $mandrill = new Mandrill('ObAuu9VmBP7pOWc15RuKXg');
            $send_result = $mandrill->messages->send($message, $async, $ip_pool, $sent_at);
            $send_result = "11";
        } catch(Mandrill_Error $e) {
            $send_result = '////mandrill error/////' . get_class($e) . ' - ' . $e->getMessage()."\n";
        }


        return $send_result;
    }
}

if (!function_exists('sensSMS'))
{
    function sendSMS($smsContents, $subject, $arrTo, $fromPhone='16885794')
    {
        //이전 sms key 202502191600 
        // $apiKey = 'NCSGGDC81DPZR0AL';
        // $apiSecret = 'RSPIMHO2TJWZDVURMQCK21ODVC5YYMEK';

        $apiKey = 'NCSGTCU8PTMTA5EW';
        $apiSecret = 'FOM2J4QCLDOWI6Z7R5ZPCVLMBG4ZAEQB';

        $date = date('Y-m-d\TH:i:s.Z\Z', time());
        $salt = uniqid();
        $signature = hash_hmac('sha256', $date.$salt, $apiSecret);

        $url = 'https://api.solapi.com/messages/v4/send';

        $message = new stdClass();
        $message->text = $smsContents;
        $message->type = 'SMS';
        $message->to = $arrTo;
        $message->from = $fromPhone;

        $fields = new stdClass();
        $fields->message = $message;
        $fields_string = json_encode($fields);

        $header = "Authorization: HMAC-SHA256 apiKey={$apiKey}, date={$date}, salt={$salt}, signature={$signature}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header, "Content-Type: application/json"));
        //curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        //echo $result;
        return $result;
    }
}

if (!function_exists('sensPush'))
{
    function sendPush($contents, $subject, $to_fcm_token, $etc=null)
    {
        $FCM_SEND_URL = 'https://fcm.googleapis.com/fcm/send';
        $FCM_SERVER_KEY = 'AAAA2Ih4tEs:APA91bFHdRPoWgWACRUkvHa4WiASyHSGaKKSVlHp7YZQJ6ZR4jZdflHY2bvJ4FUJKVsxi4E9Q3iW3whnN1MvJQSRr-hpToVB6S62TvQaiwUAWglNaT-0tA0ecKUnWrlsFyxxl4q-BBDU';
        $header = array();
        $header[] = "Authorization: key=" . $FCM_SERVER_KEY;
        $header[] = "Content-Type: application/json";

        $body = array();
        $body["to"] = $to_fcm_token;
        $body["data"] = array("title" => $subject, "body" => $contents, "sound" => "default", "badge" => "1", "etc" => $etc);
        $body["notification"] = array("title" => $subject, "body" => $contents, "sound" => "default", "badge" => "1");
        $body["content_available"] = true;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $FCM_SEND_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

