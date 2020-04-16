<?php

function get(): void
{
    header('Content-Type: application/json');
    $anon = sha1($_SERVER['REMOTE_ADDR']);
    $client = $_SERVER['HTTP_USER_AGENT'];
    $out = array($anon, $client);
    echo(json_encode($out));
}