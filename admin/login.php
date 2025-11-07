<?php

require '../conexao.php';

$erros = [];

if (isset($_SESSION['erro_admin_login'])) {
    $erros[] = $_SESSION['erro_admin_login'];
    unset($_SESSION['erro_admin_login']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erros[] = "E-mail e senha são obrigatórios.";
    }

    if (empty($erros)) {
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($senha, $admin['senha'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
            header('Location: index.php');
            exit;
        } else {
            $erros[] = "E-mail ou senha de admin inválidos.";
        }
    }
    
    if (!empty($erros)) {
        $_SESSION['erro_admin_login'] = $erros[0];
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Login</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body style="background-color: #f0f0f0;">

    <main class="container-cadastro" style="max-width: 450px; margin-top: 100px; background-color: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <div class="cadastro-header" style="text-align: center;">
            <div class="logo-container">
                <a href="#">Roseglaze Admin</a>
            </div>
        </div>

        <div>
            <?php if (!empty($erros)): ?>
                <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; text-align: center;">
                    <?php foreach ($erros as $erro): ?>
                        <p><?php echo htmlspecialchars($erro); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <form action="login.php" method="POST" class="form-cadastro">
            
            <div class="form-grupo-minimalista">
                <label for="email">E-mail (Admin)</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit" class="btn-add-to-bag">Entrar</button>
            
        </form>

    </main>

</body>
</html>