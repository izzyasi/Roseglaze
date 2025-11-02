<?php
/*
 * Documentação: Sacola Lateral 
 */
?>

<div id="sacola-lateral-container" class="sacola-lateral-container escondido">
    
    <div id="sacola-overlay" class="sacola-overlay"></div>
    
    <div class="sacola-gaveta">
        
        <div class="sacola-header">
            <h3 class="sacola-titulo">Sacola de Compras</h3>
            <button id="btn-fechar-sacola" class="btn-fechar-sacola">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        
        <div id="sacola-itens-lista" class="sacola-itens-lista">
            
            <p class="sacola-vazia-msg">Sua sacola está vazia.</p>
            
        </div>
        
        <div class="sacola-footer">
            <div class="sacola-subtotal">
                <span>Subtotal</span>
                <span id="sacola-subtotal-valor">R$ 0,00</span>
            </div>
            <a href="checkout.php" class="btn-add-to-bag">Finalizar Compra</a>
        </div>
        
    </div> </div>