(function ($) {
    'use strict';

    $('.js-ConvertCurrency').on('click', function (e) {
        if ($(this).hasClass('disabled')) {
            return false;
        }
        currency_converter_calculate();
        if ($('.convert-component__input-group').hasClass('reverse')) {
            var convert_value = $(".convert-component__form-control[name='convert-result']").val()
        } else {
            var convert_value = $(".convert-component__form-control[name='amount']").val()
        }
        var ajaxdata = {
            action: 'converter_log',
            nonce_code: cryptocurrency_converter.vars.nonce,
            convert_from: $('.rate-value-left').attr('data-currency'),
            convert_to: $('.rate-value-right').attr('data-currency'),
            convert_value: convert_value
        };
        $.post(cryptocurrency_converter.vars.ajaxurl, ajaxdata, function (response) {
            console.log(response);
        });
    });

    $('.js-ConvertDirectionSwitch').on('click', function () {
        $(this).parents('.convert-component__input-group').toggleClass('reverse');
        var temp = $(".convert-component__form-control[name='amount']").val();
        $(".convert-component__form-control[name='amount']").val($(".convert-component__form-control[name='convert-result']").val());
        $(".convert-component__form-control[name='convert-result']").val(temp);
        $('#left-rate').toggleClass('rate-value-left rate-value-right');
        $('#right-rate').toggleClass('rate-value-right rate-value-left');

    });

    $('.left-list').on('select2:select', function (e) {
        var data = e.params.data;
        var id = data.id;
        $('#left-rate').attr('data-currency', id);
        get_currency_values();
        no_convert_the_same();
    });

    $('.right-list').on('select2:select', function (e) {
        var data = e.params.data;
        var id = data.id;
        $('#right-rate').attr('data-currency', id);
        get_currency_values();
        no_convert_the_same();
    });

    $(".convert-component__form-control").keypress(function (event) {
        event = event || window.event;
        if (event.charCode && event.charCode !== 0 && event.charCode !== 46 && (event.charCode < 48 || event.charCode > 57))
            return false;
    });


    function get_currency_values() {
        $('.js-ConvertCurrency').addClass('disabled');
        var ajaxdata = {
            action: 'get_currency',
            nonce_code: cryptocurrency_converter.vars.nonce,
            convert_from: $('.rate-value-left').attr('data-currency'),
            convert_to: $('.rate-value-right').attr('data-currency'),
        };
        $.post(cryptocurrency_converter.vars.ajaxurl, ajaxdata, function (response) {
            if (parseFloat(response) > 0) {
                $('#left-rate').val(response);
                $('#right-rate').val((1 / response).toFixed(6));
            } else {
                $('#left-rate').val(0);
                $('#right-rate').val(0);
            }
            $('.js-ConvertCurrency').removeClass('disabled');

        });
    }

    function no_convert_the_same() {
        let val_left = $('.rate-value-left').attr('data-currency');
        let val_right = $('.rate-value-right').attr('data-currency');
        $(".left-list>option").prop('disabled',false);
        $(".right-list>option").prop('disabled',false);
        $(".left-list>option[value='"+val_right+"']").prop('disabled',true).trigger('change');
        $(".right-list>option[value='"+val_left+"']").prop('disabled',true).trigger('change');
    }

    function currency_converter_calculate() {
        var convert_from_val = $('.rate-value-left').val();
        var convert_to_val = $('.rate-value-right').val();
        if ($('.convert-component__input-group').hasClass('reverse')) {
            var result = parseFloat($(".convert-component__form-control[name='convert-result']").val()) * convert_from_val;
            $(".convert-component__form-control[name='amount']").val(result);
        } else {
            var result = parseFloat($(".convert-component__form-control[name='amount']").val()) * convert_from_val;
            $(".convert-component__form-control[name='convert-result']").val(result);
        }

    }
    $('.convert-component__form-control').on('change keypress', function () {
       $(this).val($(this).val()
           .replace(/[^0-9.-]/g, '')
           .replace(/(\..*)\./g, '$1')
           .replace(/(?!^)-/g, '')
           .replace(/^0+(\d)/gm, '$1'));
    });

    $(document).ready(function () {
        try {
            $('.left-list').select2({
                templateSelection: formatState,
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('.convert-component__form-group--left')
            });

            $('.right-list').select2({
                templateSelection: formatState,
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('.convert-component__form-group--right')
            });
            no_convert_the_same();
        } catch (e) {
            $('.convert-component').addClass('no-select2')
            $('.convert-component__form').addClass('convert-component__form--blured')
        }


        function formatState(state) {
            if (!state.id) {
                return state.text;
            }

            var $state = $(
                '<span><span></span></span>'
            );

            let text = state.text.split('-')[0];
            $state.find("span").text(text);

            return $state;
        }
    })
})(jQuery);
