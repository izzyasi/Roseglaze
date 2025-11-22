<?php 
require 'conexao.php'; 

try {
    $stmt = $pdo->query("SELECT * FROM lojas");
    $lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $lojas = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lojas | ROSEGLAZE</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-lojas">
        
        <h1>NOSSAS LOJAS</h1>

        <div class="lojas-grid">
            
            <?php if (count($lojas) > 0): ?>
                <?php foreach ($lojas as $loja): ?>
                    
                    <div class="loja-card">
                        
                        <?php 
                            $img_url = !empty($loja['imagem']) ? 'imagens/' . $loja['imagem'] : ''; 
                        ?>
                        <div class="loja-img" 
                             style="background-image: url('<?php echo $img_url; ?>'); 
                                    background-color: #eee;">
                        </div>

                        <div class="loja-info">
                            <h3><?php echo htmlspecialchars($loja['nome']); ?></h3>
                            
                            <p><?php echo $loja['endereco']; ?></p>
                            <p style="margin-top: 10px; font-size: 0.85rem; color: #888;">
                                <?php echo $loja['horario']; ?>
                            </p>
                            
                            </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; width: 100%;">Nenhuma loja encontrada.</p>
            <?php endif; ?>

        </div>

    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>
    
    <script src="js/main.js"></script>

</body>
</html>