<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403); 
    exit;
}

require_once "../config.php";

$userId = $_SESSION["id"];

$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM user_team WHERE user_id = :userId");
$stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['count'] >= 6) {
    http_response_code(400); 
    echo json_encode(["message" => "Seu time já possui 6 Pokémon. Remova um antes de adicionar outro."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));
$pokemonName = $data->pokemonName;

$stmt = $pdo->prepare("SELECT 1 FROM user_team WHERE user_id = :userId AND pokemon_name = :pokemonName");
$stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
$stmt->bindParam(":pokemonName", $pokemonName, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    $stmt = $pdo->prepare("INSERT INTO user_team (user_id, pokemon_name) VALUES (:userId, :pokemonName)");
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":pokemonName", $pokemonName, PDO::PARAM_STR);
    $stmt->execute();

    http_response_code(200); 
} else {
    http_response_code(409);
}
?>