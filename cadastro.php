<?php
/*
 * Documentação: Página de Cadastro (cadastro.php)
 */

require 'conexao.php';

$erros = [];
$mensagem_sucesso = '';
$old_input = []; 

if (isset($_SESSION['sucesso_cadastro'])) {
    $mensagem_sucesso = $_SESSION['sucesso_cadastro'];
    unset($_SESSION['sucesso_cadastro']); 
}

if (isset($_SESSION['erros_cadastro'])) {
    $erros = $_SESSION['erros_cadastro'];
    unset($_SESSION['erros_cadastro']); 
    if (isset($_SESSION['old_input_cadastro'])) {
        $old_input = $_SESSION['old_input_cadastro'];
        unset($_SESSION['old_input_cadastro']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $titulo = trim($_POST['titulo']);
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $telefone = trim($_POST['telefone']);
    
    $local_residencia = !empty($_POST['local_residencia']) ? trim($_POST['local_residencia']) : NULL;
    $data_nascimento = !empty($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : NULL;
    $aceita_comunicacoes = isset($_POST['aceita_comunicacoes']) ? 1 : 0;

    $old_input = $_POST;
    unset($old_input['senha']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O formato do e-mail é inválido.";
    }
    if (strlen($senha) < 8) {
        $erros[] = "A senha deve ter no mínimo 8 caracteres.";
    }
    if (!preg_match('/[a-zA-Z]/', $senha)) {
        $erros[] = "A senha deve conter pelo menos uma letra.";
    }
    if (!preg_match('/\d/', $senha)) {
        $erros[] = "A senha deve conter pelo menos um número.";
    }

    if (empty($erros)) {
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$email]);
        if ($stmt_check->fetch()) {
            $erros[] = "Este e-mail já está registrado. Tente fazer login.";
        }
    }

    if (empty($erros)) {

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $token_verificacao = bin2hex(random_bytes(32));

        $sql_insert = "INSERT INTO usuarios 
                        (email, senha, titulo, nome, sobrenome, telefone, 
                         local_residencia, data_nascimento, aceita_comunicacoes, 
                         token_verificacao, data_registro) 
                       VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        try {
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                $email, $senha_hash, $titulo, $nome, $sobrenome, 
                $telefone, $local_residencia, $data_nascimento, $aceita_comunicacoes,
                $token_verificacao 
            ]);

            $_SESSION['sucesso_cadastro'] = "Conta criada com sucesso! Por favor, verifique o seu e-mail para ativar a sua conta.";
            
            header('Location: cadastro.php');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['erros_cadastro'] = ["Ocorreu um erro ao criar a sua conta. Por favor, tente novamente."];
            $_SESSION['old_input_cadastro'] = $old_input;
            header('Location: cadastro.php');
            exit;
        }
        
    } else {
        $_SESSION['erros_cadastro'] = $erros;
        $_SESSION['old_input_cadastro'] = $old_input;
        header('Location: cadastro.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Criar Conta</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-cadastro" style="max-width: 600px;">
        
        <div class="cadastro-header">
            <div class="cadastro-abas">
                <a href="login.php" class="aba-inativa">ENTRAR</a>
                <a href="cadastro.php" class="aba-ativa">CADASTRAR</a>
            </div>
            <p class="cadastro-descricao">
                Crie uma conta Roseglaze para gerir as suas informações pessoais,
                beneficiar de uma experiência de compra mais personalizada e 
                usufruir de um check-out online mais rápido.
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
            
            <?php if ($mensagem_sucesso): ?>
                <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px;">
                    <p><?php echo htmlspecialchars($mensagem_sucesso); ?></p>
                    <a href="login.php">Ir para o Login</a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (empty($mensagem_sucesso)): ?>
        
            <form action="cadastro.php" method="POST" class="form-cadastro">
                
                <div class="form-grupo-minimalista">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="titulo">Título</label>
                    <?php $old_titulo = $old_input['titulo'] ?? ''; ?>
                    <select id="titulo" name="titulo" required>
                        <option value="" disabled <?php echo ($old_titulo === '') ? 'selected' : ''; ?>>Selecione</option>
                        <option value="Sr." <?php echo ($old_titulo === 'Sr.') ? 'selected' : ''; ?>>Sr.</option>
                        <option value="Sra." <?php echo ($old_titulo === 'Sra.') ? 'selected' : ''; ?>>Sra.</option>
                        <option value="Srta." <?php echo ($old_titulo === 'Srta.') ? 'selected' : ''; ?>>Srta.</option>
                        <option value="Prefiro não informar" <?php echo ($old_titulo === 'Prefiro não informar') ? 'selected' : ''; ?>>Prefiro não informar</option>
                    </select>
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($old_input['nome'] ?? ''); ?>" required>
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($old_input['sobrenome'] ?? ''); ?>" required>
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($old_input['telefone'] ?? ''); ?>" placeholder="+55 (XX) XXXXX-XXXX" required>
                </div>

                <div class="form-grupo-minimalista">
                    <label for="local_residencia">Local de residência (opcional)</label>
                    <input type="text" id="local_residencia" name="local_residencia" value="<?php echo htmlspecialchars($old_input['local_residencia'] ?? ''); ?>">
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="data_nascimento">Data de nascimento (opcional)</label>
                    <input type="text" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($old_input['data_nascimento'] ?? ''); ?>" placeholder="AAAA-MM-DD (ex: 1990-05-30)">
                </div>

                <div class="form-grupo-checkbox">
                    <input type="checkbox" id="aceita_comunicacoes" name="aceita_comunicacoes" value="1" <?php echo isset($old_input['aceita_comunicacoes']) ? 'checked' : ''; ?>>
                    <label for="aceita_comunicacoes">
                        Concordo que a Roseglaze possa a enviar-me comunicações
                        sobre novas coleções, produtos, serviços e eventos por e-mail.
                    </label>
                </div>

                <p class="cadastro-termos">
                    Ao prosseguir, você confirma que tem idade igual ou superior à
                    necessária para criar uma conta e concorda com a nossa
                    Política de Privacidade.
                </p>

                <button type="submit" class="btn-add-to-bag">Criar Conta</button>
                
            </form>
        <?php endif; ?>

    </main>

    <?php require 'footer.php'; ?>

</body>
</html>