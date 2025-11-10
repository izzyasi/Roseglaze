<?php
/*
 * Documentação: Adicionar Espaço (admin/adicionar_espaco.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_local = trim($_POST['nome_local']);
    $endereco_curto = trim($_POST['endereco_curto']);
    $imagem_local = trim($_POST['imagem_local']); 

    if (empty($nome_local) || empty($imagem_local)) {
        $erros[] = "Nome do Local e Caminho da Imagem são obrigatórios.";
    }

    if (empty($erros)) {
        
        $sql = "INSERT INTO espacos 
                    (nome_local, endereco_curto, imagem_local, data_registro) 
                VALUES 
                    (?, ?, ?, NOW())";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome_local, 
                $endereco_curto,
                $imagem_local
            ]);
            
            $mensagem_sucesso = "Espaço '".htmlspecialchars($nome_local)."' adicionado com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao adicionar espaço: " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Adicionar Espaço</title>
    
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="admin.css"> </head>
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
            <h2 class="secao-titulo">Adicionar Novo Espaço</h2>
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
                <label for="nome_local">Nome do Local (ex: Roseglaze Rio de Janeiro)</label>
                <input type="text" id="nome_local" name="nome_local" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="endereco_curto">Endereço Curto (ex: Ipanema, Rio de Janeiro)</label>
                <input type="text" id="endereco_curto" name="endereco_curto">
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="imagem_local">Caminho da Imagem (ex: imagens/espaco_rio.jpg)</label>
                <input type="text" id="imagem_local" name="imagem_local" required>
            </div>

            <button type="submit" class="btn-add-to-bag">Adicionar Espaço</button>
            
        </form>

    </main>

</body>
</html>