<?php
session_start();
include_once('../config.php');

// Função para adicionar ou remover unidades do estoque
if (isset($_POST['atualizar_estoque'])) {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $acao = $_POST['acao'];

    if ($acao == 'adicionar') {
        $stmt = $conexao->prepare("UPDATE estoque SET quantidade = quantidade + ? WHERE id_produto = ?");
    } else {
        $stmt = $conexao->prepare("UPDATE estoque SET quantidade = GREATEST(quantidade - ?, 0) WHERE id_produto = ?");
    }

    $stmt->bind_param("ii", $quantidade, $id_produto);
    $stmt->execute();
}

// Função para buscar produtos no estoque com join na tabela produtos
$pesquisa = '';
if (isset($_GET['search'])) {
    $pesquisa = $_GET['search'];
    // Verificar se a pesquisa é por ID ou nome
    if (is_numeric($pesquisa)) {
        // Pesquisa por ID
        $stmt = $conexao->prepare("
            SELECT estoque.id, estoque.id_produto, produtos.nome, estoque.quantidade, estoque.data_insercao, estoque.data_venda 
            FROM estoque 
            JOIN produtos ON estoque.id_produto = produtos.id 
            WHERE estoque.id_produto = ?");
        $stmt->bind_param("i", $pesquisa);
    } else {
        // Pesquisa por nome
        $stmt = $conexao->prepare("
            SELECT estoque.id, estoque.id_produto, produtos.nome, estoque.quantidade, estoque.data_insercao, estoque.data_venda 
            FROM estoque 
            JOIN produtos ON estoque.id_produto = produtos.id 
            WHERE produtos.nome LIKE ?");
        $searchTerm = '%' . $pesquisa . '%';
        $stmt->bind_param("s", $searchTerm);
    }
} else {
    // Consulta sem filtro
    $stmt = $conexao->prepare("
        SELECT estoque.id, estoque.id_produto, produtos.nome, estoque.quantidade, estoque.data_insercao, estoque.data_venda 
        FROM estoque 
        JOIN produtos ON estoque.id_produto = produtos.id");
}

$stmt->execute();
$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtos</title>
    <link rel="stylesheet" href="estoque.css">
</head>
<body>
<div class="container">
    <h1>Gerenciamento de Produtos</h1>

    <!-- Botão para acessar o Relatório de Vendas -->
    <div class="btn-relatorio">
        <a href="relatorio_venda.php" class="btn btn-primary">Acessar Relatório de Vendas</a>
    </div>

    <!-- Barra de pesquisa -->
    <div class="search-bar">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Buscar por ID ou Nome" value="<?php echo htmlspecialchars($pesquisa); ?>">
            <button type="submit">Pesquisar</button>
        </form>
    </div>

    <!-- Tabela de produtos -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Produto</th>
                <th>Nome</th>
                <th>Data de Inserção</th>
                <th>Quantidade em Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($produto = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $produto['id']; ?></td>
                    <td><?php echo $produto['id_produto']; ?></td>
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td><?php echo $produto['data_insercao']; ?></td>
                    <td><?php echo $produto['quantidade']; ?></td>
                    <td class="actions">
                        <!-- Formulário para adicionar unidades -->
                        <form action="" method="POST">
                            <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto']; ?>">
                            <input type="hidden" name="acao" value="adicionar">
                            <input type="number" name="quantidade" min="1" placeholder="Quantidade" required>
                            <button type="submit" name="atualizar_estoque">Adicionar</button>
                        </form>

                        <!-- Formulário para remover unidades -->
                        <form action="" method="POST">
                            <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto']; ?>">
                            <input type="hidden" name="acao" value="remover">
                            <input type="number" name="quantidade" min="1" placeholder="Quantidade" required>
                            <button type="submit" name="atualizar_estoque">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
