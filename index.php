<!DOCTYPE html>
<html>
    <head>
        <title>JWT Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
        <a href="validate.php" target="_blank">Validate Token</a>
        <hr>
        
        <form name="frmLogin" method="post" enctype="multipart/form-data">
            <h1>Login</h1>

            <p>Username: <input type="text" name="username" value=""/></p>
            <p>Password: <input type="password" name="password" value=""/></p>
            <p><button type="submit">Login</button></p>
        </form>
        
        <hr>
        
    </body>
</html>


<?php
require 'Jwt/Jwt.php';

if($_POST){
    $username = trim($_REQUEST['username']);
    $password = trim($_REQUEST['password']);
    
    if($username=='admin' && $password=='123'){
        $jwt = new Jwt\Jwt(['payload'=>[
            "iss" => 'localhost',
            "aud" => '',
            "iat" => time(),
            "nbf" => time()+10,
            "exp" => time()+60,
            "data" => [
                "username"  => $username,
                "password"  => $password,
                "loginTime" => time()
            ]
        ]]);
        $secretKey = $jwt->getSecretKey();
        echo 'Secret Key: '.$secretKey;
        $token = $jwt->generateToken();
        echo '<hr>Token: '.$token;
    }else{
        echo 'Wrong Username or Password.';
    }
}