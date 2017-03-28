var ep = [];

$.fn.onZ = function (events, selector, callback, priority)
{
    var p = $(this);
        is_update = false;
    if (selector)
    {
        p.on(events, selector, callback);
        is_update = true;
    }
    else
    {
        p.on(events, callback);
    }

    events = events.split(/\s+/);
    for (var i in events)
    {
        var e = events[i];
        if (is_update)
        {
            if (e == 'focus')
            {
                e = 'focusin';
            }
            else if (e == 'blur')
            {
                e = 'focusout';
            }
        }

        if (!ep[p.selector])
        {
            ep[p.selector] = [];
        }

        if (!ep[p.selector][e])
        {
            ep[p.selector][e] = []
        }

        ep[p.selector][e].push(priority);
        ep[p.selector][e].sort( function (a, b)
        {
            if (a > b) return -1;
            if (a < b) return 1;
        });
        var index = ep[p.selector][e].indexOf(priority);
        p.each( function ()
        {
            var e_array = $._data(this, 'events')[e],
                handler = e_array.pop();

            e_array.splice(index, 0, handler);
        });
    }
};

$.onZ = function (events, selector, callback, priority)
{
    $('body').onZ(events, selector, callback, priority);
};
