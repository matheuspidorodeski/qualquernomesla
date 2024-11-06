<?php
session_start();
include_once('../config.php');

// Verifica se o usuário está logado e se o ID do cliente foi definido na sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    // Caso contrário, redireciona para a página de login ou exibe uma mensagem de erro
    header('Location: ../login/login.php');
    exit();
}
// Testa a conexão
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

function listarItensPorCategoria($conexao, $categoria_id) {
    // Prepara a consulta SQL
    $sql = "SELECT * FROM produtos WHERE categoria_id = ?";
    
    // Prepara a declaração
    $stmt = $conexao->prepare($sql);
    
    // Vincula o parâmetro
    $stmt->bind_param("i", $categoria_id);
    
    // Executa a declaração
    $stmt->execute();
    
    // Obtém o resultado
    $result = $stmt->get_result();
    
    return $result; // Retorna o resultado
}

// Obtém o ID da categoria a partir da URL
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

// Chama a função para listar produtos da categoria selecionada
$result = listarItensPorCategoria($conexao, $categoria_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .container2 {
            margin-top: 20px;
        }
        .header2 h1 {
            text-align: center;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .product {
            text-align: center;
            margin: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            width: 200px;
            border-radius: 5px;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<div class="container2">
    <div class="header2">
        <h1>PODE SER DE SEU INTERESSE</h1>
    </div>

    <button class="nav-button left" onclick="scrollLeft()">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div class="product-grid" id="product-grid">
        <?php
        // Verifica se a consulta retornou resultados
        if ($result && $result->num_rows > 0) {
            // Loop através dos resultados e exibe cada produto
            while ($row = $result->fetch_assoc()) {
                echo '<a class="product" href="paginadevenda.php?id=' . $row['id'] . '">';
                echo '<img alt="' . htmlspecialchars($row['nome']) . '" src="' . htmlspecialchars($row['imagem']) . '"/>';
                echo '<h2>' . htmlspecialchars($row['nome']) . '</h2>';
                echo '<div class="price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</div>';
                echo '<div class="discount">no PIX com 15% desconto</div>';
                echo '<div class="installments">em até 12x de R$ ' . number_format($row['preco'] / 12, 2, ',', '.') . ' sem juros no cartão</div>';
                echo '</a>';
            }
        } else {
            echo '<p>Nenhum produto encontrado.</p>';
        }
        ?>
    </div>
</div>

</body>
</html>
