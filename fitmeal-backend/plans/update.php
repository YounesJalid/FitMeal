<?php
require_once "../db.php";
require_once "../middleware.php";
require_once "../utils/helpers.php";
require_once "../utils/responses.php";

$user_id = authUser();
$data = getJsonInput();

if (!requireFields($data, ["id", "title", "tdee", "meals"])) {
    jsonError("Missing fields");
}

$stmt = $pdo->prepare("UPDATE plans SET title=?, tdee=?, meals=? WHERE id=? AND user_id=?");
$stmt->execute([
    $data["title"],
    $data["tdee"],
    json_encode($data["meals"], JSON_UNESCAPED_UNICODE),
    $data["id"],
    $user_id
]);

jsonSuccess(["message" => "Plan updated"]);
