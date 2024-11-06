<?php 
session_start();
include_once('../config.php');

// Verifica se o usuário está logado e se o ID do cliente foi definido na sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    header('Location: ../login/login.php');
    exit();
}

// Calcula o total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $id => $quantidade) {
    $stmt = $conexao->prepare("SELECT preco, nome, imagem FROM produtos WHERE id = ?");
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
    // Obtém o número de parcelas ou define como 1 se não foi selecionado
    $parcelas = ($metodo_pagamento === 'credito' && isset($_POST['parcelas'])) ? (int)$_POST['parcelas'] : 1;

    // Aplica desconto de 15% se o método de pagamento for Pix
    if ($metodo_pagamento === 'pix') {
        $total *= 0.85; // Aplica 15% de desconto
    }

    // Insere o pedido na tabela "compras"
    $data_compra = date('Y-m-d H:i:s'); // Adiciona a data da compra
    $id_cliente = $_SESSION['id_cliente']; // Obtém o ID do cliente da sessão

    foreach ($_SESSION['carrinho'] as $id => $quantidade) {
        // Busca os dados do produto
        $stmt = $conexao->prepare("SELECT nome, imagem, preco FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produto = $resultado->fetch_assoc();

        // Verifica a quantidade disponível no estoque (tabela estoque)
        $stmt_estoque = $conexao->prepare("SELECT quantidade FROM estoque WHERE id_produto = ?");
        $stmt_estoque->bind_param("i", $id);
        $stmt_estoque->execute();
        $resultado_estoque = $stmt_estoque->get_result();
        
        if ($resultado_estoque && $resultado_estoque->num_rows > 0) {
            $estoque = $resultado_estoque->fetch_assoc();
            // Verifica se a quantidade no estoque é suficiente
            if ($quantidade > $estoque['quantidade']) {
                // Caso a quantidade no estoque seja insuficiente, exibe uma mensagem de erro e interrompe o processo
                echo "<script>
                        alert('Quantidade insuficiente de " . htmlspecialchars($produto['nome']) . " no estoque.');
                        window.location.href = '../carrinho/carrinho.php'; // Altere para a página do carrinho
                      </script>";
                exit;
            }

            // Atualiza o estoque subtraindo a quantidade comprada
            $novo_estoque = $estoque['quantidade'] - $quantidade;
            $stmt_atualiza_estoque = $conexao->prepare("UPDATE estoque SET quantidade = ? WHERE id_produto = ?");
            $stmt_atualiza_estoque->bind_param("ii", $novo_estoque, $id);

            if (!$stmt_atualiza_estoque->execute()) {
                echo "Erro ao atualizar o estoque. Tente novamente.";
                exit;
            }
        } else {
            echo "Erro ao verificar o estoque do produto.";
            exit;
        }

        // Insere cada item do carrinho na tabela "compras"
        $stmt_compras = $conexao->prepare("INSERT INTO compras (valor, metodo_pagamento, data_compra, id_produto, quantidade, id_cliente, parcelas) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_compras->bind_param("dsiiiii", $total, $metodo_pagamento, $data_compra, $id, $quantidade, $id_cliente, $parcelas);

        if (!$stmt_compras->execute()) {
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

        // Insere na tabela "relatorio_venda"
        $stmt_relatorio = $conexao->prepare("INSERT INTO relatorio_venda (id_produto, nome, valor, quantidade, metodo_pagamento, imagem, data_venda) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_relatorio->bind_param("isdiiss", $id, $produto['nome'], $produto['preco'], $quantidade, $metodo_pagamento, $produto['imagem'], $data_compra);

        if (!$stmt_relatorio->execute()) {
            echo "Erro ao inserir no relatório de vendas. Por favor, tente novamente.";
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

        <!-- Exibe as opções de parcelamento quando "Cartão de Crédito" for selecionado -->
        <div id="parcelas" style="display:none;">
            <label for="parcelasSelect">Número de Parcelas:</label>
            <select name="parcelas" id="parcelasSelect" class="form-control" onchange="calcularParcelas()">
                <option value="1">1x</option>
                <option value="2">2x</option>
                <option value="3">3x</option>
                <option value="4">4x</option>
                <option value="5">5x</option>
                <option value="6">6x</option>
                <option value="7">7x</option>
                <option value="8">8x</option>
                <option value="9">9x</option>
                <option value="10">10x</option>
                <option value="11">11x</option>
                <option value="12">12x</option>
            </select>
        </div>

        <div id="valorParcelado" style="display:none; margin-top: 20px;">
            <h4>Valor de cada parcela: R$ <span id="valorParcelas"></span></h4>
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

        <button type="submit" class="btn btn-primary">Finalizar Compra</button>
    </form>

    <script>
    // Valor total original do carrinho
    const totalOriginal = <?php echo json_encode($total); ?>;

    function atualizarPagamento() {
        const metodoPagamento = document.getElementById("pagamento").value;
        const qrCodeDiv = document.getElementById("qrCode");
        const totalDescontoDiv = document.getElementById("totalComDesconto");
        const totalDescontadoSpan = document.getElementById("totalDescontado");
        const parcelasDiv = document.getElementById("parcelas");
        const valorParceladoDiv = document.getElementById("valorParcelado");

        if (metodoPagamento === "pix") {
            // Calcula o desconto de 15%
            const totalComDesconto = totalOriginal * 0.85;

            // Exibe o QR code e o total com desconto
            qrCodeDiv.style.display = "block";
            totalDescontoDiv.style.display = "block";
            totalDescontadoSpan.innerText = totalComDesconto.toFixed(2).replace(".", ",");
            parcelasDiv.style.display = "none";
            valorParceladoDiv.style.display = "none";
        } else if (metodoPagamento === "credito") {
            parcelasDiv.style.display = "block";
            qrCodeDiv.style.display = "none";
            totalDescontoDiv.style.display = "none";
        } else {
            parcelasDiv.style.display = "none";
            qrCodeDiv.style.display = "none";
            totalDescontoDiv.style.display = "none";
        }
    }

    function calcularParcelas() {
        const parcelas = document.getElementById("parcelasSelect").value;
        const valorParcelas = totalOriginal / parcelas;
        document.getElementById("valorParcelas").innerText = valorParcelas.toFixed(2).replace(".", ",");
        document.getElementById("valorParcelado").style.display = "block";
    }
    </script>

</div>
</body>
</html>
