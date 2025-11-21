<?php
require_once "../db.php";
require_once "../middleware.php";
require_once "../utils/helpers.php";
require_once "../utils/responses.php";

$user_id = authUser();
$data = getJsonInput();

if (!isset($data["id"])) jsonError("ID required");

$stmt = $pdo->prepare("DELETE FROM plans WHERE id = ? AND user_id = ?");
$stmt->execute([$data["id"], $user_id]);

jsonSuccess(["message" => "Plan deleted"]);
