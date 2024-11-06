<?php 
session_start();
include_once '../config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    header('Location: ../login/login.php');
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// Adicionar produto aos favoritos
if (isset($_POST['favoritar'])) {
    $id = (int)$_POST['id_produto'];
    $stmt = $conexao->prepare("INSERT INTO favoritos (id_produto, nome, valor, id_cliente, imagem) SELECT id, nome, preco, ?, imagem FROM produtos WHERE id = ?");
    $stmt->bind_param("ii", $id_cliente, $id);
    $stmt->execute();
}

// Remover produto dos favoritos
if (isset($_POST['remover'])) {
    $id = (int)$_POST['id_produto'];
    $stmt = $conexao->prepare("DELETE FROM favoritos WHERE id_produto = ? AND id_cliente = ?");
    $stmt->bind_param("ii", $id, $id_cliente);
    $stmt->execute();
}

// Obter lista de favoritos
$stmt = $conexao->prepare("SELECT * FROM favoritos WHERE id_cliente = ?");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$favoritos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos</title>
    <link rel="stylesheet" href="../styles/favoritos.css">
</head>
<body>
    <div class="container">
        <h1>Favoritos</h1>
        <a href="javascript:history.back()" class="btn-back">Voltar</a>

        <?php if ($favoritos->num_rows > 0): ?>
            <div class="favoritos-container">
                <?php while ($favorito = $favoritos->fetch_assoc()): ?>
                    <div class="favorito-item">
                        <a href="visualizar.php?id=<?php echo $favorito['id_produto']; ?>" class="favorito-link">
                            <img src="<?php echo htmlspecialchars($favorito['imagem']); ?>" alt="<?php echo htmlspecialchars($favorito['nome']); ?>" class="favorito-img">
                            <h2><?php echo htmlspecialchars($favorito['nome']); ?></h2>
                            <p class="valor">R$ <?php echo number_format($favorito['valor'], 2, ',', '.'); ?></p>
                        </a>
                        <form method="POST">
                            <input type="hidden" name="id_produto" value="<?php echo $favorito['id_produto']; ?>">
                            <button type="submit" name="remover" class="btn-remove">Remover dos Favoritos</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhum favorito encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>