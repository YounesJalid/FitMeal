<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/utils/jwt.php";
require_once __DIR__ . "/utils/responses.php";

function authUser() {
    $headers = getallheaders();

    if (!isset($headers["Authorization"])) {
        jsonError("Missing Authorization header", 401);
    }

    $token = str_replace("Bearer ", "", $headers["Authorization"]);

    $payload = verifyJWT($token);

    if (!$payload || !isset($payload["user_id"])) {
        jsonError("Invalid token", 401);
    }

    return $payload["user_id"];
}
