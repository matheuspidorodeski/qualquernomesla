<?php
 session_start();
// Inclua seu arquivo de configuração do banco de dados
include_once('../config.php');

// Verifica se a conexão foi bem-sucedida
if (!$conexao) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Verifica se um ID foi passado
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Consulta para excluir o produto
    $delete_query = "DELETE FROM produtos WHERE id = $id";

    if (mysqli_query($conexao, $delete_query)) {
        // Redireciona de volta para a lista de produtos
        header("Location: lista.php");
        exit();
    } else {
        die("Erro ao excluir o produto: " . mysqli_error($conexao));
    }
} else {
    die("ID do produto não fornecido.");
}
?>