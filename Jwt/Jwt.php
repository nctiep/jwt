<?php
namespace Jwt;

class Jwt
{
    protected $secretKey;
    protected $header;
    protected $payload;
    
    public function __construct($params)
    {
        $this->setData($params);
        $this->secretKey = bin2hex(random_bytes(32));
    }
    
    private function setData($params)
    {
        $this->header  = $params['header'] ?? ['typ'=>'JWT', 'alg'=>'HS256'];
        $this->payload = $params['payload'] ?? [];
    }
    
    public function getSecretKey()
    {
        return $this->secretKey;
    }
    
    public function generateToken()
    {
        $base64UrlHeader  = \Jwt\Jwt::base64UrlEncode(json_encode($this->header));
        $base64UrlPayload = \Jwt\Jwt::base64UrlEncode(json_encode($this->payload));
        $signature = hash_hmac('sha256', $base64UrlHeader.".".$base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = \Jwt\Jwt::base64UrlEncode($signature);
        return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;
    }
    
    public static function validateToken($token, $secretKey)
    {
        $tokenParts = explode('.', $token);
        $header  = \Jwt\Jwt::base64UrlDecode($tokenParts[0]);
        $payload = \Jwt\Jwt::base64UrlDecode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $base64UrlHeader = \Jwt\Jwt::base64UrlEncode($header);
        $base64UrlPayload = \Jwt\Jwt::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader.".".$base64UrlPayload, $secretKey, true);
        $base64UrlSignature = \Jwt\Jwt::base64UrlEncode($signature);
        
        $payloadObj = json_decode($payload);
        $expireTime = $payloadObj->exp - time();

        return [
            'header'         => json_decode($header),
            'payload'        => json_decode($payload),
            'signatureValid' => $base64UrlSignature===$signatureProvided?'true':'false',
            'expireTime'     => $expireTime
        ];
    }
    
    public static function base64UrlEncode($string)
    {
        $b64 = base64_encode($string);
        if ($b64 === false) return false;
        $url = strtr($b64, '+/', '-_');
        return rtrim($url, '=');
    }
    
    public static function base64UrlDecode($string, $strict = false)
    {
        $b64 = strtr($string, '-_', '+/');
        return base64_decode($b64, $strict);
    }
}