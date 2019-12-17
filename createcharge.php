<?php
// Datos pasados por el formulario

$token = $_REQUEST["token"];
$payment_method_id = $_REQUEST["paymentMethodId"];
$installments = $_REQUEST["installments"];
$installments = floatval($installments);
$issuer_id = $_REQUEST["issuer_id"];
$issuer_id = floatval($issuer_id);
$total = $_REQUEST["amount"];
$total = floatval($total);
$email = $_REQUEST["email"];

session_start();
$getcodigo = $_SESSION['getcodigo'];
$iniuser = $_SESSION['iniuser'];
$login = $_SESSION['login'];
$perfil = $_SESSION['perfil'];

$conexion = new ConexionBd();

if ($payment_method_id == "visa") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "master") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "amex") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "diners") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "naranja") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "nativa") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "shopping") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "cencosud") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "cmr_master") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "argencard") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "cordial") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "cordobesa") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "cabal") {
	$payment_type = 'credit_card';
}
if ($payment_method_id == "debvisa") {
	$payment_type = 'debit_card';
}
if ($payment_method_id == "debmaster") {
	$payment_type = 'debit_card';
}
if ($payment_method_id == "maestro") {
	$payment_type = 'debit_card';
}
if ($payment_method_id == "debcabal") {
	$payment_type = 'debit_card';
}

// Declaración de cada disbursement para cada usuario según una consulta a la BD dada

foreach ($consultaBD as $i => $itemObject) {
 $particion = array($itemObject);
 $itemObject = new stdClass();
 $itemObject->amount = $trans_monto_usuario;
 $itemObject->external_reference = $trans_orden;
 $itemObject->collector_id = $mp_user;
 $itemObject->application_fee = $comision;
 $itemObject->money_release_days = 30;
 array_push($particion, $itemObject);

}  

// Declaración de disbursement para un único usuario
if ($consultaBD == "1" {
 $particion = array();
 $itemObject = new stdClass();
 $itemObject->amount = $trans_monto_usuario;
 $itemObject->external_reference = $trans_orden;
 $itemObject->collector_id = $mp_user;
 $itemObject->application_fee = $comision;
 $itemObject->money_release_days = 30;
 array_push($particion, $itemObject);
}


// Post DATA Para mercadopago
 $jayParsedAry = [
   "payer" => [
         "email" => "$email" 
      ], 
   "payments" => [
            [
               "payment_method_id" => "$payment_method_id", 
               'payment_type_id' => $payment_type,
               "token" => "$token", 
               "transaction_amount" => $total, 
               "issuer_id" => $issuer_id,
               "installments" => $installments, 
               "processing_mode" => "aggregator",
               "capture" => true
            ] 
         ], 
   "disbursements" => 
                  $particion
               , 
   "external_reference" => "$trans_orden",
   "site_id" => "MLA"
]; 

// Encode de datos para parsarlos por el curl
$result = json_encode ($jayParsedAry, JSON_PRETTY_PRINT);

// Declaro la URL del CURL
        $ch = curl_init('https://api.mercadopago.com/v1/advanced_payments?access_token=<SECRET_TOKEN_MARKETPLACE>'); 
		curl_setopt_array($ch, array(
	      CURLOPT_POST => TRUE, 
	      CURLOPT_RETURNTRANSFER => TRUE, 
	      CURLOPT_SSL_VERIFYPEER => false,
	      CURLOPT_HTTPHEADER => array(
	       'Accept: application/json', 
	       'Content-Type: application/json' 
	      ), 
	      CURLOPT_POSTFIELDS => $result 
	  	)); 

		// Envío el post  
	  	$response = curl_exec($ch); 

	  	// Chequeo si hay errores 
	  	if($response === FALSE){ 
	      die(curl_error($ch)); 
	  	} 

	  	// Decodifico la respuesta del curl 
	  	$responseData = json_decode($response, TRUE); 

	  	foreach($responseData as $key => $val){
	    $id = $responseData["id"];
	    $status = $responseData["status"];
	    $payments = $responseData["payments"];
	  	} 
	  	

    // Manejo de respuestas según el status
    if ($status == "approved") {
        echo "<script>alert('Éxito su pago ha sido procesado')</script>";
        echo"<script>window.open('index.php','_self')</script>";
    }
    if ($status == "rejected") {
        echo "<script>alert('Su pago ha sido rechazado')</script>";
        echo"<script>window.open('index.php','_self')</script>";
    }
    if ($status == "in_process") {
        echo "<script>alert('La autorización de su pago está a la espera de confirmación')</script>";
        echo"<script>window.open('index.php','_self')</script>";
    }
        



?>
