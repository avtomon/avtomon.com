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

window.nativeSubmit = function (f, beforeSubmit, afterSubmit, errorHandler, params)
{
    params = params ? params : [];
    var fd = new FormData(f.get(0));
    $.ajax({
        type: 'POST',
        url: URL,
        dataType: 'json',
        data: fd,
        async: true,
        contentType: false,
        processData: false,
        beforeSend: function ()
        {
            f.find(SUBMIT_SELECTOR).addClass(SUBMIT_DISABLED_CLASS);

            beforeSubmit ? beforeSubmit() : null;

            fd.append('class', f.attr('phpClass') || params.phpClass || null);
            fd.append('method', f.attr('phpMethod') || params.phpMethod || null);
            fd.append('get_instance', f.attr('getInstance') || params.getInstance || 0);
            fd.append('static_method', f.attr('staticMethod') || params.staticMethod || 0);
            fd.append('pagecache_flush', f.attr('pagecacheFlush') || params.pagecacheFlush || 1);

            var is_insert = false;
            if (f.attr('for') == 'insert')
            {
                is_insert = true;
            }

            f.find('input[name]:not([type=file]), textarea[name], select[name]').each( function ()
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
                            t.addClass('invalid').formError('Обязательное поле не заполнено');
                            return false;
                        }
                    }
                }
                if (min)
                {
                    if (v.length < min)
                    {
                        t.addClass('invalid').formError('Количество символов в поле не должно быть менее ' + min);
                        return false;
                    }
                }
            });
            if (f.find('*.invalid:not(:disabled)').length)
            {
                error('Есть некорректно заполненые поля');
                return false;
            }
        },
        success: function (data)
        {
            if (data.error !== undefined)
            {
                error('Произошла ошибка: ' + data.error);
            }
            else if (data.redirect !== undefined)
            {
                window.location = data.redirect;
            }
            else if (data.success !== undefined)
            {
                afterSubmit ? afterSubmit() : null;
            }
            else
            {
                error('Произошла ошибка');
            }
            setTimeout( function ()
            {
                f.find(SUBMIT_SELECTOR).removeClass(SUBMIT_DISABLED_CLASS);
            }, ENABLED_SUBMIT_TIMEOUT);
        },
        error: function (XMLHttpRequest, textStatus)
        {

            errorHandler ? errorHandler() : error('Произошла ошибка ' + textStatus);

            setTimeout( function ()
            {
                f.find(SUBMIT_SELECTOR).removeClass(SUBMIT_DISABLED_CLASS);
            }, ENABLED_SUBMIT_TIMEOUT);
        }
    });
};