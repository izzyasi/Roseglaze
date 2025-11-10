<?php
/*
 * Documentação: Editar Espaço (admin/editar_espaco.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';
$espaco = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_espaco = filter_var($_POST['id_espaco'], FILTER_VALIDATE_INT);
    $nome_local = trim($_POST['nome_local']);
    $endereco_curto = trim($_POST['endereco_curto']);
    $imagem_local = trim($_POST['imagem_local']);

    if (empty($nome_local) || empty($imagem_local)) {
        $erros[] = "Nome do Local e Caminho da Imagem são obrigatórios.";
    }

    if (empty($erros)) {   
        $sql = "UPDATE espacos SET 
                    nome_local = ?, 
                    endereco_curto = ?, 
                    imagem_local = ?
                WHERE 
                    id = ?"; 
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome_local, 
                $endereco_curto,
                $imagem_local,
                $id_espaco
            ]);
            
            $mensagem_sucesso = "Espaço '".htmlspecialchars($nome_local)."' atualizado com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar espaço: " . $e->getMessage();
        }
    }
}

if (!isset($id_espaco)) {
    $id_espaco = filter_var($_GET['id'], FILTER_VALIDATE_INT);
}

if (!$id_espaco) {
    die("Erro: ID do espaço inválido ou não fornecido.");
}

try {
    $sql_espaco = "SELECT * FROM espacos WHERE id = ?";
    $stmt_espaco = $pdo->prepare($sql_espaco);
    $stmt_espaco->execute([$id_espaco]);
    $espaco = $stmt_espaco->fetch(PDO::FETCH_ASSOC);
    
    if (!$espaco) {
        die("Espaço não encontrado.");
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar o espaço: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Editar <?php echo htmlspecialchars($espaco['nome_local']); ?></title>
    
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
            <h2 class="secao-titulo">Editar Espaço</h2>
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

        <form action="editar_espaco.php?id=<?php echo $espaco['id']; ?>" method="POST" class="form-cadastro" style="max-width: none; background-color: #fff; padding: 30px; border-radius: 8px;">
            
            <input type="hidden" name="id_espaco" value="<?php echo $espaco['id']; ?>">
            
            <div class="form-grupo-minimalista">
                <label for="nome_local">Nome do Local</label>
                <input type="text" id="nome_local" name="nome_local" value="<?php echo htmlspecialchars($espaco['nome_local']); ?>" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="endereco_curto">Endereço Curto</label>
                <input type="text" id="endereco_curto" name="endereco_curto" value="<?php echo htmlspecialchars($espaco['endereco_curto']); ?>">
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="imagem_local">Caminho da Imagem</label>
                <input type="text" id="imagem_local" name="imagem_local" value="<?php echo htmlspecialchars($espaco['imagem_local']); ?>" required>
            </div>

            <button type="submit" class="btn-add-to-bag">Atualizar Espaço</button>
            
        </form>

    </main>

</body>
</html>