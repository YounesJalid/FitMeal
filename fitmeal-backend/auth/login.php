<?php
require_once "../db.php";
require_once "../utils/helpers.php";
require_once "../utils/responses.php";
require_once "../utils/jwt.php";

$data = getJsonInput();

// Required fields
if (!requireFields($data, ["email", "password"])) {
    jsonError("Missing email or password");
}

$email = strtolower(trim($data["email"]));
$password = $data["password"];

// Fetch user
$stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    jsonError("Invalid credentials", 401);
}

// Verify password
if (!password_verify($password, $user["password"])) {
    jsonError("Invalid credentials", 401);
}

// Create new token
$token = createJWT(["user_id" => $user["id"], "email" => $email]);

// Save token in database
$stmt = $pdo->prepare("UPDATE users SET token = ? WHERE id = ?");
$stmt->execute([$token, $user["id"]]);

jsonSuccess([
    "message" => "Login successful",
    "token" => $token,
    "user" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"]
    ]
]);
