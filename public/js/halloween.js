
/*
 * COFIHO V2 — Halloween
 * Decoraciones visuales independientes.
 * No modifica formularios, rutas ni datos.
 */

(() => {
    'use strict';

    const KEY = 'cofiho-halloween-enabled';

    // Evitar inicialización duplicada
    if (document.querySelector('.halloween-toggle')) {
        return;
    }

    // Detectar tipo de página
    const topbar = document.querySelector('.topbar');
    const sidebar = document.querySelector('.sidebar');
    const hero = document.querySelector('.hero');

    const isAdmin = Boolean(topbar && sidebar);
    const isCatalog = Boolean(hero) && !isAdmin;

    document.body.classList.add(
        isAdmin ? 'hw-admin' : 'hw-public'
    );

    let enabled = true;

    try {
        enabled = localStorage.getItem(KEY) !== 'false';
    } catch (error) {
        enabled = true;
    }

    // Ilustraciones SVG
    const ART = {
        bat: `
            <svg viewBox="0 -2 64 31" aria-hidden="true">
                <path d="M32 8L35 3L36 9C40 8 45 5 52 1C60 -1 64 6 62 14C58 12 55 13 52 17C50 14 46 14 43 18C41 15 38 16 36 21L34 27L32 22L30 27L28 21C26 16 23 15 21 18C18 14 14 14 12 17C9 13 6 12 2 14C0 6 4 -1 12 1C19 5 24 8 28 9L29 3Z"/>
            </svg>
        `,

        web: `
            <svg viewBox="0 0 160 160"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1"
                 stroke-linecap="round"
                 aria-hidden="true">
                <path d="M0 0L160 0M0 0L150 55M0 0L113 113M0 0L55 150M0 0L0 160"/>
                <path d="M50 0Q40 8 47 17Q33 22 35 35Q22 33 17 47Q8 40 0 50"/>
                <path d="M90 0Q72 14 85 31Q60 40 64 64Q40 60 31 85Q14 72 0 90"/>
                <path d="M130 0Q104 20 122 44Q86 58 92 92Q58 86 44 122Q20 104 0 130"/>
            </svg>
        `,

        spider: `
            <svg viewBox="0 0 40 40" aria-hidden="true">
                <g class="sp-legs">
                    <path d="M12 22L4 14L1 22M11 27L2 27L2 35M12 31L5 37L6 40M14 19L8 10L4 12"/>
                    <path d="M28 22L36 14L39 22M29 27L38 27L38 35M28 31L35 37L34 40M26 19L32 10L36 12"/>
                </g>
                <ellipse class="sp-body" cx="20" cy="28" rx="9" ry="10"/>
                <circle class="sp-body" cx="20" cy="17" r="5"/>
                <circle class="sp-eye" cx="18" cy="16" r="1"/>
                <circle class="sp-eye" cx="22" cy="16" r="1"/>
                <path class="sp-mark" d="M18 26L22 26L20 30L22 34L18 34L20 30Z"/>
            </svg>
        `,

        pumpkin: `
            <svg viewBox="0 0 120 110" aria-hidden="true">
                <ellipse cx="60" cy="62" rx="50" ry="42" fill="#e8620c"/>
                <ellipse cx="32" cy="62" rx="26" ry="40" fill="#f2740f"/>
                <ellipse cx="88" cy="62" rx="26" ry="40" fill="#f2740f"/>
                <ellipse cx="60" cy="62" rx="22" ry="42" fill="#ff8a1f"/>
                <path d="M55 22Q56 8 66 6L68 12Q62 14 62 24Z" fill="#3b5d1f"/>
                <g fill="#ffd24a">
                    <path d="M32 56L50 56L41 40Z"/>
                    <path d="M70 56L88 56L79 40Z"/>
                    <path d="M60 58L66 68L54 68Z"/>
                    <path d="M30 74Q60 100 90 74Q80 84 72 80L66 88L60 82L54 88L48 80Q40 84 30 74Z"/>
                </g>
            </svg>
        `,

        hills: `
            <svg viewBox="0 0 1200 110"
                 preserveAspectRatio="xMidYMax slice"
                 fill="#0b0713"
                 aria-hidden="true">
                <path d="M0 110L0 78Q150 40 300 74T600 68T900 74T1200 58L1200 110Z"/>
                <path d="M140 80V58a10 10 0 0 1 20 0V80ZM230 76V56a9 9 0 0 1 18 0V76ZM980 70V50a10 10 0 0 1 20 0V70ZM1070 66V48a9 9 0 0 1 18 0V66Z"/>
                <path d="M610 74V52M603 60H617"
                      stroke="#0b0713"
                      stroke-width="4"
                      fill="none"/>
            </svg>
        `
    };

    const decorations = [];

    // Crear decoración sin afectar elementos existentes
    function createDecoration(className, html = '') {
        const element = document.createElement('div');

        element.className = 'hw-deco ' + className;
        element.setAttribute('aria-hidden', 'true');
        element.style.pointerEvents = 'none';

        if (html) {
            element.innerHTML = html;
        }

        decorations.push(element);

        return element;
    }

    // Decoraciones del panel administrativo
    if (isAdmin) {
        topbar.append(
            createDecoration('hw-web r', ART.web),
            createDecoration('hw-spider', ART.spider),
            createDecoration('hw-bat b1', ART.bat),
            createDecoration('hw-bat b2', ART.bat)
        );
    }

    // Decoraciones del catálogo público
    if (isCatalog) {
        document.body.append(
            createDecoration('hw-web l', ART.web),
            createDecoration('hw-web r', ART.web)
        );

        hero.append(
            createDecoration('hw-moon'),
            createDecoration('hw-hills', ART.hills),
            createDecoration('hw-pumpkin', ART.pumpkin),
            createDecoration('hw-bat b1', ART.bat),
            createDecoration('hw-bat b2', ART.bat)
        );
    }

    // Interruptor de Halloween
    const button = document.createElement('button');

    button.type = 'button';
    button.className = 'halloween-toggle' +
        (isAdmin ? ' in-topbar' : '');

    button.setAttribute(
        'aria-label',
        'Activar o desactivar decoraciones de Halloween'
    );

    button.innerHTML = `
        <span>Halloween</span>
        <span class="hw-switch"></span>
    `;

    // Aplicar o quitar el tema
    function render() {
        document.body.classList.toggle('hw-on', enabled);

        decorations.forEach(element => {
            element.hidden = !enabled;
        });

        button.setAttribute(
            'aria-pressed',
            String(enabled)
        );
    }

    button.addEventListener('click', () => {
        enabled = !enabled;

        try {
            localStorage.setItem(KEY, String(enabled));
        } catch (error) {
            // Continuar aunque el almacenamiento no esté disponible
        }

        render();
    });

    // Mostrar interruptor únicamente en el administrador
if (isAdmin) {
    const system = topbar.querySelector('.system');

    topbar.insertBefore(button, system || null);
}

// Aplicar tema en todas las páginas
render();
})();
