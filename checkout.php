<?php

require 'conexao.php';

$produtos_na_sacola = []; 
$preco_total = 0;

if (isset($_SESSION['sacola']) && !empty($_SESSION['sacola'])) {
    
    $ids_na_sacola = $_SESSION['sacola'];
    $placeholders = implode(',', array_fill(0, count($ids_na_sacola), '?'));
    $sql = "SELECT * FROM oculos WHERE id IN ($placeholders)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids_na_sacola);
        $produtos_na_sacola = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($produtos_na_sacola as $produto) {
            $preco_total += $produto['preco'];
        }
    } catch (PDOException $e) {
        die("Erro ao buscar produtos da sacola: " . $e->getMessage());
    }
}

if (empty($produtos_na_sacola)) {
    header('Location: index.php'); 
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Checkout</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body style="background-color: #f0f0f0;"> <?php require 'header.php'; ?>

    <main class="container-produtos">
        
        <h2 class="secao-titulo">Checkout</h2>
        
        <form action="pedido_salvar.php" method="POST" class="checkout-layout">
            
            <div class="checkout-formulario">
                <h3>Informações de Entrega</h3>
                
                <div class="form-grupo-minimalista">
                    <label for="nome_cliente">Nome Completo</label>
                    <input type="text" id="nome_cliente" name="nome_cliente" required>
                </div>
                
                <div class="form-grupo-minimalista">
                    <label for="email_cliente">Email</label>
                    <input type="email" id="email_cliente" name="email_cliente" required>
                </div>

                <div class="form-grupo-minimalista">
                    <label for="endereco_entrega">Endereço Completo</label>
                    <textarea id="endereco_entrega" name="endereco_entrega" rows="4" required></textarea>
                </div>
            </div>

            <div class="checkout-sumario">
                <h3>Sua Sacola</h3>
                
                <?php foreach ($produtos_na_sacola as $produto): ?>
                    <div class="checkout-item">
                        <div class="checkout-item-img-placeholder"></div>
                        <div class="checkout-item-detalhes">
                            <span><?php echo htmlspecialchars($produto['modelo']); ?> (x1)</span>
                            <span>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="checkout-total">
                    <span>Total</span>
                    <span>R$ <?php echo number_format($preco_total, 2, ',', '.'); ?></span>
                </div>

                <button type="submit" class="btn-add-to-bag">Finalizar Pedido</button>
            </div>
            
        </form> </main>

    <?php require 'footer.php'; ?>

</body>
</html>