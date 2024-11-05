<?php
session_start();
if (isset($_POST['submit'])) {
    include_once('../config.php');
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $imagem = $_POST['imagem'];
    $categoria_id = $_POST['categoria_id'];
    $imagemdescricao = $_POST['imagemdescricao'];
    $segundaimagemdesc = $_POST['segundaimagemdesc'];
    $segundadescricao = $_POST['segundadescricao'];
    // Exibe os dados recebidos para depuração
    var_dump($_POST);

    // Corrigido: Removida a vírgula extra
    $query = "INSERT INTO produtos (nome, preco, descricao, imagem, categoria_id, imagemdescricao, segundaimagemdesc, segundadescricao) VALUES ('$nome', '$preco', '$descricao', '$imagem', '$categoria_id', '$imagemdescricao', '$segundaimagemdesc', '$segundadescricao')";
    $result = mysqli_query($conexao, $query);

    // Verifica se a inserção foi bem-sucedida
    if ($result) {
        header('Location: lista.php');
    } else {
        echo "Erro ao cadastrar produto: " . mysqli_error($conexao);
    }
}
?>
