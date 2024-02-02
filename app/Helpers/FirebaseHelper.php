<?php

namespace App\Helpers;

class FirebaseHelper
{

  public static function CustomNotification($notification_data, $token)
  {

    $data = array(
      "data" => array(
        "title" => $notification_data['title'],
        "body" => $notification_data['body'],
      )
    );
    self::sendNotification($data, $token);


  }

  public static function CustomnewNotification($notification_data, $token)
  {

    $data = array(
      "data" => array(
        "user_id" => $notification_data['userId'],
        "title" => $notification_data['title'],
        "body" => $notification_data['body'],
      )
    );
    self::sendNotification($data, $token);


  }
  public static function sendNotification($notification_data, $tokens)
  {

    $notification_data['notification']['title'] = $notification_data['data']['title'];
    $notification_data['notification']['body'] = $notification_data['data']['body'];

    /// target mulitple device
    $notification_data["registration_ids"] = $tokens;
    $resut = self::runCurl($notification_data);

  }
  public static function runCurl($payload)
  {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type:application/json', "authorization:key=AAAAP2Tqefo:APA91bEVCpmGZCm0qdIGhPqwy96_Hq5AzgLe-dFJhNwtz8CB3a1u35smEMJdA0-FTJuCjumpPhxSyOoEhS7Ozx6UR-YfKrq0CFpzi3qCY1aUrUYVfMm7jmuuePX4qYkuB_uL3lsvQE_W"));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt(
      $ch,
      CURLOPT_POSTFIELDS,
      json_encode($payload)
    );
    $result = curl_exec($ch);
    //file_put_contents('newfire.txt', json_encode($result));

   // echo $result;
    //   echo '<pre>';
    // print_r($result);
    // die;



  }

}