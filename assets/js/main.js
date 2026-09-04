'use strict';

jQuery(function ($) {
    var $filtroTipo = $('.alf-btn-filtro');

    if ($filtroTipo.length) {
        $filtroTipo.on('click', function () {
            var tipo = $(this).data('filtro');
            var $tarjetas = $('.tarjeta-proyecto');
            var $visibles = $tarjetas.filter('[data-tipo="' + tipo + '"]');

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

    var $formContacto = $('#form-contacto');

    if ($formContacto.length) {
        $formContacto.on('submit', function (evento) {
            if ($formContacto.data('enviando') || typeof grecaptcha === 'undefined') {
                return;
            }

            evento.preventDefault();

            var $boton = $formContacto.find('button[type="submit"]');
            $boton.prop('disabled', true);

            var reactivar = setTimeout(function () {
                $boton.prop('disabled', false);
            }, 10000);

            grecaptcha.ready(function () {
                grecaptcha.execute($formContacto.data('recaptcha'), { action: 'contacto' }).then(function (token) {
                    clearTimeout(reactivar);
                    $formContacto.find('#recaptcha-token').val(token);
                    $formContacto.data('enviando', true);
                    $formContacto.trigger('submit');
                });
            });
        });
    }
});
