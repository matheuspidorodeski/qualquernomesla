<?php
session_start();
include_once('../config.php');

// Verifica se o usuário está logado e se o ID do cliente foi definido na sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['id_cliente'])) {
    // Caso contrário, redireciona para a página de login ou exibe uma mensagem de erro
    header('Location: ../login/login.php');
    exit();
}

// Verifica se o usuário logado é administrador
$isAdmin = false; // Inicializa a variável que indica se o usuário é administrador.
if (isset($_SESSION['email'])) { // Se o email do usuário estiver setado na sessão
    $email = $_SESSION['email']; // Armazena o email do usuário em uma variável.
    $sql = "SELECT * FROM usuarios WHERE email = '$email'"; // Consulta o banco de dados para obter informações do usuário logado.
    $result = $conexao->query($sql); // Executa a consulta.

    if ($result && $result->num_rows > 0) { // Se a consulta retornou resultados
        $user = $result->fetch_assoc(); // Obtém os dados do usuário em um array associativo.
        $isAdmin = $user['e_admin'] >= 1; // Verifica se o usuário é administrador de nível 1, 2 ou 3.
    }
}

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Conta o número de produtos no carrinho
$numProdutos = count($_SESSION['carrinho']); // Conta quantos produtos estão no carrinho

// Define a mensagem do carrinho com base na contagem
if ($numProdutos === 1) {
    $carrinhoMensagem = "Carrinho - 1 produto";
} else {
    $carrinhoMensagem = "Carrinho - $numProdutos produtos";
}

// Consulta para obter todos os produtos
$sql = "SELECT * FROM produtos ORDER BY id DESC"; // Seleciona todos os produtos, ordenando por ID em ordem decrescente.
$result = $conexao->query($sql); // Executa a consulta.

// Define o modo padrão com base no cookie, se existir
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'dark-mode'; // Se o cookie 'theme' existir, usa seu valor; caso contrário, define como 'dark-mode'.
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a escala de visualização para dispositivos móveis -->
    <title>Assistência Técnica</title> <!-- Título da página -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" /> <!-- Importa o Font Awesome para ícones -->
    <link rel="stylesheet" href="../styles/dash.css"> <!-- Importa o CSS da dashboard -->
