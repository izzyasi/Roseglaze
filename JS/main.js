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

    if (btnAbrirSacola) btnAbrirSacola.addEventListener('click', function(e) { e.preventDefault(); abrirSacola(); });
    if (btnFecharSacola) btnFecharSacola.addEventListener('click', fecharSacola);
    if (sacolaOverlay) sacolaOverlay.addEventListener('click', fecharSacola);

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

                    const imgUrl = produto.imagem ? 'imagens/' + produto.imagem : '';
                    const styleImg = imgUrl ? `background-image: url('${imgUrl}');` : 'background-color: #f0f0f0;';

                    const itemHTML = `
                        <div class="sacola-item" data-id-produto="${produto.id}">
                            <div class="sacola-item-img-placeholder" style="${styleImg} background-size: cover; background-position: center;"></div>
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
                if (data.total_itens > 0) contadorSacola.classList.remove('escondido');
                else contadorSacola.classList.add('escondido');
            }
        })
        .catch(error => { console.error('Erro sacola:', error); });
    }

    const listaItens = document.getElementById('sacola-itens-lista');
    if (listaItens) {
        // Remover Item
        listaItens.addEventListener('click', function(event) {
            const botaoRemover = event.target.closest('.btn-remover-item-sacola');
            if (botaoRemover) {
                event.preventDefault();
                fetch('sacola_acoes.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ acao: 'remover', id: botaoRemover.dataset.idProduto }) })
                .then(res => res.json()).then(d => { if(d.sucesso) atualizarSacolaLateral(); });
            }
        });

        listaItens.addEventListener('change', function(event) {
            const seletor = event.target.closest('.seletor-qtd-sacola');
            if (seletor) {
                fetch('sacola_acoes.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ acao: 'atualizar_quantidade', id: seletor.dataset.idProduto, quantidade: seletor.value }) })
                .then(res => res.json()).then(d => { 
                    if(d.sucesso) {
                         document.getElementById('sacola-subtotal-valor').textContent = 'R$ ' + d.total_formatado;
                         const cont = document.getElementById('contador-sacola');
                         if(cont) cont.innerHTML = d.total_itens;
                    } else { atualizarSacolaLateral(); }
                });
            }
        });
    }

    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator-bar');
    const nextBtnHero = document.getElementById('next-slide');
    const prevBtnHero = document.getElementById('prev-slide');
    const heroTitle = document.querySelector('.hero-text h1');
    const heroBtn = document.querySelector('.hero-buttons a');

    if (slides.length > 0) {
        let currentSlide = 0;
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            slides[index].classList.add('active');
    
            if (indicators.length) {
                indicators.forEach(b => b.classList.remove('active'));
                if (indicators[index]) indicators[index].classList.add('active');
            }

            const el = slides[index];
            if (heroTitle && el.dataset.title) heroTitle.textContent = el.dataset.title;
            if (heroBtn && el.dataset.button) heroBtn.textContent = el.dataset.button;
            if (heroBtn && el.dataset.link) heroBtn.href = el.dataset.link;

            const header = document.querySelector('.main-header');
            if (header) {
                const colorMode = el.dataset.headerColor; 

                if (colorMode === 'white') {
                    header.classList.add('header-white-content');
                } else {
                    header.classList.remove('header-white-content');
                }
            }
        }

        if(nextBtnHero) nextBtnHero.addEventListener('click', () => { currentSlide = (currentSlide + 1) % totalSlides; showSlide(currentSlide); });
        if(prevBtnHero) prevBtnHero.addEventListener('click', () => { currentSlide = (currentSlide - 1 + totalSlides) % totalSlides; showSlide(currentSlide); });
        
        indicators.forEach((bar, i) => { bar.addEventListener('click', () => { currentSlide = i; showSlide(currentSlide); }); });
        
        setInterval(() => { currentSlide = (currentSlide + 1) % totalSlides; showSlide(currentSlide); }, 15000);
    }

    const buscaContainer = document.getElementById('busca-overlay-container');
    const btnFecharBusca = document.getElementById('btn-fechar-busca');
    const btnAbrirBusca = document.querySelector('.nav-right a[href="#busca"]');
    const formBusca = document.querySelector('.form-busca-overlay');
    const trendsContainer = document.getElementById('busca-trends');
    const resultadosContainer = document.getElementById('busca-resultados');

    function abrirBusca() { if (buscaContainer) buscaContainer.classList.remove('escondido'); }
    function fecharBusca() { if (buscaContainer) buscaContainer.classList.add('escondido'); }

    if (btnAbrirBusca) btnAbrirBusca.addEventListener('click', function(e) { e.preventDefault(); abrirBusca(); });
    if (btnFecharBusca) btnFecharBusca.addEventListener('click', fecharBusca);

    if (formBusca) {
        formBusca.addEventListener('submit', function(e) {
            e.preventDefault();
            const termo = this.querySelector('.campo-busca-overlay').value;
            if (termo.trim() === '') return;

            if (trendsContainer) trendsContainer.classList.add('escondido');
            if (resultadosContainer) {
                resultadosContainer.classList.remove('escondido');
                resultadosContainer.innerHTML = '<p class="sacola-vazia-msg">Searching...</p>';
            }

            fetch('busca_api.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ termo_busca: termo }) })
            .then(res => res.json())
            .then(data => {
                resultadosContainer.innerHTML = '';
                if (data.sucesso && data.produtos.length > 0) {
                    let html = '<div class="busca-resultados-grid">';
                    data.produtos.forEach(p => {
                        const preco = parseFloat(p.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        const img = p.imagem ? 'imagens/' + p.imagem : '';
                        const style = img ? `background-image: url('${img}');` : 'background-color: #f0f0f0;';
                        
                        html += `
                            <div class="product-card">
                                <a href="produto.php?id=${p.id}">
                                    <div class="product-image-placeholder" style="${style} background-size: cover; background-position: center; width: 100%; padding-top: 100%;"></div>
                                    <div class="product-info"><h3>${p.modelo}</h3><p>${preco}</p></div>
                                </a>
                                <button class="btn-wishlist-busca" data-id-produto="${p.id}" data-acao-wishlist="adicionar" style="position: absolute; top: 10px; right: 10px; background:none; border:none; cursor:pointer;">
                                     <svg class="wishlist-icon-empty" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                     <svg class="wishlist-icon-filled escondido" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                </button>
                            </div>`;
                    });
                    html += '</div>';
                    resultadosContainer.innerHTML = html;
                } else {
                    resultadosContainer.innerHTML = '<p class="sacola-vazia-msg">No results found.</p>';
                }
            })
            .catch(err => console.error(err));
        });
    }

    document.addEventListener('click', function(e) {
        const btnWishlist = e.target.closest('.btn-wishlist-busca');

        if (btnWishlist) {
            e.preventDefault();
            e.stopPropagation(); 

            const idProduto = btnWishlist.dataset.idProduto;
            let acao = btnWishlist.dataset.acaoWishlist;

            fetch('wishlist_acoes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ acao: acao, id_produto: idProduto })
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    const iconEmpty = btnWishlist.querySelector('.wishlist-icon-empty');
                    const iconFilled = btnWishlist.querySelector('.wishlist-icon-filled');
                    
                    if (iconEmpty && iconFilled) {
                        if (data.nova_acao === 'remover') {
                            iconEmpty.classList.add('escondido');
                            iconFilled.classList.remove('escondido');
                            btnWishlist.dataset.acaoWishlist = 'remover';
                        } else {
                            iconEmpty.classList.remove('escondido');
                            iconFilled.classList.add('escondido');
                            btnWishlist.dataset.acaoWishlist = 'adicionar';
                        }
                    }
                } else {
                    if (data.mensagem && data.mensagem.includes('logado')) {
                         window.location.href = 'login.php';
                    } else {
                        alert(data.mensagem);
                    }
                }
            })
            .catch(error => console.error('Erro Wishlist:', error));
        }
    });

    document.addEventListener('click', function(e) {
        const setaProxima = e.target.closest('.next-arrow');
        const setaAnterior = e.target.closest('.prev-arrow');

        if (!setaProxima && !setaAnterior) return;

        const botaoClicado = setaProxima || setaAnterior;
        const wrapper = botaoClicado.closest('.carousel-wrapper');
        
        if (!wrapper) return;
        const container = wrapper.querySelector('.novidades-scroll-container');
        if (!container) return;

        const scrollAmount = 400;

        if (setaProxima) {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    });

});