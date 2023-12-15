<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403); 
    exit;
}

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION["id"];

    $data = json_decode(file_get_contents("php://input"));
    $pokemonName = $data->pokemonName;

    $stmt = $pdo->prepare("INSERT INTO user_pokemon (user_id, pokemon_name) VALUES (:userId, :pokemonName)");
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":pokemonName", $pokemonName, PDO::PARAM_STR);

    if ($stmt->execute()) {
        http_response_code(200); 
        exit;
    } else {
        http_response_code(500);
        exit;
    }
}
?>