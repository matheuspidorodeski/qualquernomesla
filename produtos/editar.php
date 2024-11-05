<?php
session_start();
// Inclua seu arquivo de configuração do banco de dados
include_once('../config.php');

// Verifica se a conexão foi bem-sucedida
if (!$conexao) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Verifica se o ID do produto foi enviado
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    // Consulta para obter os dados do produto
    $result = mysqli_query($conexao, "SELECT * FROM produtos WHERE id = $id");
    // Verifica se a consulta foi bem-sucedida
    if (!$result) {
        die("Erro na consulta: " . mysqli_error($conexao));
    }
    // Obtém os dados do produto
    $produto = mysqli_fetch_assoc($result);
} else {
    die("ID do produto não especificado.");
}

// Verifica se o formulário de edição foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $imagem = $_POST['imagem']; // Usando URL da imagem
    $categoria_id = $_POST['categoria_id'];
    $imagemdescricao = $_POST['imagemdescricao'];
    $segundaimagemdesc = $_POST['segundaimagemdesc'];
    $segundadescricao = $_POST['segundadescricao'];

    // Atualiza o produto no banco de dados
    $updateQuery = "UPDATE produtos SET nome = '$nome', preco = '$preco', descricao = '$descricao', imagem = '$imagem', 
                    categoria_id = '$categoria_id', imagemdescricao = '$imagemdescricao', 
                    segundaimagemdesc = '$segundaimagemdesc', segundadescricao = '$segundadescricao' 
                    WHERE id = $id";
    
    if (mysqli_query($conexao, $updateQuery)) {
        $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
        header('Location: lista.php');
        exit();
    } else {
        echo "Erro ao atualizar produto: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
</head>
<body>
    <h1>Editar Produto</h1>

    <form action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($produto['id']); ?>">

        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
        <br>
        <label for="preco">Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
        <br>
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
        <br>
        <label for="imagem">URL da Imagem:</label>
        <input type="text" name="imagem" value="<?php echo htmlspecialchars($produto['imagem']); ?>" required>
        <br>
        <label for="categoria_id">ID da Categoria:</label>
        <input type="number" name="categoria_id" value="<?php echo htmlspecialchars($produto['categoria_id']); ?>" required>
        <br>
        <label for="imagemdescricao">Imagem Descrição:</label>
        <input type="text" name="imagemdescricao" value="<?php echo htmlspecialchars($produto['imagemdescricao']); ?>" required>
        <br>
        <label for="segundaimagemdesc">Segunda Imagem Descrição:</label>
        <input type="text" name="segundaimagemdesc" value="<?php echo htmlspecialchars($produto['segundaimagemdesc']); ?>" required>
        <br>
        <label for="segundadescricao">Segunda Descrição:</label>
        <textarea name="segundadescricao" required><?php echo htmlspecialchars($produto['segundadescricao']); ?></textarea>
        <br>
        <button type="submit" name="editar">Atualizar Produto</button>
    </form>
</body>
</html>
