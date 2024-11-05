<?php
session_start();
include_once('../config.php');



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

    echo "<h2>Produtos no Carrinho:</h2>";
    $total = 0;

    foreach ($_SESSION['carrinho'] as $id => $quantidade) {
        $query = "SELECT * FROM produtos WHERE id = $id";
        $resultado = $conexao->query($query);
        
        if ($resultado && $resultado->num_rows > 0) {
            $produto = $resultado->fetch_assoc();
            $subtotal = $produto['preco'] * $quantidade;
            $total += $subtotal;

            echo "<div class='cart-item'>";
            echo "<h3>" . htmlspecialchars($produto['nome']) . "</h3>";
            echo "<img src='" . htmlspecialchars($produto['imagem']) . "' alt='" . htmlspecialchars($produto['nome']) . "' class='product-image'><br>";
            echo "<p>Preço: R$ " . number_format($produto['preco'], 2, ',', '.') . "</p>";
            echo "<p>Quantidade: " . $quantidade . "</p>";
            echo "<p>Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "</p>";
            echo "<a href='carrinho.php?acao=remove&id=" . $id . "' class='btn btn-danger'><i class='fas fa-trash'></i> Excluir</a> ";
            echo "<a href='carrinho.php?acao=increment&id=" . $id . "' class='btn btn-secondary'><i class='fas fa-plus'></i> Aumentar</a> ";
            echo "<a href='carrinho.php?acao=decrement&id=" . $id . "' class='btn btn-secondary'><i class='fas fa-minus'></i> Diminuir</a>";
            echo "</div>";
        } else {
            echo "<p>Produto com ID $id não encontrado.</p>";
        }
    }

    echo "<h3>Total: R$ " . number_format($total, 2, ',', '.') . "</h3>";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .cart-item {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .product-image {
            width: 100px;
            height: auto;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #343a40;
            color: white;
        }
        .btn-danger, .btn-secondary {
            margin-top: 10px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Meu Carrinho de Compras</h1>
    </header>

    <main class="container mt-5">
        <?php exibirCarrinho(); ?>
        
        <?php if (!empty($_SESSION['carrinho'])): ?>
            <div class="alert alert-info" role="alert">
                <h3>Frete Grátis!</h3>
                <p>Oferecemos frete grátis para todo o Brasil.</p>
            </div>
            
            <form action="../compra/compra.php" method="POST">
                <button type="submit" class="btn btn-success" style="margin-top: 20px;">Finalizar Compra</button>
            </form>
        <?php endif; ?>
        
        <a href="../dashboard/dashboard.php" class="btn btn-primary" style="margin-top: 20px;">Voltar à Loja</a>
    </main>

    <footer>
        <p>&copy; 2023 Loja Exemplo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
