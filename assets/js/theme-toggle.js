(function () {
    var storageKey = 'sopku_theme';
    var preferred = localStorage.getItem(storageKey) || 'light';

    function applyTheme(theme) {
        var isDark = theme === 'dark';
        document.documentElement.classList.toggle('theme-dark', isDark);
        if (document.body) {
            document.body.classList.toggle('theme-dark', isDark);
        }

        var button = document.querySelector('[data-theme-toggle]');
        if (button) {
            button.setAttribute('aria-label', isDark ? 'Ganti ke tema terang' : 'Ganti ke tema gelap');
            button.setAttribute('title', isDark ? 'Tema terang' : 'Tema gelap');
            button.querySelector('[data-theme-icon]').textContent = isDark ? '☀' : '☾';
            button.querySelector('[data-theme-label]').textContent = isDark ? 'Terang' : 'Gelap';
        }
    }

    applyTheme(preferred);

    function mountToggle() {
        if (document.querySelector('[data-theme-toggle]')) return;

        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'theme-toggle';
        button.setAttribute('data-theme-toggle', 'true');
        button.innerHTML = '<span class="theme-toggle__icon" data-theme-icon></span><span data-theme-label></span>';
        button.addEventListener('click', function () {
            var next = document.documentElement.classList.contains('theme-dark') ? 'light' : 'dark';
            localStorage.setItem(storageKey, next);
            applyTheme(next);
        });

        document.body.appendChild(button);
        applyTheme(localStorage.getItem(storageKey) || 'light');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mountToggle);
    } else {
        mountToggle();
    }
})();
