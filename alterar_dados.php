<?php
/*
 * Documentação: Página Alterar Dados (alterar_dados.php)
 */

require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit; 
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roseglaze - Detalhes da Conta</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <?php require 'header.php'; ?>

    <main class="container-cadastro" style="max-width: 800px;">
        
        <div class="cadastro-header">
            <div class="cadastro-abas">
                <a href="minha_conta.php" class="aba-inativa">MINHA CONTA</a>
                <a href="alterar_dados.php" class="aba-ativa">DETALHES DA CONTA</a>
            </div>
        </div>
        
        <div class="conta-painel">
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

            <div class="danger-zone">
                <h3 style="color: #c00;">Deletar Conta</h3>
                <p style="color: #555; line-height: 1.6;">
                    Atenção: Esta ação é permanente e não pode ser desfeita.
                    Todos os seus dados, incluindo o seu histórico de pedidos
                    e detalhes pessoais, serão permanentemente apagados.
                </p>
                
                <form action="deletar_conta.php" method="POST" id="form-deletar-conta">
                    <button type="submit" class="btn-perigo">
                        Sim, eu entendo. Deletar minha conta permanentemente.
                    </button>
                </form>
            </div>
            
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        
            const formDeletar = document.getElementById('form-deletar-conta');
        
            if (formDeletar) {
                formDeletar.addEventListener('submit', function(e) {
                
                    const confirmacao = confirm("Tem a certeza que deseja deletar a sua conta? Esta ação é permanente.");
                
                    if (confirmacao === false) {
                        e.preventDefault(); 
                    }
                });
            }
        });
    </script>

</body>
</html>
