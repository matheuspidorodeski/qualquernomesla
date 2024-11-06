<?php
session_start();
// Incluir o arquivo de conexão
include_once '../config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    header('Location: ../login/login.php'); // Redireciona para o login se não estiver logado
    exit();
}

// Obtém o ID do cliente logado
$id_cliente = $_SESSION['id_cliente'];

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Obtém o ID do produto da URL

    // Consulta para buscar o produto pelo ID
    $stmt = $conexao->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->bind_param('i', $id); // 'i' indica que o parâmetro é um inteiro
    $stmt->execute();
    $resultado = $stmt->get_result();
    $produto = $resultado->fetch_assoc();

    // Verifica se o produto foi encontrado
    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }
} else {
    echo "ID do produto não especificado.";
    exit;
}

// Adiciona o produto aos favoritos
if (isset($_POST['favoritar'])) {
    $stmt_favoritar = $conexao->prepare("INSERT INTO favoritos (cliente_id, produto_id) VALUES (?, ?)");
    $stmt_favoritar->bind_param("ii", $id_cliente, $id);
    if ($stmt_favoritar->execute()) {
        echo "<p>Produto adicionado aos favoritos!</p>";
    } else {
        echo "<p>Erro ao adicionar aos favoritos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?></title>
    <link rel="stylesheet" href="..styles/paginadevenda.css"> <!-- Link para o CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h1>Loja Exemplo</h1>
    </header>

    <main class="container">
        <div class="produto">
            <div class="imagem-produto">
                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
            </div>
            <div class="detalhes-produto">
                <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
                <p class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p class="descricao"><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>

                <!-- Formulário para adicionar o produto ao carrinho -->
                <form action="../carrinho/carrinho.php" method="GET">
                    <input type="hidden" name="acao" value="add">
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                    <button type="submit" class="btn">Adicionar ao Carrinho</button>
                </form>   
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Loja Exemplo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
