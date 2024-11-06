<?php
 session_start();
 include_once('../config.php');
 
 // Verifica se a conexão foi bem-sucedida
 if (!$conexao) {
     die("Conexão falhou: " . mysqli_connect_error());
 }
 
 // Verifica se um ID foi passado
 if (isset($_POST['id'])) {
     $id = intval($_POST['id']);
     
     // Exclui registros na tabela 'status' associados ao produto
     $delete_status_query = "DELETE FROM status WHERE id_produto = $id";
     mysqli_query($conexao, $delete_status_query);
     
     // Exclui o produto da tabela 'produtos'
     $delete_query = "DELETE FROM produtos WHERE id = $id";
     if (mysqli_query($conexao, $delete_query)) {
         // Redireciona para a lista de produtos
         header("Location: lista.php");
         exit();
     } else {
         die("Erro ao excluir o produto: " . mysqli_error($conexao));
     }
 } else {
     die("ID do produto não fornecido.");
 }
 
?>