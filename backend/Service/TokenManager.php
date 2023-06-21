<?php

class TokenManager
{
    private const REFRESH_TOKEN_DROPBOX = 'sWFz05mOQh8AAAAAAAAAAWrFDf7-IGpjm6DUtZXhyVjHW12nm8uXt7HsFDRFuybw';
    private const CLIENT_ID = '2omi7kgy0kxzehl';
    private const CLIENT_SECRET = 'wt64o80olyj4j5e';
    public static $tokenAccessDROPBOX = '';


    public static function IsValid() : bool
    {
        //make a request that doesn't return anything to the dropbox api
        //if the status code is 200 -> token is valid else not
        if(empty(self::$tokenAccessDROPBOX)){
            return false;
        }

        $headers = array('Authorization: Bearer ' . self::$tokenAccessDROPBOX,
            'Content-Type: application/json');

        $data = array("query" => "foo");

        $curlOptions = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($data),
        );

        $ch = curl_init('https://api.dropboxapi.com/2/check/user');
        curl_setopt_array($ch, $curlOptions);
        curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $status == 200;
    }

    public static function generateNewToken() : void
    {
        //using the refresh_token -- which doesn't expire, we generate access tokens
        //every time when the old ones expires
        $curl = curl_init();

        $data = array(
            'refresh_token' => self::REFRESH_TOKEN_DROPBOX,
            'grant_type' => 'refresh_token',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropbox.com/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);


        self::$tokenAccessDROPBOX = $response['access_token'];

    }
}