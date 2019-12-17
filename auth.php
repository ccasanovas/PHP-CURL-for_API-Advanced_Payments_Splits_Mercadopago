<?php 

// Get del código de mercadopago


(isset($_GET['code'])) ? $get_codigo=$_GET['code'] :$get_codigo='';

// Datos a ser enviados a la API 
  $postData = array(
      'client_id' => '<APP_ID>', 
      'client_secret' => '<SECRET_KEY>', 
      'grant_type' => 'authorization_code', 
      'code' => $get_codigo,
      'redirect_uri' => '<MY_URL>/auth.php',
  ); 

  // Seteo el curl 
  $ch = curl_init('https://api.mercadopago.com/oauth/token'); 
  curl_setopt_array($ch, array(
      CURLOPT_POST => TRUE, 
      CURLOPT_RETURNTRANSFER => TRUE, 
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HTTPHEADER => array(
       'accept: application/json', 
       'Content-Type: application/x-www-form-urlencoded' 
      ), 
      CURLOPT_POSTFIELDS => json_encode($postData) 
  )); 

  // Envío el request
  $response = curl_exec($ch); 

  // Chequeo por errores 
  if($response === FALSE){ 
      die(curl_error($ch)); 
  } 

  // Decode a la respuesta 
  $responseData = json_decode($response, TRUE); 

  // Por cada parámetro de la respuesta seteo una variable
  
  foreach($responseData as $key => $val){
    $access_token = $responseData["access_token"];
    $public_key = $responseData["public_key"];
    $refresh_token = $responseData["refresh_token"];
    $user_id = $responseData["user_id"];
     
  // Si hay datos de respuesta ejecuto la acción (Guardo el Mercadopago ID, etc etc)
     if ($responseData) {
        
    }
    
  }
  
  ?>
