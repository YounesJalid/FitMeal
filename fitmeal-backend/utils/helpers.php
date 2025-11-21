<?php

// Lire le body JSON proprement
function getJsonInput() {
    $raw = file_get_contents("php://input");
    $json = json_decode($raw, true);
    return $json ? $json : [];
}

// Vérifier champs obligatoires
function requireFields($data, $fields = []) {
    foreach ($fields as $f) {
        if (!isset($data[$f]) || $data[$f] === "") {
            return false;
        }
    }
    return true;
}
