<?php
session_start();
include_once('../config.php');


// Adicionando um item ao carrinho
if (isset($_GET['acao']) && $_GET['acao'] == 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }
    header("Location: carrinho.php");
    exit();
}

// Calculando o total do carrinho
$total = 0;
$produtosCarrinho = [];

// Verifica se o carrinho não está vazio
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $id => $quantidade) {
        // Usando consultas preparadas para evitar SQL Injection
        $stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado && $resultado->num_rows > 0) {
            $produto = $resultado->fetch_assoc();
            $subtotal = $produto['preco'] * $quantidade; // Certifique-se de que 'preco' é o nome correto da coluna
            $total += $subtotal;
            $produtosCarrinho[] = [
                'id' => $produto['id'], // Adicionando o ID do produto
                'nome' => htmlspecialchars($produto['nome']),
                'imagem' => htmlspecialchars($produto['imagem']),
                'valor' => number_format($produto['preco'], 2, ',', '.'), // Formatação do preço
                'quantidade' => $quantidade,
                'subtotal' => number_format($subtotal, 2, ',', '.')
            ];
        } else {
            echo "<p>Produto com ID $id não encontrado.</p>";
        }
    }
} else {
    echo "<p>Seu carrinho está vazio.</p>";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">

    <h2>Confirmar compra</h2>

    <?php if (!empty($produtosCarrinho)): ?>
        <?php foreach ($produtosCarrinho as $produto): ?>
            <div class="mb-4">
                <h3><?php echo $produto['nome']; ?></h3>
                <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" style="width:100px;height:auto;"><br>
                <p>Preço: R$ <?php echo $produto['valor']; ?></p>
                <p>Quantidade: <?php echo $produto['quantidade']; ?></p>
                <p>Subtotal: R$ <?php echo $produto['subtotal']; ?></p>
            </div>
            <div class="alert alert-info mt-4">
        <strong>Frete Grátis!</strong> Temos frete grátis para todo o Brasil.
    </div>
        <?php endforeach; ?>
        <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
    <?php endif; ?>
<!-- Campo oculto para enviar os IDs dos produtos e o total -->
<?php foreach ($produtosCarrinho as $produto): ?>
    <input type="hidden" name="produtos[]" value="<?php echo $produto['id']; ?>">
<?php endforeach; ?>
<input type="hidden" name="total" value="<?php echo $total; ?>"> <!-- Enviando o total -->
<form action="pagamento.php" method="POST">
    <!-- Campo oculto para enviar os IDs dos produtos e o total -->
    <?php foreach ($produtosCarrinho as $produto): ?>
        <input type="hidden" name="produtos[]" value="<?php echo $produto['id']; ?>">
    <?php endforeach; ?>
    <input type="hidden" name="total" value="<?php echo $total; ?>"> <!-- Enviando o total -->

    <button type="submit" class="btn btn-primary">Continuar para o Pagamento</button>
</form>
</form>
</div>
</body>
</html>