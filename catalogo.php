<?php

require 'conexao.php';

$tipo_produto = $_GET['tipo'] ?? null;
$titulo_pagina = "Nosso Catálogo"; 
if ($tipo_produto === 'Sol') {
    $titulo_pagina = "Óculos de Sol";
} elseif ($tipo_produto === 'Sem Grau') {
    $titulo_pagina = "Óculos Sem Grau";
}

$produtos = [];
try {
    if ($tipo_produto) {
        $sql = "SELECT * FROM oculos WHERE tipo_produto = ? ORDER BY data_registro DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tipo_produto]);
    } else {
        $sql = "SELECT * FROM oculos ORDER BY data_registro DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - <?php echo htmlspecialchars($titulo_pagina); ?></title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="admin/admin.css">
</head>
<body style="background-color: #f0f0f0;"> <?php require 'header.php'; ?>

    <main class="container-produtos">
        
        <h2 class="secao-titulo"><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        
        <div class="product-grid">
            
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $oculo): ?>

                    <div class="product-card">
                        <a href="produto.php?id=<?php echo htmlspecialchars($oculo['id']); ?>">
                            <div class="product-image-placeholder"></div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($oculo['modelo']); ?></h3>
                                <p>R$ <?php echo number_format($oculo['preco'], 2, ',', '.'); ?></p>
                            </div>
                        </a>
                    </div>
                
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1;">
                    Nenhum produto encontrado nesta categoria.
                </p>
            <?php endif; ?>

        </div> </main>


    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>

</body>
</html>