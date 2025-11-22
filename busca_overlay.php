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
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                fill="none" stroke="currentColor" stroke-width="2" 
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        
        <form action="busca.php" method="GET" class="form-busca-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                fill="none" stroke="currentColor" stroke-width="2" 
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input 
                type="search" 
                name="q" 
                placeholder="Veyn..." 
                class="campo-busca-overlay"
            >
        </form>

        <div id="busca-resultados" class="busca-sugestoes escondido">
            </div>

    </div> </div> 
