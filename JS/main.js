document.addEventListener('DOMContentLoaded', function() {

    const header = document.querySelector('.main-header');
    if (header) {
        
        const scrollTrigger = 50;

        function handleScroll() {
            if (window.scrollY > scrollTrigger) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        }
        window.addEventListener('scroll', handleScroll);
        handleScroll();
    }

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
        const contadorSacola = document.getElementById('contador-sacola');

        fetch('sacola_acoes.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ acao: 'get_sacola' }) })
        .then(response => response.json())
        .then(data => {
            listaItens.innerHTML = ''; 
            if (data.sucesso && data.produtos.length > 0) {
                data.produtos.forEach(produto => {
                    const precoFormatado = parseFloat(produto.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        
                    let selectHTML = `<div class="sacola-item-controles">`;
                    selectHTML += `<label for="qtd-${produto.id}">Quantity</label>`;
                    selectHTML += `<select name="quantidade" class="seletor-qtd-sacola" id="qtd-${produto.id}" data-id-produto="${produto.id}">`;
 
                    for (let i = 1; i <= 10; i++) {
                        const selecionado = (i === produto.quantidade) ? 'selected' : '';
                        selectHTML += `<option value="${i}" ${selecionado}>${i}</option>`;
                    }
                    
                    selectHTML += `</select>`;
                    selectHTML += `<button class="btn-remover-item-sacola" data-id-produto="${produto.id}">Remove</button>`;
                    selectHTML += `</div>`;
                    const itemHTML = `
                        <div class="sacola-item" data-id-produto="${produto.id}">
                            <div class="sacola-item-img-placeholder"></div>
                            <div class="sacola-item-detalhes">
                                <h3>${produto.modelo}</h3>
                                <p>${produto.cor}</p>
                                <p>${precoFormatado}</p>
                                
                                ${selectHTML} 
                            </div>
                        </div>`;

                    listaItens.innerHTML += itemHTML;
                });
                
                subtotalValor.textContent = 'R$ ' + data.total;

            } else {
                listaItens.innerHTML = '<p class="sacola-vazia-msg">Sua sacola está vazia.</p>';
                subtotalValor.textContent = 'R$ 0,00';
            }

            if (contadorSacola) {
                contadorSacola.innerHTML = data.total_itens;
                if (data.total_itens > 0) {
                    contadorSacola.classList.remove('escondido');
                } else {
                    contadorSacola.classList.add('escondido');
                }
            }
        })
        .catch(error => { console.error('Erro ao atualizar a sacola:', error); listaItens.innerHTML = '<p class="sacola-vazia-msg">Erro ao carregar a sacola.</p>'; });
    }

    const listaItens = document.getElementById('sacola-itens-lista');
    
    if (listaItens) {
        listaItens.addEventListener('click', function(event) {
            const botaoRemover = event.target.closest('.btn-remover-item-sacola');

            if (!botaoRemover) {
                return;
            }

            event.preventDefault();

            const idProduto = botaoRemover.dataset.idProduto;

            fetch('sacola_acoes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    acao: 'remover',
                    id: idProduto 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    atualizarSacolaLateral();
                } else {
                    alert(data.mensagem);
                }
            })
            .catch(error => console.error('Erro ao remover:', error));
        });
    
        if (listaItens) {
            listaItens.addEventListener('change', function(event) {
            
                const seletorQtd = event.target.closest('.seletor-qtd-sacola');
            
                if (!seletorQtd) {
                    return; // Se não foi, não faz nada
                }

                const idProduto = seletorQtd.dataset.idProduto;
                const novaQuantidade = seletorQtd.value;

                fetch('sacola_acoes.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        acao: 'atualizar_quantidade',
                        id: idProduto,
                        quantidade: novaQuantidade
                    })
                })

                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        const subtotalValor = document.getElementById('sacola-subtotal-valor');
                        if (subtotalValor) {
                            subtotalValor.textContent = 'R$ ' + data.total_formatado;
                        }

                        const contadorSacola = document.getElementById('contador-sacola');
                        if (contadorSacola) {
                            contadorSacola.innerHTML = data.total_itens;
                            if (data.total_itens > 0) {
                                contadorSacola.classList.remove('escondido');
                            } else {
                                contadorSacola.classList.add('escondido');
                            }
                        }
                    
                    } else {
                        alert(data.mensagem);
                        atualizarSacolaLateral();
                    }
                })
                
                .catch(error => {
                    console.error('Erro ao atualizar quantidade:', error);
                    atualizarSacolaLateral(); 
                });
            });
        }
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
                                <button class="btn-wishlist-busca" data-id-produto="${produto.id}" data-acao-wishlist="adicionar">
                                    <svg class="wishlist-icon-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    
                                    <svg class="wishlist-icon-filled escondido" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                                         fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
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

    if (resultadosContainer) {
        
        resultadosContainer.addEventListener('click', function(e) {
 
            const botaoEstrela = e.target.closest('.btn-wishlist-busca');

            if (!botaoEstrela) {
                return;
            }
            
            e.preventDefault(); 

            const idProduto = botaoEstrela.dataset.idProduto;
            let acao = botaoEstrela.dataset.acaoWishlist; 

            fetch('wishlist_acoes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    acao: acao,
                    id_produto: idProduto
                })
            })
            .then(response => response.json())
            .then(data => {
                
                if (data.sucesso) {
                    const iconeVazio = botaoEstrela.querySelector('.wishlist-icon-empty');
                    const iconeCheio = botaoEstrela.querySelector('.wishlist-icon-filled');

                    if (data.nova_acao === 'remover') {
                        iconeVazio.classList.add('escondido');
                        iconeCheio.classList.remove('escondido');
                        botaoEstrela.dataset.acaoWishlist = 'remover';
                    } else {
                        iconeVazio.classList.remove('escondido');
                        iconeCheio.classList.add('escondido');
                        botaoEstrela.dataset.acaoWishlist = 'adicionar';
                    }
                    
                } else {
                    alert(data.mensagem);

                    if (data.mensagem.includes('logado')) {
                        window.location.href = 'login.php';
                    }
                }
            })
            .catch(error => {
                console.error('Erro na wishlist:', error);
                alert('Ocorreu um erro na sua lista de desejos.');
            });
        });
    }
});