<?php
require_once "../db.php";
require_once "../middleware.php";
require_once "../utils/helpers.php";
require_once "../utils/responses.php";

$user_id = authUser();
$data = getJsonInput();

if (!requireFields($data, ["title", "tdee", "meals"])) {
    jsonError("Missing required fields");
}

$stmt = $pdo->prepare("INSERT INTO plans (user_id, title, tdee, meals) VALUES (?, ?, ?, ?)");
$stmt->execute([
    $user_id,
    $data["title"],
    $data["tdee"],
    json_encode($data["meals"], JSON_UNESCAPED_UNICODE)
]);

jsonSuccess(["message" => "Plan created"]);
