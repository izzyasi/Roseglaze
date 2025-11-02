/*
 * Documentação: JavaScript Principal
 */
    
    // --- ABRIR E FECHAR A SACOLA LATERAL ---

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

    if (btnAbrirSacola) {
        btnAbrirSacola.addEventListener('click', function(e) {
            e.preventDefault(); 
            abrirSacola();
        });
    }

    if (btnFecharSacola) {
        btnFecharSacola.addEventListener('click', fecharSacola);
    }
    
    if (sacolaOverlay) {
        sacolaOverlay.addEventListener('click', fecharSacola);
    }

    function atualizarSacolaLateral() {
        
        const listaItens = document.getElementById('sacola-itens-lista');
        const subtotalValor = document.getElementById('sacola-subtotal-valor');
        
        fetch('sacola_acoes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'get_sacola'
            })
        })
        .then(response => response.json())
        .then(data => {
    
            listaItens.innerHTML = ''; 

            if (data.sucesso && data.produtos.length > 0) {
                
                data.produtos.forEach(produto => {
                    
                    const precoFormatado = parseFloat(produto.preco).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });

                    const itemHTML = `
                        <div class="sacola-item">
                            <div class="sacola-item-img-placeholder"></div>
                            <div class="sacola-item-detalhes">
                                <h3>${produto.modelo}</h3>
                                <p>${produto.cor}</p>
                                <p>${precoFormatado}</p>
                            </div>
                        </div>
                    `;

                    listaItens.innerHTML += itemHTML;
                });

                subtotalValor.textContent = 'R$ ' + data.total;
                
            } else {
                listaItens.innerHTML = '<p class="sacola-vazia-msg">Sua sacola está vazia.</p>';
                subtotalValor.textContent = 'R$ 0,00';
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar a sacola:', error);
            listaItens.innerHTML = '<p class="sacola-vazia-msg">Erro ao carregar a sacola.</p>';
        });
    }