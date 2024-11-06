<?php
// Configuração do banco de dados
include_once('../config.php');

// Consulta para obter os dados do relatório de vendas, incluindo parcelas
$sql = "SELECT rv.id, rv.id_produto, rv.nome, rv.valor, rv.quantidade, 
               COALESCE(c.metodo_pagamento, 'Não informado') AS metodo_pagamento, 
               rv.imagem, c.parcelas 
        FROM relatorio_venda rv 
        LEFT JOIN compras c ON rv.id_produto = c.id_produto"; // Modifiquei para usar o id_produto na junção

$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #b700ff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <h1>Relatório de Vendas</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Produto</th>
                <th>Nome</th>
                <th>Valor</th>
                <th>Quantidade</th>
                <th>Método de Pagamento</th>
                <th>Imagem</th>
                <th>Parcelas</th> <!-- Nova coluna para exibir as parcelas -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Exibe os dados da tabela
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['id_produto']}</td>
                        <td>{$row['nome']}</td>
                        <td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>
                        <td>{$row['quantidade']}</td>
                        <td>{$row['metodo_pagamento']}</td>
                        <td><img src='{$row['imagem']}' alt='{$row['nome']}' style='width: 50px; height: auto;'></td>
                        <td>" . ($row['parcelas'] ? $row['parcelas'] : 'À vista') . "</td> <!-- Exibe as parcelas ou 'À vista' -->
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Nenhuma venda encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
