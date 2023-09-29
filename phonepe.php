<?php

	$data = [
	  // "merchantId"=> "MERCHANTUAT",
	  "merchantId"=> "PGTESTPAYUAT",
	  "merchantTransactionId"=> "MT7850590068188104",
	  "merchantUserId"=> "MUID123",
	  "amount"=> 10000,
	  "redirectUrl"=> "http://localhost:8000/redirect-url.php",
	  "redirectMode"=> "POST",
	  "callbackUrl"=> "http://localhost:8000/callback-url.php",
	  "mobileNumber"=> "9999999999",
	  "paymentInstrument" => [
	    "type"=> "PAY_PAGE"
	  ]
	];

	$saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399'; 
	$saltIndex = 1; 

	$encode = json_encode($data);
    $encoded = base64_encode($encode);

    $string = $encoded . "/pg/v1/pay" . $saltKey;
    $sha256 = hash("sha256", $string);
    $final_x_header = $sha256 . '###' . $saltIndex;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
	curl_setopt(
    $ch, 
    CURLOPT_HTTPHEADER, 
	    array(
	        'Content-Type: application/json',
	        'accept: application/json',
	        'X-VERIFY: '.$final_x_header,
	    )
	);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode(array('request' => $encoded)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);


	$runOutput = json_decode($server_output,true);

	header('location:'.$runOutput['data']['instrumentResponse']['redirectInfo']['url']);
	exit;
	
	echo '<pre>';
	print_r();
	echo '</pre>';
?>