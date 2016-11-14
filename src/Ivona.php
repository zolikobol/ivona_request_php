<?php

class Ivona{

  private $header = array();
  private $accessKey;
  private $serverKey;
  private $config;

  public function __construct($accessKey , $secretKey , $config){
    $this->accessKey = $accessKey;
    $this->serverKey = $serverKey;
    $this->config = $config;
  }

  private function getSignature(){

  }


}

 ?>
