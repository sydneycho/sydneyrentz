<?php
//author: sydney chongo
//url : https://sydneyjr.tech


class Sparcopay{

  public static $merchantPublicKey = "409abbbac36841469e8fed6a34fee32d";

  public static function RequestPay($transactionName, $amount, $currency, $customerFirstName, $customerLastName, $customerEmail, $customerPhone)
  {
    //UUID ID generator
      $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));


      $curl = curl_init();
      $var = true;

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://checkout.sparco.io/gateway/api/v1/checkout',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "transactionName": "'.$transactionName.'",
        "amount": "'.$amount.'",
        "currency": "'.$currency.'",
        "transactionReference": "'.$uuid.'",
        "customerFirstName": "'.$customerFirstName.'",
        "customerLastName": "'.$customerLastName.'",
        "customerEmail": "'.$customerEmail.'",
        "customerPhone": "'.$customerPhone.'",
        "returnUrl": "http://localhost/Sparco/verify_pay.php?ref='.$uuid.'",
        "autoReturn": '.$var.',
        "merchantPublicKey": "'.Sparcopay::$merchantPublicKey.'"
      }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      $result = json_decode($response);
      curl_close($curl);

      //Checkout link
      print_r($result);



      //Return checkout link or json data
      return array($result, $uuid);
        }


  //Verify transaction
  
  public static function verifyTransaction($merchantReference)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://live.sparco.io/gateway/api/v1/transaction/query?merchantReference='.$merchantReference.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'token:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwdWJLZXkiOiI0MDlhYmJiYWMzNjg0MTQ2OWU4ZmVkNmEzNGZlZTMyZCIsImlhdCI6MTY5Mzk3OTc4OX0.QJl_0wlhpAQ7ZUDjiCW1e_DfDaC47sWJg8Y7hHoxo_Y'
    ),
  ));

    $response = curl_exec($curl);
    $result = json_decode($response);

    curl_close($curl);
    return ($result);
  }  
      }
