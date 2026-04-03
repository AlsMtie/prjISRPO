class MyHeader extends HTMLElement {
    connectedCallback() {
        const isInLayouts = window.location.pathname.includes('/layouts/');
        const prefix = isInLayouts ? '../' : '';

        this.innerHTML = `
            <header>
                <div class="header-container">
                    <button class="cart-button" onclick="window.location.href='${prefix}layouts/cart.php'">
                        <img src="${prefix}icons/cart_icon.png" alt="Корзина">
                        <span class="cart-text">Корзина</span>
                    </button>
                    <div class="logo-img">
                        <a href="${prefix}index.php">
                            <img src="${prefix}icons/Tomu_logo.png" alt="Logo">
                        </a>
                    </div>
                    <button class="login-button" onclick="window.location.href='${prefix}layouts/auth.php'">
                        <img src="${prefix}icons/voiti.png" alt="Вход">
                        <span class="login-text">Войти</span>
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

        setTimeout(() => {
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        }, 100);
    }
}

customElements.define('my-header', MyHeader);