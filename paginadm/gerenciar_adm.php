<?php
session_start();
include_once('../config.php');

// Verifica se o usuário está logado e é um administrador
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    echo "<p style='color:red;'>Você precisa estar logado para acessar esta página.</p>";
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conexao->query($sql);
$user = $result->fetch_assoc();

// Verifica se o usuário logado é um administrador de nível 1
if ($user['e_admin'] != 1) {
    echo "<p style='color:red;'>Acesso negado: apenas super-administradores podem acessar esta página.</p>";
    exit;
}

// Processa o formulário para adicionar um novo administrador em diferentes níveis
if (isset($_POST['adicionar_adm'])) {
    $novo_adm_email = $_POST['novo_adm_email'];
    $nivel_adm = $_POST['nivel_adm']; // Recebe o nível do admin (1, 2 ou 3)

    // Verifica se o email existe no sistema
    $sqlCheck = "SELECT * FROM usuarios WHERE email = '$novo_adm_email'";
    $resultCheck = $conexao->query($sqlCheck);

    if ($resultCheck && $resultCheck->num_rows > 0) {
        // Atualiza o nível administrativo do usuário com base na escolha
        $sqlUpdate = "UPDATE usuarios SET e_admin = $nivel_adm WHERE email = '$novo_adm_email'";
        if ($conexao->query($sqlUpdate) === TRUE) {
            echo "<p style='color:green;'>O usuário com o email $novo_adm_email foi promovido ao nível $nivel_adm com sucesso.</p>";
        } else {
            echo "<p style='color:red;'>Erro ao promover o usuário.</p>";
        }
    } else {
        echo "<p style='color:red;'>Email não encontrado.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Administradores</title>
</head>
<body>
    <h1>Gerenciar Administradores</h1>
    <form method="POST" action="">
        <label for="novo_adm_email">Email do novo administrador:</label><br>
        <input type="email" name="novo_adm_email" id="novo_adm_email" required><br><br>

        <label for="nivel_adm">Selecione o nível do administrador:</label><br>
        <select name="nivel_adm" id="nivel_adm" required>
            <option value="1">Nível 1 - Super Admin</option>
            <option value="2">Nível 2 - Admin</option>
            <option value="3">Nível 3 - Moderador</option>
        </select><br><br>

        <button type="submit" name="adicionar_adm">Adicionar Administrador</button>
    </form>

    <br>
    <!-- Botão "Voltar" -->
    <form action="ADM.php" method="get">
        <button type="submit">Voltar</button>
    </form>
</body>
</html>
