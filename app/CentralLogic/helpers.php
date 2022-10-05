<?php

namespace App\CentralLogics;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;

class Helpers
{
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function get_business_settings($name)
    {
        $config = null;
        $paymentmethod = BusinessSetting::where('key', $name)->first();
        if ($paymentmethod) {
            $config = json_decode(json_encode($paymentmethod->value), true);
            $config = json_decode($config, true);
        }
        return $config;
    }

    public static function send_order_notification($order, $token)
    {
        try {
            $status = $order->order_status;
            $value = self::order_status_update_message($status);

       //     if ($value) {
                $data = [
                    'title' => trans('messages.order_push_title'),
                    'description' => 'Google send tou notif',// $value,
                    'order_id' => '12121',$order->id,
                    'image' => '',
                    'type' => 'order_status',
                ];
                self::send_push_notif_to_device($token, $data);
                try {
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception$e) {
                    return response()->json([$e], 403);
                }

       //     }
            return true;

        } catch (\Exception$e) {
            info($e);
        }
        return false;
    }

    public static function send_push_notif_to_device($fcm_token, $data, $delivery = 0)
    {
        $key = 0;
        if ($delivery == 1) {
            $key = BusinessSetting::where(['key' => 'delivery_boy_push_notification_key'])->first()->value;
        } else {
            $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        }

        $url = "https://fcm.googleapis.com/fcm/send";
        // $header = array("Authorization: key=" . $key['content'] . "",
        //     "content-type:application/json");

            $SERVER_API_KEY =   'AAAA7oSRoSI:APA91bEMUHxy1a7xf_1GJsk-9W1xEJma_1y_eD9lh-dcfFeFDJAojgTGTRXh7_pL-TuFHTpUyhxjqhF1vpx2JS-nZCZh2JvGGiNsR6Flfowon7mWkfQ16RyxAx5vLOL2C0ub5EIL86jq';

            $header = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "mutable_content" : true,
            "data" : {
                "title": "' . $data['title'] . '",
                "body": "' . $data['description'] . '",
                "order_id": "' . $data['order_id'] . '",
                "type": "' . $data['type'] . '",
                "is_read": 0,
             },
             "notification" : {
                "title": "' . $data['title'] . '",
                "body": "' . $data['description'] . '",
                "order_id": "' . $data['order_id'] . '",
                "title_loc_key": "' . $data['order_id'] . '",
                "body_loc_key": "' . $data['type'] . '",
                "type": "' . $data['type'] . '",
                "is_read": 0,
                "icon": "' . $data['new'] . '",
                "android_channel_id": "' . $data['dbfood'] . '",
             }
        }';
        $token_1 = 'cWQc7_cRTc6SZow6vo0dLh:APA91bFhXN-SMc9nU5stpNZOhKBvYY9PYU-nfvpLzd3ewS6E-IkRnIRs3M7bGBR4wlAwTaR27Rqqh41XSm9IhxSycCujaOWFN6VvIONAwWbfcKTi-ffgBxQw1Pjbw2_BSQKtLjtWOKQ7';

        $data = [

            "registration_ids" => [
                $token_1
            ],

            "notification" => [

                "title" => 'Welcome Here - Registering',

                "body" => 'Description I ma the notfication of the registration_ids',

                "sound" => "default" // required for sound on ios

            ],

        ];

        $dataString = json_encode($data);

        // $ch = curl_init();
        // $timeout = 120;
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POST, true);



        // $result = curl_exec($ch);
        // if ($result === false) {
        //     dd(curl_errno($ch));
        // }

        // curl_close($ch);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $result = curl_exec($ch);

        return $result;
    }

    public static function order_status_update_message($status)
    {
        // if ($status == 'pending') {
        //     $data = BusinessSetting::where('key', 'order_pending_message')->first();
        // } elseif ($status == 'confirmed') {
        //     $data = BusinessSetting::where('key', 'order_confirmation_msg')->first();
        // } elseif ($status == 'processing') {
        //     $data = BusinessSetting::where('key', 'order_processing_message')->first();
        // } elseif ($status == 'picked_up') {
        //     $data = BusinessSetting::where('key', 'out_for_delivery_message')->first();

        // } elseif ($status == 'handover') {
        //     $data = BusinessSetting::where('key', 'order_handover_message')->first();

        // } elseif ($status == 'delivered') {
        //     $data = BusinessSetting::where('key', 'order_delivered_message')->first();

        // } elseif ($status == 'delivery_boy_delivered') {
        //     $data = BusinessSetting::where('key', 'delivery_boy_delivered_message')->first();

        // } elseif ($status == 'accepted') {
        //     $data = BusinessSetting::where('key', 'delivery_boy_assign_delivered_message')->first();

        // } elseif ($status == 'canceled') {
        //     $data = BusinessSetting::where('key', 'order_canceled_message')->first();

        // } elseif ($status == 'refunded') {
        //     $data = BusinessSetting::where('key', 'order_refunded_message')->first();

        // } else {
          // $data = '{"status":"0"},{"message":""}';
          $data = BusinessSetting::where('key', 'order_pending_message')->first();

      //  }
        return   '';// $data['value']['message'];
    }
}
