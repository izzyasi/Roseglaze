<?php

require 'conexao.php';

$erros = [];

if (isset($_SESSION['erros_login'])) {
    $erros = $_SESSION['erros_login'];
    unset($_SESSION['erros_login']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erros[] = "E-mail e senha são obrigatórios.";
    }

    if (empty($erros)) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {

            if ($usuario['email_verificado'] == 1) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome']; 
                
                header('Location: minha_conta.php');
                exit;
                
            } else {
                $erros[] = "A sua conta foi criada, mas ainda não foi ativada. Por favor, verifique o seu e-mail.";
            }
            
        } else {
            $erros[] = "E-mail ou senha inválidos.";
        }
    }
  
    if (!empty($erros)) {
        $_SESSION['erros_login'] = $erros;
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
    <title>Roseglaze - Entrar</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-cadastro">
        
        <div class="cadastro-header">
            <div class="cadastro-abas">
                <a href="login.php" class="aba-ativa">ENTRAR</a>
                <a href="cadastro.php" class="aba-inativa">CADASTRAR</a>
            </div>
            
            <p class="cadastro-descricao">
            Bem-vindo(a) de volta.<br>
            Entre para poder acessar a sua conta.
            </p>
        </div>
        
        <div>
            <?php if (!empty($erros)): ?>
                <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                    <?php foreach ($erros as $erro): ?>
                        <p><?php echo htmlspecialchars($erro); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <form action="login.php" method="POST" class="form-cadastro">
            
            <div class="form-grupo-minimalista">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit" class="btn-add-to-bag">Entrar</button>
            
        </form>

    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>

</body>
</html>