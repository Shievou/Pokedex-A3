<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../images/favicon-16x16.png">
  <link rel="stylesheet" href="../css/style.css">
  <script defer src="../js/script.js"></script>
  <title>Pokédex</title>
</head>
<body>
  <main>
      <h1 class="my-5">Oi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bem vindo ao nosso site.</h1>
    </div>
  <img src="#" alt="pokemon" class="pokemon__image">

    <h1 class="pokemon__data">
      <span class="pokemon__number"></span> -
      <span class="pokemon__name"></span>
    </h1>

    <form class="form">
      <input
        type="search"
        class="input__search"
        placeholder="Name or Number"
        required
      />
    </form>

    <input type="checkbox" class="checkbox-favorite">

    <button class="add-to-team-button">Add</button>

    <button class="remove-from-team-button">Remove</button>

    <div class="buttons">
      <button class="button btn-prev">Prev &lt;</button>
      <button class="button btn-next">Next &gt;</button>
    </div>

    <img src="../images/pokedex.png" alt="pokedex" class="pokedex">
    <p class="links">

        <a href="senha.php" class="btn btn-warning">Redefina sua senha &nbsp; </a>
        <a href="logout.php" class="btn btn-danger ml-3"> &nbsp; Sair da conta</a>
    </p>
  </main>
</body>
</html>