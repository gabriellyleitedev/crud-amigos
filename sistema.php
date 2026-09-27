<?php

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "crud_amigos");

if ($conn->connect_error) {
    die("Erro ao conectar ao banco.");
}


/* =========================
   CADASTRAR
========================= */

if (isset($_POST["cadastrar"])) {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $conn->query("
        INSERT INTO amigos (nome, email, telefone)
        VALUES ('$nome', '$email', '$telefone')
    ");

    header("Location: sistema.php");
    exit;
}


/* =========================
   EDITAR
========================= */

if (isset($_POST["editar"])) {

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $conn->query("
        UPDATE amigos
        SET nome='$nome',
            email='$email',
            telefone='$telefone'
        WHERE id=$id
    ");

    header("Location: sistema.php");
    exit;
}


/* =========================
   EXCLUIR
========================= */

if (isset($_GET["excluir"])) {

    $id = $_GET["excluir"];

    $conn->query("
        DELETE FROM amigos
        WHERE id=$id
    ");

    header("Location: sistema.php");
    exit;
}


/* =========================
   VERIFICAR EDIÇÃO
========================= */

$editando = false;
$amigo_editar = null;

if (isset($_GET["editar"])) {

    $id = $_GET["editar"];

    $resultado = $conn->query("
        SELECT * FROM amigos
        WHERE id=$id
    ");

    $amigo_editar = $resultado->fetch_assoc();

    $editando = true;
}


/* =========================
   LISTAR AMIGOS
========================= */

$amigos = $conn->query("
    SELECT * FROM amigos
    ORDER BY id DESC
");

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Cadastro de Amigos</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


<div class="container">


    <!-- CABEÇALHO -->

    <div class="topo">

        <div>

            <h1>Cadastro de Amigos</h1>

            <p>
                Sistema de gerenciamento de contatos
            </p>

        </div>

        <a href="index.php" class="sair">
            Sair
        </a>

    </div>



    <!-- FORMULÁRIO -->

    <div class="box">

        <h2>

            <?php

            if ($editando) {
                echo "Editar amigo";
            } else {
                echo "Novo amigo";
            }

            ?>

        </h2>


        <form method="POST">


            <?php if ($editando): ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $amigo_editar["id"]; ?>"
                >

            <?php endif; ?>


            <input
                type="text"
                name="nome"
                placeholder="Nome"
                value="<?php

                    if ($editando) {
                        echo htmlspecialchars(
                            $amigo_editar["nome"]
                        );
                    }

                ?>"
                required
            >


            <input
                type="email"
                name="email"
                placeholder="E-mail"
                value="<?php

                    if ($editando) {
                        echo htmlspecialchars(
                            $amigo_editar["email"]
                        );
                    }

                ?>"
                required
            >


            <input
                type="text"
                name="telefone"
                placeholder="Telefone"
                value="<?php

                    if ($editando) {
                        echo htmlspecialchars(
                            $amigo_editar["telefone"]
                        );
                    }

                ?>"
            >


            <?php if ($editando): ?>

                <button name="editar">
                    Salvar alterações
                </button>

                <a
                    href="sistema.php"
                    style="
                        display:block;
                        text-align:center;
                        margin-top:10px;
                        text-decoration:none;
                    "
                >
                    Cancelar
                </a>

            <?php else: ?>

                <button name="cadastrar">
                    Cadastrar amigo
                </button>

            <?php endif; ?>


        </form>

    </div>



    <!-- LISTA -->

    <div class="box">

        <h2>Amigos cadastrados</h2>


        <table>

            <tr>

                <th>Nome</th>

                <th>E-mail</th>

                <th>Telefone</th>

                <th>Ações</th>

            </tr>


            <?php while ($amigo = $amigos->fetch_assoc()): ?>


                <tr>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $amigo["nome"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $amigo["email"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $amigo["telefone"]
                        );
                        ?>
                    </td>


                    <td>


                        <a
                            href="sistema.php?editar=<?php echo $amigo["id"]; ?>"
                            style="color:#2563eb;"
                        >
                            Editar
                        </a>


                        &nbsp;


                        <a
                            href="sistema.php?excluir=<?php echo $amigo["id"]; ?>"
                            style="color:#dc2626;"
                            onclick="return confirm('Deseja realmente excluir este amigo?');"
                        >
                            Excluir
                        </a>


                    </td>


                </tr>


            <?php endwhile; ?>


        </table>

    </div>


</div>


</body>

</html>