<?php

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';
$loja = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_loja = filter_var($_POST['id_espaco'], FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $horario = trim($_POST['horario']);
    $imagem = trim($_POST['imagem']);

    if (empty($nome) || empty($imagem)) {
        $erros[] = "Nome e Imagem são obrigatórios.";
    }

    if (empty($erros)) {   
        $sql = "UPDATE lojas SET 
                    nome = ?, 
                    endereco = ?, 
                    horario = ?,
                    imagem = ?
                WHERE 
                    id = ?"; 
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome, 
                $endereco,
                $horario,
                $imagem,
                $id_loja
            ]);
            
            $mensagem_sucesso = "Loja '".htmlspecialchars($nome)."' atualizada com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar loja: " . $e->getMessage();
        }
    }
}

if (!isset($id_loja)) {
    $id_loja = filter_var($_GET['id'], FILTER_VALIDATE_INT);
}

if (!$id_loja) {
    die("Erro: ID da loja inválido ou não fornecido.");
}

try {
    $sql_loja = "SELECT * FROM lojas WHERE id = ?";
    $stmt_loja = $pdo->prepare($sql_loja);
    $stmt_loja->execute([$id_loja]);
    $loja = $stmt_loja->fetch(PDO::FETCH_ASSOC);
    
    if (!$loja) {
        die("Loja não encontrada.");
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar a loja: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Editar <?php echo htmlspecialchars($loja['nome']); ?></title>
    
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
            <h2 class="secao-titulo">Editar Loja</h2>
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

        <form action="editar_espaco.php?id=<?php echo $loja['id']; ?>" method="POST" class="form-cadastro" style="max-width: none; background-color: #fff; padding: 30px; border-radius: 8px;">
            
            <input type="hidden" name="id_espaco" value="<?php echo $loja['id']; ?>">
            
            <div class="form-grupo-minimalista">
                <label for="nome">Nome da Loja</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($loja['nome']); ?>" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($loja['endereco']); ?>">
            </div>

            <div class="form-grupo-minimalista">
                <label for="horario">Horário</label>
                <input type="text" id="horario" name="horario" value="<?php echo htmlspecialchars($loja['horario'] ?? ''); ?>">
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="imagem">Caminho da Imagem</label>
                <input type="text" id="imagem" name="imagem" value="<?php echo htmlspecialchars($loja['imagem']); ?>" required>
            </div>

            <button type="submit" class="btn-add-to-bag">Atualizar Loja</button>
            
        </form>

    </main>

</body>
</html>