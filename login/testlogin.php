<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    include_once('../config.php');
    
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Consulta preparada para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Exibe os dados recuperados para confirmar o conteúdo
        echo "<pre>";
        print_r($user);
        echo "</pre>";

        // Verifica se a senha fornecida corresponde ao hash armazenado
        if (password_verify($senha, $user['senha'])) {
            // Verifica se o campo 'id' existe no array e atribui a sessão
            if (isset($user['id'])) {
                $_SESSION['id_cliente'] = $user['id']; // Define o ID do usuário na sessão
                $_SESSION['email'] = $user['email'];
                
                // Redirecionamento com base no nível de acesso
                if ($user['e_admin'] == 1) {
                    header('Location: ../dashboard/dashboard.php');
                } else {
                    header('Location: ../dashboard/dashboard.php');
                }
                exit(); // Para o script após o redirecionamento
            } else {
                echo "<p style='color:red;'>Erro: ID do usuário não encontrado.</p>";
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