</head>
<body class="<?php echo $theme; ?>"> <!-- Define a classe do body com base no cookie -->
<header class="header">
    <div class="logo">
        <a href="../dashboard/dashboard.php">
            <img src="../styles/img/tecprok.png" alt="Logo TecPro" style="height: 100px;"> <!-- Exibe o logo da empresa -->
        </a>
    </div>

    <nav class="menu">
        <div class="menu-buttons"> <!-- Botões do menu -->
            <a href="https://wa.me/+554288697902" class="support-button">
                <i class="fas fa-headset"></i> ATENDIMENTO AO CLIENTE <!-- Link para atendimento ao cliente -->
            </a>
            <button class="favorites-button" onclick="window.location.href='../favoritos/favoritos.php'">
                <i class="fas fa-heart"></i> MEUS FAVORITOS <!-- Botão para favoritos -->
            </button>
            <button class="mode-button" id="mode-button" onclick="toggleMode()">
                <i class="fas fa-moon"></i> <!-- Ícone do botão de modo -->
                <span id="mode-text">MODO ESCURO</span> <!-- Texto do botão de modo -->
            </button>
            <button class="cart-button" onclick="window.location.href='../carrinho/carrinho.php'">
                <i class="fas fa-shopping-cart"></i> <?php echo $carrinhoMensagem; ?> <!-- Exibe a mensagem do carrinho -->
            </button>
        </div>
        <input type="text" class="search-bar" placeholder="Digite o que você procura..."> <!-- Barra de pesquisa -->
    </nav>
    
    <!-- Menu do usuário com as opções Login, Cadastro e Logout/Sair -->
    <div class="user-menu-container"> 
        <button class="menu-button"><i class="fas fa-user"></i></button> <!-- Botão de menu do usuário -->
        <div class="dropdown-menu"> <!-- Menu dropdown para o usuário -->
            <?php if (!isset($_SESSION['email'])): ?> <!-- Se o usuário não estiver logado -->
                <button onclick="window.location.href='../login/login.php'">Login</button> <!-- Botão de login -->
                <button onclick="window.location.href='../login/cadastro.php'">Cadastro</button> <!-- Botão de cadastro -->
            <?php else: ?> <!-- Se o usuário estiver logado -->
                <!-- Botão "Gerenciar Site" visível apenas para administradores -->
                <?php if ($isAdmin): ?> <!-- Se o usuário for administrador -->
                    <button onclick="window.location.href='../paginadm/ADM.php'" class="btn btn-secondary">
                        Gerenciar Site <!-- Botão para gerenciar o site -->
                    </button>
                <?php endif; ?>
                <form method="POST" action="../login/logout.php"> <!-- Formulário para logout -->
                    <button type="submit" name="logout">Sair</button> <!-- Botão para sair -->
                </form>
                <form method="POST" action="../compra/status.php">
                <button type="submit" name="status">staus</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="container"> <!-- Container principal -->
    <div class="main-banner"> <!-- Banner principal -->
        <img alt="Main banner with promotional content and red birds" src="../styles/img/banner.jpg" height="400" width="1200"/> <!-- Imagem do banner -->
        <div class="text"> <!-- Texto do banner -->
            <h1>PROMOÇÃO TEC PRO</h1> <!-- Título da promoção -->
            <p>ESPECIAL DIA DA INAUGURAÇAO</p> <!-- Descrição da promoção -->
            <button>CONFIRA</button> <!-- Botão para conferir a promoção -->
        </div>
        
    </div>
    
    <div class="promo-section"> <!-- Seção de promoções -->
           <img alt="" src="../styles/img/pc.png" /> <!-- Imagem promocional -->
           <img alt="" src="../styles/img/promo.webp" /> <!-- Imagem promocional -->
           <img alt="" src="../styles/img/promo2.webp" /> <!-- Imagem promocional -->
       </div>
    
    <div class="header2">
        <h1>PODE SER DE SEU INTERESSE</h1> <!-- Cabeçalho para produtos de interesse -->
    </div>

    <div class="product-navigation"> <!-- Contêiner dos botões de navegação -->
        <button class="scroll-button" onclick="scrollLeft()"><i class="fa-solid fa-arrow-left"></i></button> <!-- Botão para mover para a esquerda -->
        <div class="product-grid" id="product-grid"> <!-- Grid para exibição de produtos -->
            <?php
            // Verifica se a consulta retornou resultados
            if ($result && $result->num_rows > 0) { // Se a consulta retornou resultados
                // Loop através dos resultados e exibe cada produto
                while ($row = $result->fetch_assoc()) { // Itera sobre cada produto
                    echo '<a class="product" href="paginadevenda.php?id=' . $row['id'] . '">'; // Link para a página de venda do produto
                    echo '<img alt="' . htmlspecialchars($row['nome']) . '" src="' . htmlspecialchars($row['imagem']) . '"/>'; // Exibe a imagem do produto
                    echo '<h2>' . htmlspecialchars($row['nome']) . '</h2>'; // Exibe o nome do produto
                    echo '<div class="price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</div>'; // Exibe o preço formatado
                    echo '<div class="discount">no PIX com 15% desconto</div>'; // Exibe a informação de desconto
                    echo '<div class="installments">em até 12x de R$ ' . number_format($row['preco'] / 12, 2, ',', '.') . ' sem juros no cartão</div>'; // Exibe a informação de parcelamento
                    
                    echo '</a>'; // Fecha o link do produto
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>'; // Mensagem se não houver produtos
            }
            ?>
        </div>
        <button class="scroll-button" onclick="scrollRight()"><i class="fa-solid fa-arrow-right"></i></button> <!-- Botão para mover para a direita -->
    </div>
</div>

<script src="../scripts/dash.js"></script> <!-- Importa o JavaScript para a dashboard -->
</body>
<footer class="copy">
        <p>&copy; 2023 Loja Exemplo. Todos os direitos reservados.</p>
        
    </footer>
</html>
