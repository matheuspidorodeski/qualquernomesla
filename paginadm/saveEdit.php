<?php
session_start();
include_once('../config.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $endereco = $_POST['endereco'];

    // Verificar se a senha foi modificada
    if (!empty($senha)) {
        // Criptografa a nova senha
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
    } else {
        // Se a senha não foi fornecida, mantém a senha atual
        $sql = "SELECT senha FROM usuarios WHERE id=$id";
        $result = $conexao->query($sql);
        $user_data = $result->fetch_assoc();
        $senha_criptografada = $user_data['senha'];
    }

    $sqlUpdate = "UPDATE usuarios 
                  SET nome='$nome', senha='$senha_criptografada', email='$email', telefone='$telefone', 
                      data_nascimento='$data_nascimento', cidade='$cidade', estado='$estado', endereco='$endereco'
                  WHERE id=$id";

    $result = $conexao->query($sqlUpdate);

    if ($result) {
        header('Location: ../paginadm/ADM.php');
    } else {
        echo "Erro ao atualizar o usuário.";
    }
}
?>
