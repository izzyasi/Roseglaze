<?php

require 'conexao.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Pedido Confirmado</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-produtos" style="text-align: center; min-height: 40vh;">
        
        <h2 class="secao-titulo">Pedido Confirmado!</h2>
        
        <div style="font-size: 1.1rem; padding: 20px;">
            <p>Obrigado pela sua compra.</p>
            <p>Recebemos o seu pedido e ele já está sendo processado.</p>
            <p>Enviaremos um e-mail de confirmação para o endereço fornecido.</p>
        </div>

        <a href="index.php" class="btn-add-to-bag" style="width: 300px; text-decoration: none;">Voltar à Homepage</a>

    </main>

    <?php require 'footer.php'; ?>
    <?php require 'sacola_lateral.php'; ?>
    <?php require 'busca_overlay.php'; ?>

</body>
</html>