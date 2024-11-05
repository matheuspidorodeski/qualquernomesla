<?php
session_start();
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include_once('../config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conexao->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: ../paginadm/loginadm.php');
    } else {
        $user = $result->fetch_assoc();
        
        // Usando password_verify para verificar a senha criptografada
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['email'] = $email;
            header('Location: ../paginadm/ADM.php');
        } else {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: ../paginadm/loginadm.php');
        }
    }
} else {
    header('Location: ../paginadm/loginadm.php');
}
?>
