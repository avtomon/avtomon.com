/**
 * Created by Александр on 17.06.14.
 */

$.getScript('/html/js/jquery.form.js', function ()
{
    $('.clearForm').on('click', function ()
    {
        $('input[name], textarea[name], select[name]').val('').text('');
        $('ul.dropdown-content li:first-child').click();
        $('.chosen').trigger("chosen:updated");
    });

    $.fn.formError = function (message)
    {
        var span = this.prev('.select-dropdown');
        this
            .add(span)
            .addClass('invalid')
            .nextAll('.note:not(.error)')
            .addClass('no_display');
        this
            .nextAll('.note.error')
            .remove();
        this
            .parent()
            .append('<div class="note error">' + message + '</div>');
    };

    $.fn.delFormError = function ()
    {
        var span = this.prev('.select-dropdown');
        this.add(span).removeClass('invalid').nextAll('.note:not(.error)').removeClass('no_display');
        this.siblings('.note.error').remove();
    };

    window.nativeSubmit = function (element, beforeSubmit, afterSubmit, errorSubmit, params)
    {
        var form = element.closest('form'),
            options = {
                dataType:'json',
                iframe: true,
                type: 'POST',
                url: '/router.php',
                data: {
                    class: params && params.phpClass ? params.phpClass : form.attr('phpClass'),
                    method: params && params.phpMethod ? params.phpMethod : form.attr('phpMethod'),
                    get_instance: params && params.getInstance ? params.getInstance : form.attr('getInstance') ? form.attr('getInstance') : 0,
                    static_method: params && params.staticMethod ? params.staticMethod : form.attr('staticMethod') ? form.attr('staticMethod') : 0,
                    pagecache_flush: params && params.pagecacheFlush ? params.pagecacheFlush : form.attr('pagecacheFlush') ? form.attr('pagecacheFlush') : 1
                },
                beforeSerialize: function ($form)
                {
                    beforeSubmit ? beforeSubmit() : null;

                    var is_insert = false;
                    if ($form.attr('for') == 'insert')
                    {
                        is_insert = true;
                    }

                    $form.find('input[name]:not([type=file]), textarea[name], select[name]').each( function ()
                    {
                        var t = $(this),
                            v = t.val(),
                            min = t.attr('min');
                        if (!t.prop('multiple'))
                        {
                            t.val($.trim(v));
                        }
                        if (is_insert)
                        {
                            if (!v)
                            {
                                if (!t.hasClass('required'))
                                {
                                    t.prop('disabled', true);
                                }
                                else
                                {
                                    t.formError('Обязательное поле не заполнено');
                                }
                            }
                            if (min)
                            {
                                if (v.length < min)
                                {
                                    $(this).formError('Количество символов в поле не должно быть менее ' + min);
                                }
                            }
                            if ($(this).hasClass('invalid'))
                            {
                                return false;
                            }
                        }
                    });
                    if ($form.find('*.invalid:not(:disabled)').length)
                    {
                        showError('Есть некорректно заполненые поля');
                        return false;
                    }
                },
                success: function (response)
                {
                    if (response.success !== false && response.success !== undefined)
                    {
                        afterSubmit ? afterSubmit(response) : null;
                    }
                    else if (response.error)
                    {
                        errorSubmit ? errorSubmit(response) : showError(response.error);
                    }
                    else if (response.redirect)
                    {
                        window.location = data.redirect;
                    }
                    else
                    {
                        showError('Произошла ошибка');
                    }
                },
                error: function (error)
                {
                    showError('Произошла ошибка. ' + error.error.message);
                }
            };
        if (!form.find('*.invalid:not(:disabled)').length)
        {
            form.ajaxSubmit(options);
        }
        setTimeout( function ()
        {
            form.find('*:disabled').prop('disabled', false);
            $('.save').removeClass('disabled');
        }, 500);
        return false;
    }
});
