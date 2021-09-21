<!DOCTYPE html>
<html>
    <head>
        <title>JWT Validate Token</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
        <form name="frmValidate" method="post" enctype="multipart/form-data">
            <h1>Validate Token</h1>

            <p>Secret Key:<br><textarea name="secretKey" style="width: 90%;"></textarea></p>
            <p>Token:<br><textarea name="token" style="width: 90%;height: 70px;"></textarea></p>
            <p><button type="submit">Validate Token</button></p>
        </form>
        
        <hr>
        
    </body>
</html>

<?php
require 'Jwt/Jwt.php';

if($_POST){
    $secretKey = trim($_REQUEST['secretKey']);
    $token     = trim($_REQUEST['token']);
    
    $validateToken = \Jwt\Jwt::validateToken($token, $secretKey);
    echo '<pre>';
    print_r($validateToken);
    echo '</pre>';
    echo '<hr>Signature Valid: '.$validateToken['signatureValid'];
    if($validateToken['expireTime']<0){
        echo '<hr>Token Expired';
    }else{
        echo '<hr>Expire Time: '.date('d/m/Y H:i:s', $validateToken['expireTime']+time());
    }
}