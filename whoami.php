<?php

function get(): void
{
    header('Content-Type: application/json');
    $hdrs = getallheaders();
    $anon = sha1($hdrs["Host"]);
    $client = $hdrs["User-Agent"];
    $out = array($anon, $client);
    echo(json_encode($out));
}