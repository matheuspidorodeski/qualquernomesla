<?php
session_start();
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include_once('../config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifica se a senha fornecida corresponde ao hash armazenado
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $user['senha'];

            // Verifica se o usuário é administrador
            if ($user['e_admin'] == 1) {
                header('Location: ../dashboard/dashboard.php');
            } else {
                header('Location: ../dashboard/dashboard.php');
            }
        } else {
            echo "<p style='color:red;'>Senha incorreta</p>";
        }
    } else {
        echo "<p style='color:red;'>Usuário não encontrado</p>";
    }
} else {
    echo "<p style='color:red;'>Por favor, preencha todos os campos.</p>";
}
?>
