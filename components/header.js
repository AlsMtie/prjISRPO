class MyHeader extends HTMLElement {
    connectedCallback() {
        const isInLayouts = window.location.pathname.includes('/layouts/');
        const prefix = isInLayouts ? '../' : '';
        const isLoggedIn = window.isLoggedIn || false;
        const userName = window.userName || '';

        this.innerHTML = `
            <header>
                <div class="header-container">
                    <button class="cart-button" onclick="window.location.href='${prefix}layouts/cart.php'">
                        <img src="${prefix}src/icons/cart_icon.png" alt="Корзина">
                        <span class="cart-text">Корзина</span>
                    </button>
                    <div class="logo-img">
                        <a href="${prefix}index.php">
                            <img src="${prefix}src/icons/Tomu_logo.png" alt="Logo">
                        </a>
                    </div>
                    <button class="profile-button" onclick="window.location.href='${prefix}layouts/${isLoggedIn ? 'profile.php' : 'auth.php'}'">
                        <img src="${prefix}src/icons/${isLoggedIn ? 'profile.png' : 'voiti.png'}" alt="${isLoggedIn ? 'Профиль' : 'Вход'}">
                        <span class="profile-text">${isLoggedIn ? 'Профиль' : 'Войти'}</span>
                    </button>
                </div>
                <div class="nav-container">
                    <nav class="nav-menu">
                        <button class="nav-button" onclick="window.location.href='${prefix}layouts/menu.php?category=1'">Горячие блюда</button>
                        <button class="nav-button" onclick="window.location.href='${prefix}layouts/menu.php?category=2'">Супы</button>
                        <button class="nav-button" onclick="window.location.href='${prefix}layouts/menu.php?category=3'">Салаты</button>
                        <button class="nav-button" onclick="window.location.href='${prefix}layouts/menu.php?category=4'">Напитки</button>
                        <button class="nav-button" onclick="window.location.href='${prefix}layouts/menu.php?category=5'">Добавки</button>
                        <button class="nav-button" onclick="window.location.href='${prefix}layouts/menu.php?category=6'">Десерты</button>
                    </nav>
                </div>
            </header>
        `;
    }
}

customElements.define('my-header', MyHeader);