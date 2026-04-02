class MyFooter extends HTMLElement {
    connectedCallback() {
        const isInLayouts = window.location.pathname.includes('/layouts/');
        const prefix = isInLayouts ? '../' : '';

        this.innerHTML = `
            <footer class="footer">
                <div class="footer-container">
                    <div class="footer-logo">
                        <img src="${prefix}icons/Tomu_logo.png" alt="Tomy Logo">
                    </div>
                    
                    <div class="footer-links">
                        <a href="#" class="footer-link">Условие оплаты, получения и возраста</a>
                        <a href="#" class="footer-link">Пользовательское соглашение</a>
                        <a href="#" class="footer-link">Copyright 2026</a>
                    </div>
                </div>
            </footer>`;
    }
}
customElements.define('my-footer', MyFooter);