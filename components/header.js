class MyHeader extends HTMLElement {
    connectedCallback() {
        const isInLayouts = window.location.pathname.includes('/layouts/');
        const prefix = isInLayouts ? '../' : '';
        const isLoggedIn = window.isLoggedIn || false;

        let profileButtonClass = '';
        let profileIcon = '';
        let profileLink = '';
        let profileText = '';

        if (isLoggedIn) {
            profileButtonClass = 'profile-button-logged';
            profileIcon = 'profile.png';
            profileLink = 'profile.php';
            profileText = 'Профиль';
        } else {
            profileButtonClass = '';
            profileIcon = 'voiti.png';
            profileLink = 'auth.php';
            profileText = 'Войти';
        }

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
                    <button class="profile-button ${profileButtonClass}" onclick="window.location.href='${prefix}layouts/${profileLink}'">
                        <img src="${prefix}src/icons/${profileIcon}" alt="${profileText}">
                        <span class="profile-text">${profileText}</span>
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