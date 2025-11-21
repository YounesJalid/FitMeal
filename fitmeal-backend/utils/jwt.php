<?php

$JWT_SECRET = "FITMEAL_SUPER_SECRET_KEY"; // change le si tu veux

function createJWT($payload, $secret = null) {
    global $JWT_SECRET;
    if (!$secret) $secret = $JWT_SECRET;

    $header = base64_encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
    $payload = base64_encode(json_encode($payload));

    $signature = hash_hmac("sha256", "$header.$payload", $secret, true);
    $signature = base64_encode($signature);

    return "$header.$payload.$signature";
}

function verifyJWT($jwt, $secret = null) {
    global $JWT_SECRET;
    if (!$secret) $secret = $JWT_SECRET;

    $parts = explode(".", $jwt);
    if (count($parts) !== 3) return false;

    list($header, $payload, $signature) = $parts;

    $expected = base64_encode(hash_hmac("sha256", "$header.$payload", $secret, true));

    if (!hash_equals($expected, $signature)) return false;

    return json_decode(base64_decode($payload), true);
}
