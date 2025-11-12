<?php

require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; 
}

$usuario_id = $_SESSION['usuario_id'];
try {
    $sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->execute([$usuario_id]);
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados do utilizador: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Detalhes Pessoais</title>
    
    <link rel="stylesheet" href="css/estilo.css"> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body style="background-color: #f0f0f0;"> <?php require 'header.php'; ?>

    <main class="container-conta">
        
        <a href="minha_conta.php" class="link-voltar">
            <span class="material-icons-outlined">chevron_left</span>
            A MINHA CONTA
        </a>
        
        <h2 class="conta-titulo-principal">DETALHES PESSOAIS</h2>

        <div class="conta-painel" style="background-color: #fff; padding: 40px; border: 1px solid #eee; border-radius: 8px;">

            <div class="form-secao">
                <h3 class="form-secao-titulo">PERFIL</h3>
                
                <form action="detalhes-pessoais.php" method="POST" class="conta-form">
                    <input type="hidden" name="acao" value="atualizar_perfil">
                    
                    <div class="conta-form-grupo">
                        <label for="titulo">Título</label>
                        <select id="titulo" name="titulo" class="input-readonly-like" required>
                            <option value="Sr." <?php echo ($usuario['titulo'] === 'Sr.') ? 'selected' : ''; ?>>Sr.</option>
                            <option value="Sra." <?php echo ($usuario['titulo'] === 'Sra.') ? 'selected' : ''; ?>>Sra.</option>
                            <option value="Srta." <?php echo ($usuario['titulo'] === 'Srta.') ? 'selected' : ''; ?>>Srta.</option>
                            <option value="Prefiro não informar" <?php echo ($usuario['titulo'] === 'Prefiro não informar') ? 'selected' : ''; ?>>Prefiro não informar</option>
                        </select>
                    </div>

                    <div class="conta-form-grupo">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                    </div>
                    
                    <div class="conta-form-grupo">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($usuario['sobrenome']); ?>" required>
                    </div>

                    <div class="conta-form-grupo">
                        <label for="local_residencia">Local de residência (opcional)</label>
                        <input type="text" id="local_residencia" name="local_residencia" value="<?php echo htmlspecialchars($usuario['local_residencia'] ?? ''); ?>">
                    </div>

                    <div class="conta-form-grupo">
                        <label for="data_nascimento">Data de nascimento (opcional)</label>
                        <input type="text" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento'] ?? ''); ?>" placeholder="AAAA-MM-DD">
                    </div>

                    <button type="submit" class="btn-add-to-bag">Atualizar seu Perfil</button>
                </form>
            </div>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">

            <div class="form-secao">
                <h3 class="form-secao-titulo">NÚMERO DE CONTATO</h3>
                
                <form action="detalhes-pessoais.php" method="POST" class="conta-form">
                    <input type="hidden" name="acao" value="atualizar_telefone">

                    <p style="font-size: 0.9rem; color: #555;">
                        Adicione um número de telefone para que a Roseglaze
                        possa contactar onde quer que se encontre.
                    </p>

                    <div class="conta-form-grupo">
                        <label for="telefone">Telefone Principal</label>
                        <input type_="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
                    </div>
                    
                    <button type="submit" class="btn-add-to-bag">Guardar as Alterações</button>
                </form>
            </div>
            
        </div>
    </main>

    <?php require 'footer.php'; ?>
    
</body>
</html>