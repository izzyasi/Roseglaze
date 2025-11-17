<?php

require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; 
}

$usuario_id = $_SESSION['usuario_id'];
$erros_email = [];
$sucesso_email = '';
$erros_senha = [];
$sucesso_senha = '';

if (isset($_SESSION['sucesso_seguranca'])) {

    if ($_SESSION['sucesso_seguranca']['form'] === 'email') {
        $sucesso_email = $_SESSION['sucesso_seguranca']['msg'];
    }
    if ($_SESSION['sucesso_seguranca']['form'] === 'senha') {
        $sucesso_senha = $_SESSION['sucesso_seguranca']['msg'];
    }
    unset($_SESSION['sucesso_seguranca']);
}
if (isset($_SESSION['erros_seguranca'])) {
    if ($_SESSION['erros_seguranca']['form'] === 'email') {
        $erros_email = $_SESSION['erros_seguranca']['msg'];
    }
    if ($_SESSION['erros_seguranca']['form'] === 'senha') {
        $erros_senha = $_SESSION['erros_seguranca']['msg'];
    }
    unset($_SESSION['erros_seguranca']);
}

try {
    $sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->execute([$usuario_id]);
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        session_destroy(); header('Location: login.php'); exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados do utilizador: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'alterar_email') {
        $novo_email = trim($_POST['email']);
        $senha_atual = $_POST['senha_atual_email'];
        
        if (password_verify($senha_atual, $usuario['senha'])) {
        
            if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
                $erros_email[] = "O novo formato de e-mail é inválido.";
            } else {
        
                $sql_check = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->execute([$novo_email, $usuario_id]);
                if ($stmt_check->fetch()) {
                    $erros_email[] = "Este e-mail já está a ser usado por outra conta.";
                }
            }
 
            if (empty($erros_email)) {
                $sql_update = "UPDATE usuarios SET email = ? WHERE id = ?";
                $pdo->prepare($sql_update)->execute([$novo_email, $usuario_id]);
                $_SESSION['sucesso_seguranca'] = ['form' => 'email', 'msg' => 'E-mail atualizado com sucesso!'];
                header('Location: login-seguranca.php');
                exit;
            }
            
        } else {
            $erros_email[] = "A 'Senha Atual' está incorreta.";
        }
        
        if (!empty($erros_email)) {
            $_SESSION['erros_seguranca'] = ['form' => 'email', 'msg' => $erros_email];
            header('Location: login-seguranca.php');
            exit;
        }
    }

    if ($acao === 'alterar_senha') {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];

        if (password_verify($senha_atual, $usuario['senha'])) {
 
            if (strlen($nova_senha) < 8) {
                $erros_senha[] = "A nova senha deve ter no mínimo 8 caracteres.";
            }
            if (!preg_match('/[a-zA-Z]/', $nova_senha)) {
                $erros_senha[] = "A nova senha deve conter pelo menos uma letra.";
            }
            if (!preg_match('/\d/', $nova_senha)) {
                $erros_senha[] = "A nova senha deve conter pelo menos um número.";
            }
           
            if (!preg_match('/[A-Z]/', $nova_senha)) {
                $erros_senha[] = "A nova senha deve conter pelo menos uma letra maiúscula.";
            }

            if (empty($erros_senha)) {
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $sql_update = "UPDATE usuarios SET senha = ? WHERE id = ?";
                $pdo->prepare($sql_update)->execute([$nova_senha_hash, $usuario_id]);
                
                $_SESSION['sucesso_seguranca'] = ['form' => 'senha', 'msg' => 'Senha alterada com sucesso!'];
                header('Location: login-seguranca.php');
                exit;
            }
            
        } else {
            $erros_senha[] = "A 'Senha Atual' está incorreta.";
        }

        if (!empty($erros_senha)) {
            $_SESSION['erros_seguranca'] = ['form' => 'senha', 'msg' => $erros_senha];
            header('Location: login-seguranca.php');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Segurança</title>
    <link rel="stylesheet" href="css/estilo.css"> 
</head>
<body style="background-color: #f0f0f0;"> <?php require 'header.php'; ?>

    <main class="container-conta">
        
        <a href="minha_conta.php" class="link-voltar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                fill="none" stroke="currentColor" stroke-width="2" 
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            A MINHA CONTA
        </a>
        
        <h2 class="conta-titulo-principal">INÍCIO DE SESSÃO E SEGURANÇA</h2>

        <div class="conta-painel" style="background-color: #fff; padding: 40px; border: 1px solid #eee; border-radius: 8px;">

            <div class="form-secao">
                <h3 class="form-secao-titulo">E-MAIL DE INÍCIO DE SESSÃO</h3>
                
                <div>
                    <?php if (!empty($erros_email)): ?>
                        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                            <?php foreach ($erros_email as $erro): ?><p><?php echo htmlspecialchars($erro); ?></p><?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($sucesso_email): ?>
                        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px;">
                            <p><?php echo htmlspecialchars($sucesso_email); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <form action="login-seguranca.php" method="POST" class="conta-form">
                    <input type="hidden" name="acao" value="alterar_email">
                    
                    <div class="conta-form-grupo">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    </div>

                    <p style="font-size: 0.9rem; color: #555;">
                        Para alterar o seu e-mail, introduza a sua palavra-passe atual.
                    </p>
                    
                    <div class="conta-form-grupo">
                        <label for="senha_atual_email">Senha Atual</label>
                        <input type="password" id="senha_atual_email" name="senha_atual_email" required>
                    </div>

                    <button type="submit" class="btn-add-to-bag">Alterar o E-mail de Início de Sessão</button>
                </form>
            </div>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">

            <div class="form-secao">
                <h3 class="form-secao-titulo">SENHA</h3>
                
                <div>
                    <?php if (!empty($erros_senha)): ?>
                        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                            <?php foreach ($erros_senha as $erro): ?><p><?php echo htmlspecialchars($erro); ?></p><?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($sucesso_senha): ?>
                        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px;">
                            <p><?php echo htmlspecialchars($sucesso_senha); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <form action="login-seguranca.php" method="POST" class="conta-form">
                    <input type="hidden" name="acao" value="alterar_senha">
                    
                    <div class="conta-form-grupo">
                        <label for="senha_atual_senha">Senha Atual</label>
                        <input type="password" id="senha_atual_senha" name="senha_atual" required>
                    </div>
                    
                    <div class="conta-form-grupo">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                    </div>
                    
                    <p style="font-size: 0.8rem; color: #777;">
                        A sua senha deve conter no mínimo 8 caracteres e 3 tipos de caracteres: maiúsculas, minúsculas e números.
                    </p>

                    <button type="submit" class="btn-add-to-bag">Alterar Senha</button>
                </form>
            </div>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">
            
            <div class="danger-zone">
                <h3 style="color: #c00;">Deletar Conta</h3>
                <p>Atenção: Esta ação é permanente e não pode ser desfeita.</p>
                <form action="deletar_conta.php" method="POST" id="form-deletar-conta">
                    <button type="submit" class="btn-perigo">
                        Deletar minha conta permanentemente.
                    </button>
                </form>
            </div>
            
        </div>
    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const formDeletar = document.getElementById('form-deletar-conta');
        if (formDeletar) {
            formDeletar.addEventListener('submit', function(e) {
                const confirmacao = confirm("Tem a certeza absoluta que deseja deletar a sua conta? Esta ação é permanente.");
                if (confirmacao === false) {
                    e.preventDefault(); 
                }
            });
        }
    });
    </script>
    
</body>
</html>