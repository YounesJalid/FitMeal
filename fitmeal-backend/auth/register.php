<?php
require_once "../db.php";
require_once "../utils/helpers.php";
require_once "../utils/responses.php";
require_once "../utils/jwt.php";

$data = getJsonInput();

// Required fields
if (!requireFields($data, ["name", "email", "password"])) {
    jsonError("Missing required fields: name, email, password");
}

$name = trim($data["name"]);
$email = strtolower(trim($data["email"]));
$password = $data["password"];

// Check valid email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError("Invalid email format");
}

// Check if user already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    jsonError("Email already in use", 409);
}

// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $hashed]);

$user_id = $pdo->lastInsertId();

// Create token
$token = createJWT(["user_id" => $user_id, "email" => $email]);

// Save token in database
$stmt = $pdo->prepare("UPDATE users SET token = ? WHERE id = ?");
$stmt->execute([$token, $user_id]);

jsonSuccess([
    "message" => "Account created successfully",
    "token" => $token,
    "user" => [
        "id" => $user_id,
        "name" => $name,
        "email" => $email
    ]
], 201);
