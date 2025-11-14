<?php

$trends = [];
if (isset($pdo)) { 
    try {
        $sql_trends = "SELECT id, modelo FROM oculos ORDER BY RAND() LIMIT 5";
        $stmt_trends = $pdo->prepare($sql_trends);
        $stmt_trends->execute();
        $trends = $stmt_trends->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    }
}

?>

<div id="busca-overlay-container" class="busca-overlay-container escondido">
    
    <div class="busca-conteudo">
        
        <button id="btn-fechar-busca" class="btn-fechar-busca">
            <span class="material-icons-outlined">close</span>
        </button>
        
        <form action="busca.php" method="GET" class="form-busca-overlay">
            <span class="material-icons-outlined">search</span>
            <input 
                type="search" 
                name="q" 
                placeholder="Please enter the search term(s)" 
                class="campo-busca-overlay"
            >
        </form>
        
        <div id="busca-trends" class="busca-sugestoes">
            <h4 class="busca-sugestoes-titulo">SEARCH TRENDS</h4>
            
            <div class="busca-trends-grid"> <?php foreach ($trends as $produto_trend): ?>
                    <a href="produto.php?id=<?php echo $produto_trend['id']; ?>" class="busca-item-sugestao">
                        <div class="busca-item-img-placeholder"></div>
                        <p><?php echo htmlspecialchars($produto_trend['modelo']); ?></p> 
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="busca-resultados" class="busca-sugestoes escondido">
            </div>

    </div> </div> 
