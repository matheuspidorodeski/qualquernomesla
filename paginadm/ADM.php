<?php
session_start();
include_once('../config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conexao->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $admin_level = $user['e_admin'];

    // Verifica se o nível do administrador está correto
    if ($admin_level < 1 || $admin_level > 3) {
        header("Location: ../dashboard/dashboard.php");
        exit();
    }
} else {
    echo "<p style='color:red;'>Erro ao verificar o usuário.</p>";
    exit();
}

// Exibe todos os usuários para que possam ser editados/excluídos
$sql_users = "SELECT * FROM usuarios";
$result_users = $conexao->query($sql_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ADM</title>
    <style>
        body {
            background-image: url('jj.avif');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
            color: white;
            text-align: center;
        }
        .table-bg {
            background: rgba(128, 128, 128, 0.8);
            border-radius: 15px 15px 0 0;
        }
        .logout-container {
            display: flex;          
            justify-content: flex-start; 
            margin-left: 20px;     
        }
        .logout-button {
            background-color: red; 
            color: white;          
            border: none;          
            padding: 10px 20px;    
            border-radius: 5px;    
            cursor: pointer;       
            font-size: 16px;      
        }
        .logout-button:hover {
            background-color: darkred; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">SISTEMA DE ADMIRO DA TECPRO</a>
            <div class="logout-container">
                <form method="POST" action="../paginadm/logoutadm.php">
                    <button type="submit" name="logout" class="logout-button">Sair</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="d-flex justify-content-center my-3 gap-3">
    <a href="../dashboard/dashboard.php" class="btn btn-warning">Ir para dashboard</a>
    <a href="../paginadm/gerenciar_adm.php" class="btn btn-success" onclick="return handleAdminRegistration(<?php echo $admin_level; ?>)">Cadastrar um Administrador</a>
    <a href="../produtos/lista.php" class="btn btn-info" onclick="return checkProductPermission(<?php echo $admin_level; ?>)">Cadastrar Produtos</a>
    <a href="../login/cadastro.php" class="btn btn-secondary">Cadastrar Usuário</a>
    <a href="../estoque/estoque.php" class="btn btn-dark">Gerenciar Estoque</a>
</div>

    <br>
    <?php
        echo "<h1>Bem-vindo, administrador <u>{$user['nome']}</u></h1>";
    ?>
    <br>
    <div class="m-5">
     
    </div>

    <div class="m-5">
    <form method="GET" action="">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="search_id" placeholder="Pesquisar por ID" aria-label="Pesquisar por ID">
            <input type="text" class="form-control" name="search_email" placeholder="Pesquisar por Email" aria-label="Pesquisar por Email">
            <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
        </div>
    </form>
</div>

<div class="m-5">
    <table class="table text-white table-bg">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Telefone</th>
                <th scope="col">Data de Nascimento</th>
                <th scope="col">Cidade</th>
                <th scope="col">Estado</th>
                <th scope="col">Endereço</th>
                <th scope="col">Admin Level</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
<?php
$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
$search_email = isset($_GET['search_email']) ? $_GET['search_email'] : '';

$sql_users = "SELECT * FROM usuarios WHERE 1=1"; // Começa a consulta

if ($search_id) {
    $sql_users .= " AND id = ?";
}
if ($search_email) {
    $sql_users .= " AND email LIKE ?";
}

$stmt = $conexao->prepare($sql_users);

if ($search_id && $search_email) {
    $like_email = "%$search_email%";
    $stmt->bind_param("is", $search_id, $like_email);
} elseif ($search_id) {
    $stmt->bind_param("i", $search_id);
} elseif ($search_email) {
    $like_email = "%$search_email%";
    $stmt->bind_param("s", $like_email);
}

$stmt->execute();
$result_users = $stmt->get_result();

while ($user_data = mysqli_fetch_assoc($result_users)) {
    echo "<tr>";
    echo "<td>".$user_data['id']."</td>";
    echo "<td>".$user_data['nome']."</td>";
    echo "<td>".$user_data['email']."</td>";
    echo "<td>".$user_data['telefone']."</td>";
    echo "<td>".$user_data['data_nascimento']."</td>";
    echo "<td>".$user_data['cidade']."</td>";
    echo "<td>".$user_data['estado']."</td>";
    echo "<td>".$user_data['endereco']."</td>";
    echo "<td>".$user_data['e_admin']."</td>";
    echo "<td>";
    
    // Check admin level and e_admin status
    if ($admin_level == 1) {
        echo "<a class='btn btn-sm btn-primary' href='edit.php?id=".$user_data['id']."' title='Editar'>Editar</a>
              <a class='btn btn-sm btn-danger' href='delete.php?id=".$user_data['id']."' title='Excluir'>Excluir</a>";
    } elseif ($admin_level == 2 || $admin_level == 3) {
        if ($user_data['e_admin'] == 0) { // Somente se o usuário não for um admin
            echo "<a class='btn btn-sm btn-primary' href='edit.php?id=".$user_data['id']."' title='Editar'>Editar</a>
                  <a class='btn btn-sm btn-danger' href='delete.php?id=".$user_data['id']."' title='Excluir'>Excluir</a>";
        }
    }

    echo "</td>";
    echo "</tr>";
}
?>
        </tbody>
    </table>
</div>


    <script>
        function handleAdminRegistration(adminLevel) {
            if (adminLevel === 1) {
                // Redirect to the admin registration page
                window.location.href = '../paginadm/gerenciar_adm.php'; // Update this to the correct path
            } else {
                alert("Você não possui permissão para cadastrar administradores.");
            }
            return false; // Prevent default anchor behavior
        }

        function checkProductPermission(adminLevel) {
            if (adminLevel === 3) {
                alert("Você não possui permissão para cadastrar produtos. Apenas Admin de nível 1 e nível 2 podem fazer isso.");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>
