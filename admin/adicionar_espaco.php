<?php

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $horario = trim($_POST['horario']);
    $imagem = trim($_POST['imagem']); 

    if (empty($nome) || empty($imagem)) {
        $erros[] = "Nome da Loja e Caminho da Imagem são obrigatórios.";
    }

    if (empty($erros)) {
        
        $sql = "INSERT INTO lojas 
                    (nome, endereco, horario, imagem) 
                VALUES 
                    (?, ?, ?, ?)";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome, 
                $endereco,
                $horario,
                $imagem
            ]);
            
            $mensagem_sucesso = "Loja '".htmlspecialchars($nome)."' adicionada com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao adicionar loja: " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Adicionar Loja</title>
    
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="admin.css"> 
</head>
<body style="background-color: #f0f0f0;">

    <header class="admin-header">
        <div classs="admin-header-logo">
            <a href="index.php">Roseglaze Admin</a>
        </div>
        <div class="admin-header-user">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?></span>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <main class="admin-dashboard container-produtos">
        
        <div class="admin-page-header">
            <h2 class="secao-titulo">Adicionar Nova Loja</h2>
            <a href="gerir_espacos.php" style="text-decoration: none; color: #555;">
                &larr; Voltar para a lista
            </a>
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
                </div>
            <?php endif; ?>
        </div>

        <form action="adicionar_espaco.php" method="POST" class="form-cadastro" style="max-width: none; background-color: #fff; padding: 30px; border-radius: 8px;">
            
            <div class="form-grupo-minimalista">
                <label for="nome">Nome da Loja (ex: Roseglaze Rio)</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="endereco">Endereço Completo</label>
                <input type="text" id="endereco" name="endereco">
            </div>

            <div class="form-grupo-minimalista">
                <label for="horario">Horário de Funcionamento (ex: Seg-Sex 10h às 20h)</label>
                <input type="text" id="horario" name="horario">
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="imagem">Caminho da Imagem (ex: imagens/loja_rio.jpg)</label>
                <input type="text" id="imagem" name="imagem" required>
            </div>

            <button type="submit" class="btn-add-to-bag">Adicionar Loja</button>
            
        </form>

    </main>

</body>
</html>