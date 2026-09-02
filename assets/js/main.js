'use strict';

jQuery(function ($) {
    var $filtroTipo = $('.alf-btn-filtro');

    if ($filtroTipo.length) {
        $filtroTipo.on('click', function () {
            var tipo = $(this).data('filtro');
            var $tarjetas = $('.tarjeta-proyecto');
            var $visibles = tipo === 'todos' ? $tarjetas : $tarjetas.filter('[data-tipo="' + tipo + '"]');

            $filtroTipo.removeClass('active');
            $(this).addClass('active');
            $tarjetas.addClass('d-none');
            $visibles.removeClass('d-none');
            $('.alf-sin-resultados').toggleClass('d-none', $visibles.length > 0);
        });
    }

    var $categoria = $('#categoria');

    if ($categoria.length) {
        $categoria.on('change', function () {
            $(this).closest('form').trigger('submit');
        });
    }

    var $menu = $('#menuPrincipal');

    $menu.on('click', 'a.nav-link', function () {
        if (window.innerWidth < 992) {
            var colapso = bootstrap.Collapse.getInstance($menu[0]);

            if (colapso) {
                colapso.hide();
            }
        }
    });
});
