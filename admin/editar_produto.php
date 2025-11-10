<?php
/*
 * Documentação: Editar Produto (admin/editar_produto.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';
$produto = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = filter_var($_POST['id_produto'], FILTER_VALIDATE_INT);
    $modelo = trim($_POST['modelo']);
    $tipo_produto = trim($_POST['tipo_produto']);
    $cor = trim($_POST['cor']);
    $preco = trim($_POST['preco']);
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $colecao_id = filter_var($_POST['colecao_id'], FILTER_VALIDATE_INT);
    $em_destaque = isset($_POST['em_destaque']) ? 1 : 0;

    if (empty($modelo) || empty($preco) || empty($tipo_produto)) {
        $erros[] = "Modelo, Tipo e Preço são obrigatórios.";
    }

    if (empty($erros)) {
        
        $sql = "UPDATE oculos SET 
                    modelo = ?, 
                    tipo_produto = ?, 
                    cor = ?, 
                    preco = ?, 
                    stock = ?, 
                    colecao_id = ?, 
                    em_destaque = ?
                WHERE 
                    id = ?"; 
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $modelo, 
                $tipo_produto, 
                $cor, 
                $preco, 
                $stock, 
                ($colecao_id ?: NULL),
                $em_destaque,
                $id_produto
            ]);
            
            $mensagem_sucesso = "Produto '".htmlspecialchars($modelo)."' atualizado com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar produto: " . $e->getMessage();
        }
    }
}

if (!isset($id_produto)) {
    $id_produto = filter_var($_GET['id'], FILTER_VALIDATE_INT);
}

if (!$id_produto) {
    die("Erro: ID do produto inválido ou não fornecido.");
}

try {
    $sql_produto = "SELECT * FROM oculos WHERE id = ?";
    $stmt_produto = $pdo->prepare($sql_produto);
    $stmt_produto->execute([$id_produto]);
    $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        die("Produto não encontrado.");
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar o produto: " . $e->getMessage());
}

$colecoes = [];
try {
    $sql_colecoes = "SELECT id, nome FROM colecoes ORDER BY nome";
    $stmt_colecoes = $pdo->prepare($sql_colecoes);
    $stmt_colecoes->execute();
    $colecoes = $stmt_colecoes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erros[] = "Erro ao buscar coleções: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze Admin - Editar <?php echo htmlspecialchars($produto['modelo']); ?></title>
    
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
            <h2 class="secao-titulo">Editar Produto</h2>
            <a href="gerir_produtos.php" style="text-decoration: none; color: #555;">
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

        <form action="editar_produto.php?id=<?php echo $produto['id']; ?>" method="POST" class="form-cadastro" style="max-width: none; background-color: #fff; padding: 30px; border-radius: 8px;">
            
            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
            
            <div class="form-grupo-minimalista">
                <label for="modelo">Modelo (Nome do Produto)</label>
                <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($produto['modelo']); ?>" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="tipo_produto">Tipo de Produto</label>
                <select id="tipo_produto" name="tipo_produto" required>
                    <option value="" disabled>Selecione o tipo</option>
                    <option value="Sol" <?php echo ($produto['tipo_produto'] === 'Sol') ? 'selected' : ''; ?>>Sol</option>
                    <option value="Sem Grau" <?php echo ($produto['tipo_produto'] === 'Sem Grau') ? 'selected' : ''; ?>>Sem Grau</option>
                </select>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="colecao_id">Coleção (Opcional)</label>
                <select id="colecao_id" name="colecao_id">
                    <option value="">Nenhuma</option>
                    <?php foreach ($colecoes as $colecao): ?>
                        <option value="<?php echo $colecao['id']; ?>" <?php echo ($produto['colecao_id'] == $colecao['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($colecao['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="cor">Cor</label>
                <input type="text" id="cor" name="cor" value="<?php echo htmlspecialchars($produto['cor']); ?>">
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="preco">Preço (ex: 350.00)</label>
                <input type="text" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="stock">Stock (Quantidade)</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($produto['stock']); ?>" required>
            </div>

            <div class="form-grupo-checkbox">
                <input type="checkbox" id="em_destaque" name="em_destaque" value="1" <?php echo ($produto['em_destaque'] == 1) ? 'checked' : ''; ?>>
                <label for="em_destaque">
                    Marcar como "Nossa Seleção" (Destaque)?
                </label>
            </div>

            <button type="submit" class="btn-add-to-bag">Atualizar Produto</button>
            
        </form>

    </main>

</body>
</html>