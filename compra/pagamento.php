<?php
session_start();
include_once('../config.php');

// Verifica se o usuário está logado e se o ID do cliente foi definido na sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    // Caso contrário, redireciona para a página de login ou exibe uma mensagem de erro
    header('Location: ../login/login.php');
    exit();
}

// Calcula o total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $id => $quantidade) {
    $stmt = $conexao->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado && $resultado->num_rows > 0) {
        $produto = $resultado->fetch_assoc();
        $subtotal = $produto['preco'] * $quantidade;
        $total += $subtotal;
    }
}

// Processa o formulário de pagamento se enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodo_pagamento'])) {
    $metodo_pagamento = $_POST['metodo_pagamento'];
    
    // Aplica desconto de 15% se o método de pagamento for Pix
    if ($metodo_pagamento === 'pix') {
        $total *= 0.85; // Aplica 15% de desconto
    }

    // Insere o pedido na tabela "compras"
    $data_compra = date('Y-m-d H:i:s'); // Adiciona a data da compra
    $id_cliente = $_SESSION['id_cliente']; // Obtém o ID do cliente da sessão
    $id_usuario_logado = $_SESSION['id_usuario']; // Obtém o ID do usuário logado da sessão

    foreach ($_SESSION['carrinho'] as $id => $quantidade) {
        // Insere cada item do carrinho na tabela "compras"
        $stmt = $conexao->prepare("INSERT INTO compras (valor, metodo_pagamento, data_compra, id_produto, quantidade, id_cliente) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dsiiii", $total, $metodo_pagamento, $data_compra, $id, $quantidade, $id_cliente);

        if (!$stmt->execute()) {
            echo "Erro ao inserir o item no pedido. Por favor, tente novamente.";
            exit;
        }

        // Obtém o ID da compra recém-inserida
        $id_compra = $conexao->insert_id;

        // Insere na tabela "status"
        $stmt_status = $conexao->prepare("INSERT INTO status (id_cliente, id_compra, id_produto) VALUES (?, ?, ?)");
        $stmt_status->bind_param("iii", $id_cliente, $id_compra, $id);

        if (!$stmt_status->execute()) {
            echo "Erro ao inserir o status do pedido. Por favor, tente novamente.";
            exit;
        }
    }

    // Exibe um popup de sucesso e redireciona
    echo "<script>
        alert('Compra realizada com sucesso! Total da compra: R$ " . number_format($total, 2, ',', '.') . "');
        window.location.href = 'status.php'; // Altere para a página desejada
    </script>";

    unset($_SESSION['carrinho']); // Limpa o carrinho após a compra
    exit; // Para garantir que o resto do script não seja executado
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

    <h2>Carrinho de Compras</h2>

    <?php if (!empty($_SESSION['carrinho'])): ?>
        <?php foreach ($_SESSION['carrinho'] as $id => $quantidade): ?>
            <?php
            $stmt = $conexao->prepare("SELECT nome, imagem, preco FROM produtos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $produto = $resultado->fetch_assoc();
            $subtotal = $produto['preco'] * $quantidade;
            ?>
            <div class="mb-4">
                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="width:100px;height:auto;"><br>
                <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p>Quantidade: <?php echo $quantidade; ?></p>
                <p>Subtotal: R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>
        <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
    <?php endif; ?>

    <h2>Selecionar Método de Pagamento</h2>
    <form method="POST" id="pagamentoForm">
        <div class="form-group">
            <label for="pagamento">Método de Pagamento:</label>
            <select name="metodo_pagamento" id="pagamento" class="form-control" required onchange="atualizarPagamento()">
                <option value="">Selecione um método</option>
                <option value="credito">Cartão de Crédito</option>
                <option value="boleto">Boleto</option>
                <option value="pix">Pix</option>
            </select>
        </div>

        <!-- Exibe o QR code quando "Pix" for selecionado -->
        <div id="qrCode" style="display:none; margin-top: 20px;">
            <h3>Pagamento via Pix</h3>
            <img src="https://codigosdebarrasbrasil.com.br/wp-content/uploads/2019/09/codigo_qr-300x300.png" alt="Código QR para pagamento" style="width: 150px; height: auto;">
        </div>

        <!-- Exibe o total com desconto -->
        <div id="totalComDesconto" style="display:none; margin-top: 20px;">
            <h4>Total com 15% de desconto: R$ <span id="totalDescontado"></span></h4>
        </div>

        <button type="submit" class="btn btn-primary">Finalizar compra</button>
    </form>

    <script>
    // Valor total original do carrinho
    const totalOriginal = <?php echo json_encode($total); ?>;

    function atualizarPagamento() {
        const metodoPagamento = document.getElementById("pagamento").value;
        const qrCodeDiv = document.getElementById("qrCode");
        const totalDescontoDiv = document.getElementById("totalComDesconto");
        const totalDescontadoSpan = document.getElementById("totalDescontado");

        if (metodoPagamento === "pix") {
            // Calcula o desconto de 15%
            const totalComDesconto = totalOriginal * 0.85;

            // Exibe o QR code e o total com desconto
            qrCodeDiv.style.display = "block";
            totalDescontoDiv.style.display = "block";
            totalDescontadoSpan.innerText = totalComDesconto.toFixed(2).replace(".", ",");
        } else {
            // Esconde o QR code e o total com desconto se não for Pix
            qrCodeDiv.style.display = "none";
            totalDescontoDiv.style.display = "none";
        }
    }
    </script>
</div>
</body>
</html>
