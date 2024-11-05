<?php
session_start();
include_once('../config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    header('Location: ../login/login.php');
    exit();
}

// Obtém o ID do cliente logado
$id_cliente = $_SESSION['id_cliente'];

// Busca os pedidos do cliente com o mesmo ID
$stmt = $conexao->prepare("SELECT c.id AS id_pedido, c.data_compra, c.quantidade, c.valor, p.nome AS item_nome 
                            FROM compras c 
                            JOIN produtos p ON c.id_produto = p.id 
                            WHERE c.id_cliente = ? 
                            ORDER BY c.data_compra DESC");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se há pedidos
if ($resultado->num_rows == 0) {
    echo "<p>Não há pedidos recentes.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Pedido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Status do Pedido</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Itens Comprados</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID do Pedido</th>
                        <th>Item</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Status</th> <!-- Coluna para o status -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($item['item_nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                            <td>R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
                            <td><span class="badge badge-warning">Preparando</span></td> <!-- Status do item -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Botão para voltar para a dashboard -->
            <a href="../dashboard/dashboard.php" class="btn btn-primary">Voltar para a Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
