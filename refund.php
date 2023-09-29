<?php
	$payload = [
		"merchantId"=> "PGTESTPAYUAT",
	    "merchantUserId"=> "MUID123",
	    "originalTransactionId"=> "T2309292323263758429721",
	    "merchantTransactionId"=> "MT7850590068188104",
	    "amount"=> 5000,
	    "callbackUrl"=> "http://localhost:8000/call-url.php"
	];

	/*refund*/
	$encode = base64_encode(json_encode($payload));

	$saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399'; 
	$saltIndex = 1; 

	$final_x_header = hash('sha256',$encode.'/pg/v1/refund'.$saltKey).'###'.$saltIndex;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/refund");
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
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode(array('request' => $encode)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	$responsePayment = json_decode($server_output,true);
	echo '<pre>';
	print_r($responsePayment);
	echo '</pre>';

	/*status*/
	$string = '/pg/v1/status/'.$payload['merchantId'].'/'.$payload['merchantTransactionId'].$saltKey;
	$sha256 = hash('sha256',$string);
	$final_x_header_status = $sha256.'###'.$saltIndex;


	$chs = curl_init();

	curl_setopt($chs, CURLOPT_URL,"https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/".$payload['merchantId']."/".$payload['merchantTransactionId']);
	curl_setopt(
    $chs, 
    CURLOPT_HTTPHEADER, 
	    array(
	        'Content-Type: application/json',
	        'accept: application/json',
	        'X-VERIFY:'.$final_x_header_status,
	        'X-MERCHANT-ID:'.$payload['merchantId'],
	    )
	);
	curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);

	$server_outputs = curl_exec($chs);
	$runOutput = json_decode($server_outputs,true);

	echo '<pre>';
		print_r($runOutput);
	echo '</pre>';
?>