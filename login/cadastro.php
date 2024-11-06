<?php
session_start();
if (isset($_POST['submit'])) {
    include_once('../config.php');
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $endereco = $_POST['endereco'];

    $query = "INSERT INTO usuarios (nome, senha, email, cpf, telefone, data_nascimento, cidade, estado, endereco)
              VALUES ('$nome', '$senha', '$email', '$cpf', '$telefone', '$data_nascimento', '$cidade', '$estado', '$endereco')";

    $result = mysqli_query($conexao, $query);

    if ($result) {
        header('Location: ../login/login.php');
        exit();
    } else {
        echo "Erro ao cadastrar usuário: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="../front-end/img/logosite.png" rel="stylesheet"/>
    <link rel="stylesheet" href="../styles/login.css">
    <script>
        function validateEmail() {
            const emailField = document.getElementById("email");
            const errorText = document.getElementById("emailError");
            const emailValue = emailField.value;

            if (!emailValue.endsWith("@gmail.com")) {
                errorText.textContent = "O email deve ser do domínio @gmail.com";
                emailField.setCustomValidity("O email deve ser do domínio @gmail.com");
            } else {
                errorText.textContent = "";
                emailField.setCustomValidity(""); // Limpa a mensagem de erro
            }
        }

        function validateForm() {
            return validateEmail();
        }
    </script>
       <style>
        .scroll-container {
            max-height: 400px; /* Altura máxima do contêiner */
            overflow-y: auto; /* Adiciona rolagem vertical */
            overflow-x: hidden; /* Remove rolagem horizontal */
            padding: 30px; /* Adiciona espaçamento interno */
            box-sizing: border-box; /* Inclui o padding no cálculo da largura total */
        }

        .button {
            width: 100%; /* Ajusta para ocupar toda a largura disponível */
            padding: 10px; /* Adiciona um pouco de espaçamento interno */
            font-size: 16px; /* Define o tamanho da fonte */
            background-color: rgb(119, 0, 255);
            color: white; /* Cor do texto */
            border: none; /* Remove a borda */
            border-radius: 5px; /* Bordas arredondadas */
            cursor: pointer; /* Muda o cursor ao passar sobre o botão */
            transition: background-color 0.3s; /* Transição suave para cor de fundo */
            margin-left: 10px;
        }
        
        .button:hover {
            background-color: rgb(153, 0, 255);
        }

        /* Adiciona margens ao título e ao parágrafo */
        .right h2 {
            margin-left: 28px; /* Ajuste o valor conforme necessário */
            color: white;
        }

        .right p {
            margin-left: 28px; /* Ajuste o valor conforme necessário */
        }

        /* Adiciona estilo para a imagem da esquerda */
        .left img {
            width: 100%; /* Ajusta a largura da imagem para ocupar toda a área */
            height: auto; /* Mantém a proporção da imagem */
            margin-top: 0px; /* Adiciona espaço acima da imagem */
            margin-bottom: 200px; /* Adiciona espaço abaixo da imagem */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="../styles/img/login.webp" alt="Imagem de Login"> <!-- Adicionando a imagem aqui -->
            <div class="left-content">
                <h1>Tec Pro</h1>
                <p>Assistência técnica, que tem tudo que você procura.</p>
            </div>
        </div>
        <div class="right">
        <img class="imglogo" src="../styles/img/tecprok.png" alt="logo-site" class="logo-site">
        <br>
            <h2>Cadastrar</h2>
            <p>Preencha os dados abaixo para se cadastrar.</p>
            <div class="scroll-container">
                <form action="" method="POST" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="nome">Nome completo</label>
                        <input type="text" name="nome" id="nome" placeholder="Nome" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" placeholder="Senha" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" placeholder="Email" required oninput="validateEmail()">
                        <span id="emailError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                         <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" required 
                         oninput="formatarCPF(this)"
                        pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                            title="Digite o CPF no formato 000.000.000-00">
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" name="telefone" id="telefone" placeholder="Telefone" required>
                    </div>
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="data_nascimento" required>
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" placeholder="Cidade" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" name="estado" id="estado" placeholder="Estado" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" name="endereco" id="endereco" placeholder="Endereço" required>
                    </div>
                    <input class="button" type="submit" name="submit" value="Cadastrar">
                </form>
            </div>
            <br>
            <a href="../login/login.php" class="forgot-password">Voltar ao Login</a>
        </div>
    </div>
</body>
</html>
