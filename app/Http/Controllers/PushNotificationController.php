<?php

namespace App\Http\Controllers;

use App\Models\PushNotification;
use Illuminate\Http\Request;
use Mockery\Expectation;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $push_notifications = PushNotification::orderBy('created_at', 'desc')->get();
        return view('notification.index', compact('push_notifications'));
    }



    public function bulksend(Request $req)
    {
        // $comment = new PushNotification();
        // $comment->title = $req->input('title');
        // $comment->body = $req->input('body');
        // $comment->img = $req->input('img');
        // $comment->save();
        // $url = 'https://fcm.googleapis.com/fcm/send';
        // $dataArr = array('click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'id' => $req->id, 'status' => "done");
        // $notification = array('title' => $req->title, 'text' => $req->body, 'image' => $req->img, 'sound' => 'default', 'badge' => '1');
        // $arrayToSend = array('to' => "/topics/all", 'notification' => $notification, 'data' => $dataArr, 'priority' => 'high');
        // $fields = json_encode($arrayToSend);
        // $headers = array(
        //     'Authorization: key=' . "BN_Uj9BfDvftsCAeUshgjEpfCGdh0JPELzaFMt8wv_WuESdL5APg5lazUdVoWCdp9Zdkt9TMTJvuV7cjT0bAu4I",
        //     'Content-Type: application/json',
        // );
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        // $result = curl_exec($ch);
        // //var_dump($result);
        // curl_close($ch);
        // return redirect()->back()->with('success', 'Notification Send successfully');

        $SERVER_API_KEY =   'AAAA7oSRoSI:APA91bEMUHxy1a7xf_1GJsk-9W1xEJma_1y_eD9lh-dcfFeFDJAojgTGTRXh7_pL-TuFHTpUyhxjqhF1vpx2JS-nZCZh2JvGGiNsR6Flfowon7mWkfQ16RyxAx5vLOL2C0ub5EIL86jq';
        $token_1 = 'cGScUO9OTdu5UGwT2bKJ6O:APA91bH28dneF5eLZ88GNpSqEDBiEMdy98S38EGTgnfjLH2EHJjD5CqYtIIiPG79wrisSjO2SpHBkhSPl4uYQ4zV4y_vg7tx65K5guDhEsum29TVxkWjKS-7Htbz56886rsb7Q_1Joyc';

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

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            dd($response);
        } catch (expectation $e) {
            print_r($e);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notification.create');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotification $pushNotification)
    {
        //
    }
}
