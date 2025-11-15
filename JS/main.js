document.addEventListener('DOMContentLoaded', function() {

    const sacolaContainer = document.getElementById('sacola-lateral-container');
    const btnFecharSacola = document.getElementById('btn-fechar-sacola');
    const sacolaOverlay = document.getElementById('sacola-overlay');
    const btnAbrirSacola = document.querySelector('.nav-right a[href="#sacola"]');

    function abrirSacola() {
        if (sacolaContainer) {
            sacolaContainer.classList.remove('escondido');
            atualizarSacolaLateral();
        }
    }
    function fecharSacola() {
        if (sacolaContainer) {
            sacolaContainer.classList.add('escondido');
        }
    }
    if (btnAbrirSacola) { btnAbrirSacola.addEventListener('click', function(e) { e.preventDefault(); abrirSacola(); }); }
    if (btnFecharSacola) { btnFecharSacola.addEventListener('click', fecharSacola); }
    if (sacolaOverlay) { sacolaOverlay.addEventListener('click', fecharSacola); }

    function atualizarSacolaLateral() {
        const listaItens = document.getElementById('sacola-itens-lista');
        const subtotalValor = document.getElementById('sacola-subtotal-valor');
        fetch('sacola_acoes.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ acao: 'get_sacola' }) })
        .then(response => response.json())
        .then(data => {
            listaItens.innerHTML = ''; 
            if (data.sucesso && data.produtos.length > 0) {
                data.produtos.forEach(produto => {
                    const precoFormatado = parseFloat(produto.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    const itemHTML = `<div class="sacola-item"><div class="sacola-item-img-placeholder"></div><div class="sacola-item-detalhes"><h3>${produto.modelo}</h3><p>${produto.cor}</p><p>${precoFormatado}</p></div></div>`;
                    listaItens.innerHTML += itemHTML;
                });
                subtotalValor.textContent = 'R$ ' + data.total;
            } else {
                listaItens.innerHTML = '<p class="sacola-vazia-msg">Sua sacola está vazia.</p>';
                subtotalValor.textContent = 'R$ 0,00';
            }
        })
        .catch(error => { console.error('Erro ao atualizar a sacola:', error); listaItens.innerHTML = '<p class="sacola-vazia-msg">Erro ao carregar a sacola.</p>'; });
    }
    
    const buscaContainer = document.getElementById('busca-overlay-container');
    const btnFecharBusca = document.getElementById('btn-fechar-busca');
    const btnAbrirBusca = document.querySelector('.nav-right a[href="#busca"]');
    const formBusca = document.querySelector('.form-busca-overlay');
    const trendsContainer = document.getElementById('busca-trends');
    const resultadosContainer = document.getElementById('busca-resultados');

    function abrirBusca() {
        if (buscaContainer) {
            buscaContainer.classList.remove('escondido');
            if (trendsContainer) trendsContainer.classList.remove('escondido');
            if (resultadosContainer) resultadosContainer.classList.add('escondido');
        }
    }
    function fecharBusca() {
        if (buscaContainer) {
            buscaContainer.classList.add('escondido');
        }
    }
    if (btnAbrirBusca) { btnAbrirBusca.addEventListener('click', function(e) { e.preventDefault(); abrirBusca(); }); }
    if (btnFecharBusca) { btnFecharBusca.addEventListener('click', fecharBusca); }
    if (formBusca) {
        formBusca.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const termo = this.querySelector('.campo-busca-overlay').value;
            
            if (termo.trim() === '') return;

            if (trendsContainer) trendsContainer.classList.add('escondido');
            if (resultadosContainer) {
                resultadosContainer.classList.remove('escondido');
                resultadosContainer.innerHTML = '<p class="sacola-vazia-msg">A buscar...</p>';
            }

            fetch('busca_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ termo_busca: termo })
            })
            .then(response => response.json())
            .then(data => {
                
                resultadosContainer.innerHTML = '';
                
                if (data.sucesso && data.produtos.length > 0) {
                    let htmlResultados = '<div class="busca-resultados-grid">';
                    
                    data.produtos.forEach(produto => {

                        const precoFormatado = parseFloat(produto.preco).toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        });

                        htmlResultados += `
                            <div class="product-card">
                                <a href="produto.php?id=${produto.id}">
                                    <div class="product-image-placeholder"></div>
                                    <div class="product-info">
                                        <h3>${produto.modelo}</h3>
                                        <p>${precoFormatado}</p> 
                                    </div>
                                </a>
                                <button class="btn-wishlist-busca" data-id-produto="${produto.id}">
                                    <span class="material-icons-outlined">star_border</span>
                                </button>
                            </div>
                        `;
                    });
                    
                    htmlResultados += '</div>';
                    resultadosContainer.innerHTML = htmlResultados;
                    
                } else {
                    resultadosContainer.innerHTML = '<p class="sacola-vazia-msg">Nenhum produto encontrado para "'+termo+'".</p>';
                }
            })
            .catch(error => {
                console.error('Erro ao processar a busca:', error);
                resultadosContainer.innerHTML = '<p class="sacola-vazia-msg">Ocorreu um erro ao processar os resultados.</p>';
            });
        });
    }

});