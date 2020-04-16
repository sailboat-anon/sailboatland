<?php

function get(): void
{
    header('Content-Type: application/json');
    $anon = sha1($_SERVER['REMOTE_ADDR']);
    $client = $_SERVER['HTTP_USER_AGENT'];
    /*$ch = curl_init();
    $url = "http://ipinfo.io/";
    $info = gethostbyname($_SERVER['REMOTE_ADDR']);

    curl_setopt($ch, CURLOPT_URL, $url.$info);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = json_encode(curl_exec($ch));
    curl_close($ch); 
    */
    //$out = array($anon, $client, $output);
    $out = array($anon, $client);
    echo(json_encode($out));
}