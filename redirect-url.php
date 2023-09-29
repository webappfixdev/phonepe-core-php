<?php
	
	$response = $_POST;

	$saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399'; 
	$saltIndex = 1; 

    $string = "/pg/v1/status/" . $response['merchantId'].'/'.$response['transactionId'].$saltKey;
    $sha256 = hash("sha256", $string);
    $final_x_header = $sha256 . '###' . $saltIndex;


	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/".$response['merchantId']."/".$response['transactionId']);
	curl_setopt(
    $ch, 
    CURLOPT_HTTPHEADER, 
	    array(
	        'Content-Type: application/json',
	        'accept: application/json',
	        'X-VERIFY:'.$final_x_header,
	        'X-MERCHANT-ID:'.$response['merchantId'],
	    )
	);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);
	$runOutput = json_decode($server_output,true);

	echo '<pre>';
		print_r($runOutput);
	echo '</pre>';

?>