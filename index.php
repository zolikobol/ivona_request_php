<?php

$dateTime = new DateTime('NOW' , new DateTimeZone('Europe/London'));
//$dateTime->modify('+5 minutes');

$date = $dateTime->format('Ymd');
$date1 = $dateTime->format('His');

$signature = getSignatureKey('sha256' , '+n/y4VQO15AbjP5kbsC3k5rthv+F27ZIGcpk1yJQ' , $date , 'eu-west-1' , 'aws4_request' , $date1);

$header = array();
$header[] = 'Authorization: AWS4-HMAC-SHA256 Credential=GDNAIW4VCJKOZQHK2XIA/' . $date . '/eu-west-1/tts/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature='.$signature['signature'];
$header[] = 'X-Amz-Date: ' . $signature['date'];
$header[] = 'x-amz-content-sha256: ' . hash('sha256' , '{"Input":{"Data":"Hello world"}}');
$header[] = 'Content-type: application/json';

requestToIvona($header);

function getSignatureKey($algo = 'sha256' , $key , $dateStamp , $regionName , $serviceName , $date1){

  //string hash_hmac ( string $algo , string $data , string $key [, bool $raw_output = false ] )

  $DateKey = hash_hmac($algo , $dateStamp , "AWS4" + $key);
  //var_dump($DateKey);
  $DateRegionKey = hash_hmac($algo , $regionName , $DateKey);
  //var_dump($DateRegionKey);
  $DateRegionServiceKey = hash_hmac($algo , "tts" , $DateRegionKey);
  //var_dump($DateRegionServiceKey);
  $SigningKey = hash_hmac($algo , $serviceName , $DateRegionServiceKey);
  //$Signature = hash_hmac($algo , $serviceName , $SigningKey);
  $date = $dateStamp . "T" ;
  $date.= $date1 . "Z";

  return array(
    'signature' => $SigningKey,
    'date'      => $date
  );

}

function requestToIvona($header){

  $url = 'https://tts.eu-west-1.ivonacloud.com';

  $session = curl_init();
  curl_setopt($session, CURLOPT_URL, $url);
  curl_setopt($session, CURLOPT_HTTPHEADER,$header);
  curl_setopt($session, CURLOPT_POST, true);
  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

  $content = curl_exec($session);
  $responseInfo = curl_getinfo($session);
  var_dump($content);exit;

}
