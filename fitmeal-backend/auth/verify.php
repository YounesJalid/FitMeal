<?php
require_once "../db.php";
require_once "../utils/responses.php";
require_once "../utils/jwt.php";

$headers = getallheaders();

if (!isset($headers["Authorization"])) {
    jsonError("Missing Authorization header", 401);
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);

$payload = verifyJWT($token);

if (!$payload || !isset($payload["user_id"])) {
    jsonError("Invalid token", 401);
}

// Fetch user info
$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->execute([$payload["user_id"]]);
$user = $stmt->fetch();

if (!$user) {
    jsonError("User not found", 404);
}

jsonSuccess([
    "valid" => true,
    "user" => $user
]);
