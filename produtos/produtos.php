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
    $quantidade = $_POST['quantidade'];
    
    // Exibe os dados recebidos para depuração
    var_dump($_POST);

    // Corrigido: Removida a vírgula extra
    $query = "INSERT INTO produtos (nome, preco, descricao, imagem, categoria_id, imagemdescricao, segundaimagemdesc, segundadescricao) 
              VALUES ('$nome', '$preco', '$descricao', '$imagem', '$categoria_id', '$imagemdescricao', '$segundaimagemdesc', '$segundadescricao')";
    
    // Inserir produto na tabela de produtos
    $result = mysqli_query($conexao, $query);

    if ($result) {
        // Obter o ID do produto inserido
        $id_produto = mysqli_insert_id($conexao);

        // Preparar a consulta para o estoque
        $query_estoque = "INSERT INTO estoque (id_produto, quantidade, data_insercao) VALUES (?, ?, NOW())";
        $stmt_estoque = $conexao->prepare($query_estoque);
        $stmt_estoque->bind_param("ii", $id_produto, $quantidade);

        if ($stmt_estoque->execute()) {
            header('Location: lista.php');
        } else {
            echo "Erro ao cadastrar produto no estoque: " . $conexao->error;
        }
    } else {
        echo "Erro ao cadastrar produto: " . $conexao->error;
    }
}
?>
