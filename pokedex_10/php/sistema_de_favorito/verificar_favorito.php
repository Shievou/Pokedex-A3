<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    exit;
}

require_once "../config.php";

$userId = $_SESSION["id"];

$data = json_decode(file_get_contents("php://input"));
$pokemonName = $data->pokemonName;

$stmt = $pdo->prepare("SELECT 1 FROM user_pokemon WHERE user_id = :userId AND pokemon_name = :pokemonName");
$stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
$stmt->bindParam(":pokemonName", $pokemonName, PDO::PARAM_STR);
$stmt->execute();

echo json_encode(["isFavorite" => $stmt->rowCount() > 0]);
?>