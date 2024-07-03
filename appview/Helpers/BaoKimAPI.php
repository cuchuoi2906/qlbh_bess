<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 7/5/19
 * Time: 12:31
 */

namespace AppView\Helpers;


use Exception;
use Firebase\JWT\JWT;

class BaoKimAPI
{
    /* Bao Kim API key */
    const API_KEY = "dacde9825ecb4423a5e5429e06d1af44";
    const API_SECRET = "bca8a9ca938e4004b5172f44e0e1b3d0";

    /** Bảo Kim sandbox **/
//    const API_KEY = "a18ff78e7a9e44f38de372e093d87ca1";
//    const API_SECRET = "9623ac03057e433f95d86cf4f3bef5cc";

    const TOKEN_EXPIRE = 186400; //token expire time in seconds
    const ENCODE_ALG = 'HS256';

    private static $_jwt = null;

    /**
     * Refresh JWT
     */
    public static function refreshToken($data = [])
    {

        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time() - 100000;
        $notBefore = $issuedAt;
        $expire = $notBefore + self::TOKEN_EXPIRE;

        /*
         * Payload data of the token
         */
        $data = [
            'iat' => $issuedAt,         // Issued at: time when the token was generated
            'jti' => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss' => self::API_KEY,     // Issuer
            'nbf' => $notBefore,        // Not before
            'exp' => $expire,           // Expire
            'form_params' => $data
        ];

        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        self::$_jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            self::API_SECRET, // The signing key
            'HS256'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        return self::$_jwt;
    }

    /**
     * Get JWT
     */
    public static function getToken($data = [])
    {
        if (!self::$_jwt)
            self::refreshToken($data);

        try {
            JWT::decode(self::$_jwt, self::API_SECRET, array('HS256'));
        } catch (Exception $e) {
            self::refreshToken($data);
        }

        return self::$_jwt;
    }
}