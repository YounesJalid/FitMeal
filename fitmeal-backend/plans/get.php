<?php
require_once "../db.php";
require_once "../middleware.php";
require_once "../utils/responses.php";

$user_id = authUser();

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) jsonError("Invalid ID");

$stmt = $pdo->prepare("SELECT * FROM plans WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

$plan = $stmt->fetch();
if (!$plan) jsonError("Plan not found", 404);

$plan["meals"] = json_decode($plan["meals"], true);

jsonSuccess($plan);
