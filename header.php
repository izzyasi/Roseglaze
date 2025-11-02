<?php
/*
 * Documentação: Cabeçalho Reutilizável (header.php)
 */

$itens_na_sacola = 0;
if (isset($_SESSION['sacola'])) {
    $itens_na_sacola = count($_SESSION['sacola']);
}
?>

<header class="main-header">
        
    <nav class="nav-left">
        <a href="index.php">Sunglasses</a>
        <a href="#">Coleções</a>
        <a href="#">PFW25</a>
    </nav>
    
    <div class="logo-container">
        <a href="index.php">Roseglaze</a>
    </div>
    
    <nav class="nav-right">
    <a href="#"><span class="material-icons-outlined">search</span></a>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="minha_conta.php">
            <span class="material-icons-outlined">person_outline</span>
        </a>
    <?php else: ?>
        <a href="login.php">
            <span class="material-icons-outlined">person_outline</span>
        </a>
    <?php endif; ?>


    <a href="#sacola"> 
        <span class="material-icons-outlined">shopping_bag_outline</span>
        <?php if ($itens_na_sacola > 0): ?>
            <span class="sacola-contador">(<?php echo $itens_na_sacola; ?>)</span>
        <?php endif; ?>
    </a>
</nav>
</header>