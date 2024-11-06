<?php 
session_start();
include_once('../config.php');

// Verifica se o usuário está logado e se o ID do cliente foi definido na sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    header('Location: ../login/login.php');
    exit();
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

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

// Removendo um item do carrinho
if (isset($_GET['acao']) && $_GET['acao'] == 'remove' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['carrinho'][$id]);
}

// Incrementando a quantidade
if (isset($_GET['acao']) && $_GET['acao'] == 'increment' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    }
}

// Decrementando a quantidade
if (isset($_GET['acao']) && $_GET['acao'] == 'decrement' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id]) && $_SESSION['carrinho'][$id] > 1) {
        $_SESSION['carrinho'][$id]--;
    } elseif (isset($_SESSION['carrinho'][$id]) && $_SESSION['carrinho'][$id] == 1) {
        unset($_SESSION['carrinho'][$id]);
    }
}

function exibirCarrinho() {
    global $conexao;
    
    if (empty($_SESSION['carrinho'])) {
        echo "<p>Seu carrinho está vazio.</p>";
        return;
    }

    echo "<div class='cart-container'>";
    $total = 0;

    foreach ($_SESSION['carrinho'] as $id => $quantidade) {
        $query = "SELECT * FROM produtos WHERE id = $id";
        $resultado = $conexao->query($query);
        
        if ($resultado && $resultado->num_rows > 0) {
            $produto = $resultado->fetch_assoc();
            $subtotal = $produto['preco'] * $quantidade;
            $total += $subtotal;

            echo "<div class='cart-item'>";
            echo "<img src='" . htmlspecialchars($produto['imagem']) . "' alt='" . htmlspecialchars($produto['nome']) . "' class='product-image'>";
            echo "<div class='item-details'>";
            echo "<h3>" . htmlspecialchars($produto['nome']) . "</h3>";
            echo "<p>R$ " . number_format($produto['preco'], 2, ',', '.') . "</p>";
            echo "<p>Quantidade: " . $quantidade . "</p>";
            echo "<p>Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "</p>";
            echo "<div class='item-actions'>";
            echo "<a href='carrinho.php?acao=remove&id=" . $id . "' class='btn-remove'>Remover</a>";
            echo "<a href='carrinho.php?acao=increment&id=" . $id . "' class='btn-increment'>+</a>";
            echo "<a href='carrinho.php?acao=decrement&id=" . $id . "' class='btn-decrement'>-</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>Produto com ID $id não encontrado.</p>";
        }
    }

    echo "<div class='cart-total'>";
    echo "<h3>Total: R$ " . number_format($total, 2, ',', '.') . "</h3>";
    echo "</div>";
    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="carrinho.css">
</head>
<body>

    <header>
        <h1>Meu Carrinho de Compras</h1>
    </header>

    <main class="container mt-5">
        <?php exibirCarrinho(); ?>

        <?php if (!empty($_SESSION['carrinho'])): ?>
            <div class="alert">
                <h3>Frete Grátis!</h3>
                <p>Oferecemos frete grátis para todo o Brasil.</p>
            </div>
            <form action="../compra/compra.php" method="POST">
                <button type="submit" class="btn btn-finalizar">Finalizar Compra</button>
            </form>
        <?php endif; ?>
        
        <a href="../dashboard/dashboard.php" class="btn btn-voltar">Voltar à Loja</a>
    </main>

    <footer>
        <p>&copy; 2023 Loja Exemplo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
