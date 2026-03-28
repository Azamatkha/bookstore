import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    let catalog = document.getElementById('catalog');
    if (!catalog) {
        return;
    }

    const scrollToCatalog = () => {
        catalog.scrollIntoView({ behavior: 'auto', block: 'start' });
    };

    const buildUrlFromForm = (form) => {
        const url = new URL(form.action, window.location.origin);
        const params = new URLSearchParams();
        const data = new FormData(form);

        for (const [key, value] of data.entries()) {
            const text = String(value).trim();
            if (text !== '') {
                params.set(key, text);
            }
        }

        url.search = params.toString();

        return url;
    };

    const fetchCatalog = async (url, pushState = true) => {
        catalog.classList.add('opacity-60', 'pointer-events-none');

        try {
            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error(`Catalog request failed: ${response.status}`);
            }

            const html = await response.text();
            const documentFragment = new DOMParser().parseFromString(html, 'text/html');
            const incomingCatalog = documentFragment.getElementById('catalog');

            if (!incomingCatalog) {
                window.location.href = url.toString();
                return;
            }

            catalog.outerHTML = incomingCatalog.outerHTML;
            catalog = document.getElementById('catalog');

            if (pushState) {
                window.history.pushState({}, '', `${url.pathname}${url.search}#catalog`);
            }

            bindCatalogInteractions();
            scrollToCatalog();
        } catch {
            window.location.href = url.toString();
        } finally {
            catalog.classList.remove('opacity-60', 'pointer-events-none');
        }
    };

    const bindCatalogInteractions = () => {
        const filterForm = catalog.querySelector('form[method="GET"]');
        if (!filterForm) {
            return;
        }

        filterForm.addEventListener('submit', (event) => {
            event.preventDefault();
            fetchCatalog(buildUrlFromForm(filterForm));
        });

        const resetLink = filterForm.querySelector('a');
        if (resetLink) {
            resetLink.addEventListener('click', (event) => {
                event.preventDefault();
                fetchCatalog(new URL(filterForm.action, window.location.origin));
            });
        }

        const reactiveFields = filterForm.querySelectorAll('select');
        reactiveFields.forEach((field) => {
            field.addEventListener('change', () => {
                fetchCatalog(buildUrlFromForm(filterForm));
            });
        });

        const paginationLinks = catalog.querySelectorAll('nav[role="navigation"] a');
        paginationLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                fetchCatalog(new URL(link.href, window.location.origin));
            });
        });
    };

    bindCatalogInteractions();

    if (window.location.search.length > 0 || window.location.hash === '#catalog') {
        scrollToCatalog();
    }

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);
        fetchCatalog(url, false);
    });
});
