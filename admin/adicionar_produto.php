<?php
/*
 * Documentação: Adicionar Produto (admin/adicionar_produto.php)
 */

require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit; 
}

$erros = [];
$mensagem_sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        
        $sql = "INSERT INTO oculos 
                    (modelo, tipo_produto, cor, preco, stock, colecao_id, em_destaque, data_registro) 
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $modelo, 
                $tipo_produto, 
                $cor, 
                $preco, 
                $stock, 
                ($colecao_id ?: NULL), 
                $em_destaque
            ]);
            
            $mensagem_sucesso = "Produto '".htmlspecialchars($modelo)."' adicionado com sucesso!";
            
        } catch (PDOException $e) {
            $erros[] = "Erro ao adicionar produto: " . $e->getMessage();
        }
    }
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
    <title>Roseglaze Admin - Adicionar Produto</title>
    
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
            <h2 class="secao-titulo">Adicionar Novo Produto</h2>
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

        <form action="adicionar_produto.php" method="POST" class="form-cadastro" style="max-width: none; background-color: #fff; padding: 30px; border-radius: 8px;">
            
            <div class="form-grupo-minimalista">
                <label for="modelo">Modelo (Nome do Produto)</label>
                <input type="text" id="modelo" name="modelo" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="tipo_produto">Tipo de Produto</label>
                <select id="tipo_produto" name="tipo_produto" required>
                    <option value="" disabled selected>Selecione o tipo</option>
                    <option value="Sol">Sol</option>
                    <option value="Sem Grau">Sem Grau</option>
                </select>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="colecao_id">Coleção (Opcional)</label>
                <select id="colecao_id" name="colecao_id">
                    <option value="">Nenhuma</option>
                    <?php foreach ($colecoes as $colecao): ?>
                        <option value="<?php echo $colecao['id']; ?>">
                            <?php echo htmlspecialchars($colecao['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="cor">Cor</label>
                <input type="text" id="cor" name="cor">
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="preco">Preço (ex: 350.00)</label>
                <input type="text" id="preco" name="preco" required>
            </div>
            
            <div class="form-grupo-minimalista">
                <label for="stock">Stock (Quantidade)</label>
                <input type="number" id="stock" name="stock" value="0" required>
            </div>

            <div class="form-grupo-checkbox">
                <input type="checkbox" id="em_destaque" name="em_destaque" value="1">
                <label for="em_destaque">
                    Marcar como "Nossa Seleção" (Destaque)?
                </label>
            </div>

            <button type="submit" class="btn-add-to-bag">Adicionar Produto</button>
            
        </form>

    </main>

</body>
</html>