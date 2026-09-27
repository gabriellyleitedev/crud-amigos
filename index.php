<?php
session_start();

$conn = new mysqli("localhost", "root", "", "crud_amigos");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE email='$email' AND senha='$senha'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $_SESSION["login"] = $email;
        header("Location: sistema.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="box">

    <h1>Cadastro de Amigos</h1>
    <p>Faça login para acessar o sistema</p>

    <?php if (isset($erro)) echo "<div class='erro'>$erro</div>"; ?>

    <form method="POST">

        <input type="email" name="email" placeholder="E-mail" required>

        <input type="password" name="senha" placeholder="Senha" required>

        <button>Entrar</button>

    </form>

</div>

</body>
</html>