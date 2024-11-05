<?php
session_start();
include_once '../config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conexao->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $produto = $resultado->fetch_assoc();

    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }
} else {
    echo "ID do produto não especificado.";
    exit;
}

// Cálculo do número de produtos no carrinho
$numProdutos = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0;
$carrinhoMensagem = $numProdutos === 1 ? "Carrinho - 1 produto" : "Carrinho - $numProdutos produtos";

// Verifica se o usuário é administrador
$isAdmin = false;
if (isset($_SESSION['email'])) {
    $query = $conexao->prepare('SELECT e_admin FROM usuarios WHERE email = ?');
    $query->bind_param('s', $_SESSION['email']);
    $query->execute();
    $result = $query->get_result();
    if ($row = $result->fetch_assoc()) {
        $isAdmin = (bool)$row['e_admin'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TecPro - Página do Produto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../styles/dash.css">
</head>
<body class="<?php echo $theme; ?>"> <!-- Define a classe do body com base no cookie -->
<header class="header">
    <div class="logo">
    <a href="../dashboard/dashboard.php">
    <img src="../styles/img/tecprok.png" alt="Logo TecPro" style="height: 100px;">
</a>
 <!-- Exibe o logo da empresa -->
    </div> 

    <!-- Botão de Acesso a Departamentos -->
    <div class="dropdown">
        <button class="departments-button">
            <i class="fa-solid fa-bars-staggered"></i> Acesse Todos os Departamentos <!-- Botão que abre um menu dropdown -->
        </button>
        <div class="dropdown-content"> <!-- Conteúdo do dropdown -->
            <a href="serch.php?categoria_id=1">Placas de Vídeos</a> <!-- Links para categorias de produtos -->
            <a href="serch.php?categoria_id=2">Processadores</a>
            <a href="serch.php?categoria_id=3">Mouses</a>
            <a href="serch.php?categoria_id=4">Monitores</a>
            <a href="serch.php?categoria_id=5">Memórias</a>
            <a href="serch.php?categoria_id=6">Fontes de Alimentação</a>
            <a href="serch.php?categoria_id=7">Placa Mãe</a>
        </div>
    </div>

    <nav class="menu">
        <div class="menu-buttons"> <!-- Botões do menu -->
            <a href="https://wa.me/+554288697902" class="support-button">
                <i class="fas fa-headset"></i> ATENDIMENTO AO CLIENTE <!-- Link para atendimento ao cliente -->
            </a>
            <button class="favorites-button" onclick="window.location.href='#'">
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
                <?php if ($isAdmin): ?>
                    <button onclick="window.location.href='../paginadm/ADM.php'" class="btn btn-secondary">
                        Gerenciar Site 
                    </button>
                <?php endif; ?>
                <form method="POST" action="../login/logout.php"> <!-- Formulário para logout -->
                    <button type="submit" name="logout">Sair</button> <!-- Botão para sair -->
                </form>
            <?php endif; ?>
        </div>
    </div>
</header>
<br><br>
<style>
   .main-container {
    background-color: #444; /* Fundo mais escuro para a main-container */
    padding: 10px;
    border-radius: 8px;
    margin: 0 auto;
    max-width: 80%;
    justify-content: center;
    
}

.main {
    display: flex;
    align-items: flex-start;
    padding: 10px;
    background-color: #444; /* Fundo mais escuro dentro da main */
    border-radius: 5px;
    color: #e0e0e0; /* Texto claro para contraste */
}

    .product-image {
        width: 700px;
        height: 700px;
        margin-right: 100px;
        border-radius: 5px;
    }
    .content {
        flex: 1;
        background-color: #222;
        padding: 30px;
        border-radius: 5px;
        color: #fff;
        max-width: 600px;
    }
    .product-title {
        font-size: 26px;
        margin-bottom: 10px;
    }
    .price {
        font-size: 24px;
        color: #4caf50;
        margin-bottom: 20px;
    }
    .add-to-cart-button {
    width: 100%;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    color: #fff; /* Cor do texto, mantenha como branco para contraste */
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    background-color: green /* Altere esta linha para definir a cor roxa */
}

    .installment-options {
        margin-top: 25px;
    }
    .installment-options h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    .installment-options table {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
    }
    .installment-options table th, .installment-options table td {
        padding: 8px;
        border: 1px solid #444;
        text-align: left;
    }

        .descricao-container {
    background-color: #444; /* Cor de fundo para a descrição */
    padding: 20px; /* Espaçamento interno */
    border-radius: 5px; /* Bordas arredondadas */
    display: flex; /* Usar flexbox para layout */
    align-items: center; /* Alinhamento vertical no centro */
    justify-content: center; /* Centralizar conteúdo */
    margin-top: 20px; /* Margem superior para espaçamento */
}
.text-content {
    text-align: left; /* Alinhamento do texto à esquerda */
    margin-right: 70px; /* Espaço entre o texto e a imagem */
    margin-top: -220px;
    margin-left: 100px;
}

.image-content {
    flex: 0 0 auto; /* Não permite que a imagem se expanda */
    text-align: center;
    background-color: #333;
    padding: 20px;
    border-radius: 10px;
    margin-right: 350px;

}



.descricao-container2 {
    background-color: #444; /* Cor de fundo para a descrição */
    padding: 20px; /* Espaçamento interno */
    border-radius: 5px; /* Bordas arredondadas */
    display: flex; /* Usar flexbox para layout */
    align-items: center; /* Alinhamento vertical no centro */
    justify-content: center; /* Centralizar conteúdo */
    margin-top: 20px; /* Margem superior para espaçamento */
}

.text-content2 {
    text-align: left; /* Alinhamento do texto à esquerda */
    margin-right: 100px; /* Espaço entre o texto e a imagem */
    margin-top: -220px;
    margin-right: 350px;
}

.image-content2 {
    flex: 0 0 auto; /* Não permite que a imagem se expanda */
    text-align: center;
    background-color: #333;
    padding: 20px;
    border-radius: 10px;
    margin-right: 70px; /* Espaço entre o texto e a imagem */
    margin-top: 100px;
    margin-left: 100px;
}
.image-content2 img {
    width: 400px; /* A imagem não excede a largura do seu container */
    height: 400px; /* Mantém a proporção da imagem */
}

.image-content img {
    width: 400px; /* A imagem não excede a largura do seu container */
    height: 400px; /* Mantém a proporção da imagem */

}

.pagaments img {
    margin-right: 10px; /* Ajuste o valor para aumentar ou diminuir o espaço entre as imagens */
}

.menu-item {
            color: white;
            font-family: Arial, sans-serif;
            font-size: 20px;
            text-align: center;
            position: relative;
        }
        .menu-item::before {
            content: '';
            display: block;
            width: 50px;
            height: 2px;
            background-color: rgb(153, 0, 255);
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .modo-claro .menu-item {
    color: white; /* Define a cor do texto como branca */
}

.modo-claro .text-content h2,
.modo-claro .text-content p,
.modo-claro .text-content2 h2,
.modo-claro .text-content2 p {
    color: white; /* Garante que o texto nas descrições também fique branco */
}

.pagaments {
    margin-top: 25px;
    color: white; /* Define a cor do texto como branca */
}

</style>

<div class="main-container">
    <div class="main">
        <!-- Imagem do produto -->
        <img class="product-image" src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" />

        <div class="content">
            <!-- Nome e Preço do Produto -->
            <div class="product-title"><?php echo htmlspecialchars($produto['nome']); ?></div>
            <div class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>

         <form action="../carrinho/carrinho.php" method="GET">
                    <input type="hidden" name="acao" value="add">
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                    <button type="submit" class="add-to-cart-button"><i class="fa-solid fa-cart-plus"></i> Adicionar ao Carrinho</button>
                </form>   


            <!-- Opções de parcelamento -->
            <div class="installment-options">
                <h4>Opções de Parcelamento:</h4>
                <table>
                    <tr><th>Parcelamento</th><th>Valor</th></tr>
                    <?php
                    // Gera opções de parcelamento
                    $preco = $produto['preco'];
                    for ($parcela = 1; $parcela <= 12; $parcela++) {
                        $valorParcela = $preco / $parcela;
                        $desconto = ($parcela <= 6) ? 0.05 : 0; // Exemplo: 5% de desconto para 1-6x
                        $valorComDesconto = $valorParcela * (1 - $desconto);
                        echo "<tr><td>{$parcela}x</td><td>R$ " . number_format($valorComDesconto, 2, ',', '.') . ($desconto ? " <span>(5% desconto)</span>" : " (sem juros)") . "</td></tr>";
                    }
                  
                    ?>
                </table>
            </div>
            <br>
            <div class="pagaments">
          <img src="../styles/img/visa.png" alt="Logo TecPro" style="height: 40px; width: 40px;">
          </a>
          <img src="../styles/img/mastercard.png" alt="Logo TecPro" style="height: 40px; width: 40px;">
          </a>
          <img src="../styles/img/elo.webp" alt="Logo TecPro" style="height: 40px; width: 40px;">
          </a>
          <img src="../styles/img/hipercardlogo.png" alt="Logo TecPro" style="height: 40px; width: 40px;">
          </a>
        </div>
        </div>
    </div>
    <br><br><br><br><br><br><br><br><br>

    <div class="container modo-claro"> <!-- Adicione 'modo-claro' aqui quando necessário -->
    <div class="menu-item">SOBRE</div>
    <br><br>
    <div class="descricao-container">
        <div class="text-content">
            <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
        </div>
        <div class="image-content">
            <img alt="<?php echo htmlspecialchars($produto['nome']); ?>" src="<?php echo htmlspecialchars($produto['imagemdescricao']); ?>" height="400" width="300" />
        </div>
    </div>
    <br><br><br><br>
    <div class="descricao-container2">
        <div class="image-content2">
            <img alt="<?php echo htmlspecialchars($produto['nome']); ?>" src="<?php echo htmlspecialchars($produto['segundaimagemdesc']); ?>" height="400" width="300" />
        </div>
        <div class="text-content2">
            <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($produto['segundadescricao'])); ?></p>
        </div>
    </div>
</div>

 
 </head>
 <body>
<script>
    
// Função para alternar entre os modos claro e escuro
function toggleMode() {
        const body = document.body; // Obtém o elemento body do HTML
        const menuButtons = document.querySelector('.menu-buttons'); // Obtém os botões do menu
        const modeButton = document.getElementById('mode-button'); // Obtém o botão de modo
        const modeText = document.getElementById('mode-text'); // Obtém o texto do botão de modo
        const icon = modeButton.querySelector('i'); // Obtém o ícone do botão de modo

        // Alterna a classe de modo escuro
        body.classList.toggle('dark-mode'); // Adiciona ou remove a classe 'dark-mode' no body
        menuButtons.classList.toggle('dark-mode'); // Adiciona ou remove a classe 'dark-mode' nos botões do menu

        // Verifica se o modo escuro está ativado
        if (body.classList.contains('dark-mode')) { // Se o modo escuro estiver ativado
            modeText.textContent = 'MODO CLARO'; // Muda o texto para "Modo Claro"
            icon.classList.remove('fa-moon'); // Remove o ícone da lua
            icon.classList.add('fa-sun'); // Adiciona o ícone do sol
            
            // Define um cookie para armazenar a preferência do usuário
            document.cookie = "theme=dark-mode; path=/; max-age=" + (365 * 24 * 60 * 60); // 1 ano
        } else {
            modeText.textContent = 'MODO ESCURO'; // Muda o texto para "Modo Escuro"
            icon.classList.remove('fa-sun'); // Remove o ícone do sol
            icon.classList.add('fa-moon'); // Adiciona o ícone da lua

            // Define um cookie para armazenar a preferência do usuário
            document.cookie = "theme=light-mode; path=/; max-age=" + (365 * 24 * 60 * 60); // 1 ano
        }
    }

    // Atualiza o tema com base no cookie ao carregar a página
    window.onload = function() { // Função chamada quando a página é carregada
        const theme = document.cookie.split('; ').find(row => row.startsWith('theme=')); // Busca o cookie 'theme'
        if (theme) { // Se o cookie existir
            const mode = theme.split('=')[1]; // Obtém o valor do modo do cookie
            document.body.classList.add(mode); // Adiciona a classe correspondente ao body
            document.querySelector('.menu-buttons').classList.add(mode); // Adiciona a classe correspondente aos botões do menu
            if (mode === 'dark-mode') { // Se o modo for 'dark-mode'
                document.getElementById('mode-text').textContent = 'MODO CLARO'; // Atualiza o texto do modo
                document.getElementById('mode-button').querySelector('i').classList.replace('fa-moon', 'fa-sun'); // Atualiza o ícone
            } else {
                document.getElementById('mode-text').textContent = 'MODO ESCURO'; // Atualiza o texto do modo
                document.getElementById('mode-button').querySelector('i').classList.replace('fa-sun', 'fa-moon'); // Atualiza o ícone
            }
        }
    };
</script>
<footer>
        <p>&copy; 2023 Loja Exemplo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
