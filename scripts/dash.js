function scrollLeft() {
    const productGrid = document.getElementById('product-grid');
    productGrid.scrollLeft -= 200; // Muda a posição horizontal do grid para a esquerda
}

function scrollRight() {
    const productGrid = document.getElementById('product-grid');
    productGrid.scrollLeft += 200; // Muda a posição horizontal do grid para a direita
}
function toggleMode() {
    const body = document.body; // Obtém o elemento body do HTML
    const menuButtons = document.querySelector('.menu-buttons'); // Obtém os botões do menu
    const promoSection = document.querySelector('.promo-section'); // Obtém a seção de promoções
    const modeButton = document.getElementById('mode-button'); // Obtém o botão de modo
    const modeText = document.getElementById('mode-text'); // Obtém o texto do botão de modo
    const icon = modeButton.querySelector('i'); // Obtém o ícone do botão de modo

    // Alterna a classe de modo escuro
    body.classList.toggle('dark-mode'); // Adiciona ou remove a classe 'dark-mode' no body
    menuButtons.classList.toggle('dark-mode'); // Adiciona ou remove a classe 'dark-mode' nos botões do menu
    promoSection.classList.toggle('dark-mode'); // Adiciona ou remove a classe 'dark-mode' na seção de promoções

    // Verifica se o modo escuro está ativado
    if (body.classList.contains('dark-mode')) { // Se o modo escuro estiver ativado
        modeText.textContent = 'MODO CLARO'; // Muda o texto para "Modo Claro"
        icon.classList.remove('fa-moon'); // Remove o ícone da lua
        icon.classList.add('fa-sun'); // Adiciona o ícone do sol
        
        // Define um cookie para armazenar a preferência do usuário
        document.cookie = "theme=dark-mode; path=/; max-age=" + (365 * 24 * 60 * 60); // 1 ano
    } else {
        modeText.textContent = 'MODO ESCURO'; // Muda o texto para "Modo Escuro"
        icon.classList.remove('fa-sun'); // Remove o ícone do sol
        icon.classList.add('fa-moon'); // Adiciona o ícone da lua

        // Define um cookie para armazenar a preferência do usuário
        document.cookie = "theme=light-mode; path=/; max-age=" + (365 * 24 * 60 * 60); // 1 ano
    }
}

// Atualiza o tema com base no cookie ao carregar a página
window.onload = function() { // Função chamada quando a página é carregada
    const theme = document.cookie.split('; ').find(row => row.startsWith('theme=')); // Busca o cookie 'theme'
    if (theme) { // Se o cookie existir
        const mode = theme.split('=')[1]; // Obtém o valor do modo do cookie
        document.body.classList.add(mode); // Adiciona a classe correspondente ao body
        document.querySelector('.menu-buttons').classList.add(mode); // Adiciona a classe correspondente aos botões do menu
        document.querySelector('.promo-section').classList.add(mode); // Adiciona a classe correspondente à seção promoções
        if (mode === 'dark-mode') { // Se o modo for 'dark-mode'
            document.getElementById('mode-text').textContent = 'MODO CLARO'; // Atualiza o texto do modo
            document.getElementById('mode-button').querySelector('i').classList.replace('fa-moon', 'fa-sun'); // Atualiza o ícone
        } else {
            document.getElementById('mode-text').textContent = 'MODO ESCURO'; // Atualiza o texto do modo
            document.getElementById('mode-button').querySelector('i').classList.replace('fa-sun', 'fa-moon'); // Atualiza o ícone
        }
    }
};