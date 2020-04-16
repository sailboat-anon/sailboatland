<?php

function get(): void
{
    header('Content-Type: application/json');
    $hdrs = getallheaders();
    $anon_raw = $hdrs["Host"];
    $user_agent = $hdrs["User-Agent"];

    $cipher = "aes-128-gcm";
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $anon_encrypt = openssl_encrypt($anon_raw, $cipher, $key, $options=0, $iv, $tag);
        //store $cipher, $iv, and $tag for decryption later
        //$anon = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
    }
    $out = array($anon_encrypt, $user_agent);
    echo(json_encode($out));
}