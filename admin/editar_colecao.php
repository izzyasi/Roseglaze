<?php
/*
 * Documentação: Editar Coleção (admin/editar_colecao.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';
$colecao = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_colecao = filter_var($_POST['id_colecao'], FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $imagem_principal = trim($_POST['imagem_principal']);
    $ativa = isset($_POST['ativa']) ? 1 : 0;

    if (empty($nome) || empty($imagem_principal)) {
        $erros[] = "Nome da Coleção e Caminho da Imagem são obrigatórios.";
    }

    if (empty($erros)) {
        
        $sql = "UPDATE colecoes SET 
                    nome = ?, 
                    descricao = ?, 
                    imagem_principal = ?, 
                    ativa = ?
                WHERE 
                    id = ?"; 
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome, 
                $descricao,
                $imagem_principal,
                $ativa,
                $id_colecao 
            ]);
            
            $mensagem_sucesso = "Coleção '".htmlspecialchars($nome)."' atualizada com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar coleção: " . $e->getMessage();
        }
    }
}

if (!isset($id_colecao)) {
    $id_colecao = filter_var($_GET['id'], FILTER_VALIDATE_INT);
}

if (!$id_colecao) {
    die("Erro: ID da coleção inválido ou não fornecido.");
}

try {
    $sql_colecao = "SELECT * FROM colecoes WHERE id = ?";
    $stmt_colecao = $pdo->prepare($sql_colecao);
    $stmt_colecao->execute([$id_colecao]);
    $colecao = $stmt_colecao->fetch(PDO::FETCH_ASSOC);
    
    if (!$colecao) {
        die("Coleção não encontrada.");
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar a coleção: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Editar <?php echo htmlspecialchars($colecao['nome']); ?></title>
    
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
            <h2 class="secao-titulo">Editar Coleção</h2>
            <a href="gerir_colecoes.php" style="text-decoration: none; color: #555;">
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

        <form action="editar_colecao.php?id=<?php echo $colecao['id']; ?>" method="POST" class="form-cadastro" style="max-width: none; background-color: #fff; padding: 30px; border-radius: 8px;">
            
            <input type="hidden" name="id_colecao" value="<?php echo $colecao['id']; ?>">
            
            <div class="form-grupo-minimalista">
                <label for="nome">Nome da Coleção</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($colecao['nome']); ?>" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="descricao">Descrição (Texto Editorial)</label>
                <textarea id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($colecao['descricao']); ?></textarea>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="imagem_principal">Caminho da Imagem Principal (ex: imagens/colecao_x.jpg)</label>
                <input type="text" id="imagem_principal" name="imagem_principal" value="<?php echo htmlspecialchars($colecao['imagem_principal']); ?>" required>
            </div>

            <div class="form-grupo-checkbox">
                <input type="checkbox" id="ativa" name="ativa" value="1" <?php echo ($colecao['ativa'] == 1) ? 'checked' : ''; ?>>
                <label for="ativa">
                    Tornar esta coleção "Ativa" (visível no site)?
                </label>
            </div>

            <button type="submit" class="btn-add-to-bag">Atualizar Coleção</button>
            
        </form>

    </main>

</body>
</html>