<?php
require_once "../db.php";
require_once "../middleware.php";
require_once "../utils/responses.php";

$user_id = authUser();

$stmt = $pdo->prepare("SELECT * FROM plans WHERE user_id = ?");
$stmt->execute([$user_id]);

$plans = $stmt->fetchAll();
foreach ($plans as &$p) {
    $p["meals"] = json_decode($p["meals"], true);
}

jsonSuccess($plans);
