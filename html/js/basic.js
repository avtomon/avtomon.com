/**
 * Created by Александр on 17.07.14.
 */
    function translite(str){
        var arr = {'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 'ж':'g', 'з':'z', 'и':'i', 'й':'y', 'к':'k', 'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r',
                   'с':'s', 'т':'t', 'у':'u', 'ф':'f', 'ы':'i', 'э':'e', 'А':'A', 'Б':'B', 'В':'V', 'Г':'G', 'Д':'D', 'Е':'E', 'Ж':'G', 'З':'Z', 'И':'I', 'Й':'Y', 'К':'K',
                   'Л':'L', 'М':'M', 'Н':'N', 'О':'O', 'П':'P', 'Р':'R', 'С':'S', 'Т':'T', 'У':'U', 'Ф':'F', 'Ы':'I', 'Э':'E', 'ё':'yo', 'х':'h', 'ц':'ts', 'ч':'ch', 'ш':'sh',
                   'щ':'shch', 'ъ':'', 'ь':'', 'ю':'yu', 'я':'ya', 'Ё':'YO', 'Х':'H', 'Ц':'TS', 'Ч':'CH', 'Ш':'SH', 'Щ':'SHCH', 'Ъ':'', 'Ь':'', 'Ю':'YU', 'Я':'YA'};

        var replacer = function(a)
        {
            return arr[a]||a
        };
        return str.replace(/[А-яёЁ]/g,replacer);
    }

    var HTML=function(){
        var x,mnem=
            {34:"quot",38:"amp",39:"apos",60:"lt",62:"gt",402:"fnof",
                338:"OElig",339:"oelig",352:"Scaron",353:"scaron",
                376:"Yuml",710:"circ",732:"tilde",8226:"bull",8230:"hellip",
                8242:"prime",8243:"Prime",8254:"oline",8260:"frasl",8472:"weierp",
                8465:"image",8476:"real",8482:"trade",8501:"alefsym",8592:"larr",
                8593:"uarr",8594:"rarr",8595:"darr",8596:"harr",8629:"crarr",
                8656:"lArr",8657:"uArr",8658:"rArr",8659:"dArr",8660:"hArr",
                8704:"forall",8706:"part",8707:"exist",8709:"empty",8711:"nabla",
                8712:"isin",8713:"notin",8715:"ni",8719:"prod",8721:"sum",
                8722:"minus",8727:"lowast",8730:"radic",8733:"prop",8734:"infin",
                8736:"ang",8743:"and",8744:"or",8745:"cap",8746:"cup",8747:"int",
                8756:"there4",8764:"sim",8773:"cong",8776:"asymp",8800:"ne",
                8801:"equiv",8804:"le",8805:"ge",8834:"sub",8835:"sup",8836:"nsub",
                8838:"sube",8839:"supe",8853:"oplus",8855:"otimes",8869:"perp",
                8901:"sdot",8968:"lceil",8969:"rceil",8970:"lfloor",8971:"rfloor",
                9001:"lang",9002:"rang",9674:"loz",9824:"spades",9827:"clubs",
                9829:"hearts",9830:"diams",8194:"ensp",8195:"emsp",8201:"thinsp",
                8204:"zwnj",8205:"zwj",8206:"lrm",8207:"rlm",8211:"ndash",
                8212:"mdash",8216:"lsquo",8217:"rsquo",8218:"sbquo",8220:"ldquo",
                8221:"rdquo",8222:"bdquo",8224:"dagger",8225:"Dagger",8240:"permil",
                8249:"lsaquo",8250:"rsaquo",8364:"euro",977:"thetasym",978:"upsih",982:"piv"},
            tab=("nbsp|iexcl|cent|pound|curren|yen|brvbar|sect|uml|"+
                "copy|ordf|laquo|not|shy|reg|macr|deg|plusmn|sup2|sup3|"+
                "acute|micro|para|middot|cedil|sup1|ordm|raquo|frac14|"+
                "frac12|frac34|iquest|Agrave|Aacute|Acirc|Atilde|Auml|"+
                "Aring|AElig|Ccedil|Egrave|Eacute|Ecirc|Euml|Igrave|"+
                "Iacute|Icirc|Iuml|ETH|Ntilde|Ograve|Oacute|Ocirc|Otilde|"+
                "Ouml|times|Oslash|Ugrave|Uacute|Ucirc|Uuml|Yacute|THORN|"+
                "szlig|agrave|aacute|acirc|atilde|auml|aring|aelig|ccedil|"+
                "egrave|eacute|ecirc|euml|igrave|iacute|icirc|iuml|eth|ntilde|"+
                "ograve|oacute|ocirc|otilde|ouml|divide|oslash|ugrave|uacute|"+
                "ucirc|uuml|yacute|thorn|yuml").split("|");
        for(x=0;x<96;x++)mnem[160+x]=tab[x];
        tab=("Alpha|Beta|Gamma|Delta|Epsilon|Zeta|Eta|Theta|Iota|Kappa|"+
            "Lambda|Mu|Nu|Xi|Omicron|Pi|Rho").split("|");
        for(x=0;x<17;x++)mnem[913+x]=tab[x];
        tab=("Sigma|Tau|Upsilon|Phi|Chi|Psi|Omega").split("|");
        for(x=0;x<7;x++)mnem[931+x]=tab[x];
        tab=("alpha|beta|gamma|delta|epsilon|zeta|eta|theta|iota|kappa|"+
            "lambda|mu|nu|xi|omicron|pi|rho|sigmaf|sigma|tau|upsilon|phi|chi|"+
            "psi|omega").split("|");
        for(x=0;x<25;x++)mnem[945+x]=tab[x];
        return {
            encode:function(text){
                return text.replace(/[\u00A0-\u2666<>\&]/g,function(a){
                    return "&"+(mnem[a=a.charCodeAt(0)]||"#"+a)+";"
                })
            },
            decode:function(text){
                return text.replace(/\&#?(\w+);/g,function(a,b){
                    if(Number(b))return String.fromCharCode(Number(b));
                    for(x in mnem){
                        if(mnem[x]===b)return String.fromCharCode(x);
                    }
                })
            }
        }
    }();

    function strip_tags(input, allowed)
    {
        allowed = (((allowed || '') + '')
            .toLowerCase()
            .match(/<[a-z][a-z0-9]*>/g) || [])
            .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
            commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return input.replace(commentsAndPhpTags, '')
            .replace(tags, function($0, $1) {
                return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
            });
    }

    $('body').on('submit', 'form', function ()
    {
        return false;
    });

    $('html').on('focus', 'input[type=text]', function ()
    {
        if (!$(this).attr('maxlength'))
        {
            $(this).attr('maxlength', '300');
        }
    });

    function implode (glue, pieces)
    {
        return ((pieces instanceof Array) ? pieces.join (glue) : pieces);
    }

    function getInternetExplorerVersion ()
    {
        var rv = -1;
        if (navigator.appName == 'Microsoft Internet Explorer')
        {
            var ua = navigator.userAgent,
                re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

            if (re.exec(ua) != null)
                rv = parseFloat( RegExp.$1 );
        }
        else if (navigator.appName == 'Netscape')
        {
            var ua = navigator.userAgent,
                re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");

            if (re.exec(ua) != null)
                rv = parseFloat( RegExp.$1 );
        }
        return rv;
    }

    $.fn.toogleProp = function(prop) {
        return this.prop(prop, !this.prop(prop));
    };

    $.fn.veil = function()
    {
        return this.addClass('no_display');
    };

    $.fn.reveal = function()
    {
        return this.removeClass('no_display');
    };

    $.fn.veilOne = function(filter)
    {
        return this.addClass('no_display').siblings(filter).removeClass('no_display').end();
    };

    $.fn.revealOne = function(filter)
    {
        return this.removeClass('no_display').siblings(filter).addClass('no_display').end();
    };

    $.fn.setDatepicker = function ()
    {
        return this.datepicker({showOn: "focus"}).mask('99.99.9999');
    };

    $.fn.p = function (filter)
    {
        return filter ? this.parents(filter) : this.parent();
    };

    $.fn.id = function (id)
    {
        return id === undefined ? this.attr('id') : this.attr('id', id);
    };

    $.fn.addCOne = function(cname, filter)
    {
        return this.addClass(cname).siblings(filter).removeClass(cname).end();
    };

    $.fn.removeCOne = function(cname, filter)
    {
        return this.removeClass(cname).siblings(filter).addClass(cname).end();
    };

    $.fn.serializeAll = function () {
        var data = $(this).serializeArray();

        $(':disabled[name]', this).each(function () {
            data.push({ name: this.name, value: $(this).val() });
        });

        return data;
    };

    $.fn.serializeObject = function () {
        "use strict";

        var result = {};
        var extend = function (i, element) {
            var node = result[element.name];

            // If node with same name exists already, need to convert it to an array as it
            // is a multi-value field (i.e., checkboxes)

            if ('undefined' !== typeof node && node !== null) {
                if ($.isArray(node)) {
                    node.push(element.value);
                } else {
                    result[element.name] = [node, element.value];
                }
            } else {
                result[element.name] = element.value;
            }
        };

        $.each(this.serializeAll(), extend);
        return result;
    };

    jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg)
    {
        return function( elem ) {
            return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    $('a.sorter').click( function ()
    {
        $(this).toggleClass('up');
    });

    $.getScript('/html/js/jquery.tablesorter.min.js', function ()
    {
        setTimeout(function ()
        {
            try
            {
                $('table.users:not(.no_sorter)').tablesorter();
            }
            catch (e)
            {

            }
        }, 2000);
    });

var Page = function (subject)
{
    var b = $('body'),
        h = $('html');

    function toggleActivity (type, id, callback, errorCallBack)
    {
        var params = {
            class: subject,
            method: type + subject,
            method_params:
            {
                id: id
            }
        };
        return request(params, true, 'POST', function (data)
            {
                if (data.success)
                {
                    callback();
                }
                else
                {
                    errorCallBack();
                }
            },
            errorCallBack);
    }

    function statusToggle (el)
    {
        var status = el.parent().siblings('.in_class_is_active');
        el.add(status).toggleClass('false true');
    }

    b.on('keyup change', '.invalid', function ()
    {
        $(this).delFormError();
    });

    h.on('focus', '.chosen-container .search-field input', function ()
    {
        $(this).parent().siblings('.search-choice').css('margin-bottom', 0);
    });

    b.on('blur', '.chosen-container .search-field input', function ()
    {
        $(this).parent().siblings('.search-choice').css('margin-bottom', '-23px');
    });

    h.on('click', '.dropdown-content', function ()
    {
        $('.select-dropdown[data-activates=' + $(this).attr('id') + '].invalid').next().change();
    });

    var page = trim(window.location.href, ' /.').match(/page\/(\d+)/);
    if ($.isArray(page))
    {
        page = page[1];
    }
    else
    {
        page = 1;
    }

    window.obj = {
        activate: function (id, callback, errorCallBack)
        {
            return toggleActivity('activate', id, callback, errorCallBack);
        },
        deactivate: function (id, callback, errorCallBack)
        {
            return toggleActivity('deactivate', id, callback, errorCallBack);
        },
        statusToggle: function (el)
        {
            var status = el.parent().siblings('.in_class_is_active');
            el.add(status).toggleClass('false true');
        },
        getPageCount: function ()
        {
            var params = {
                class: subject,
                method: 'getAdminPageCount',
                get_instance: false,
                static_method: false
            };
            request(params, true, 'GET', function (data)
            {
                if (data.success)
                {
                    if (data.success[0].page_count > 1)
                    {
                        $('.content_linepages').removeClass('no_display');
                        for (var i = 1; i <  parseInt(data.success[0].page_count) + 1; i++)
                        {
                            var newEl = $('.content_linepages li.clone').clone(true).removeClass('clone');
                            var a = newEl.appendTo('.content_linepages ul').find('a').text(i);
                            if (i == page)
                            {
                                var href = '/admin/' + subject.toLowerCase() + 's/page/';
                                newEl.addClass('active');
                                if (i <  parseInt(data.success[0].page_count))
                                {
                                    $('a.arr-next').attr('href', href + (i + 1))
                                }
                                if (i > 1)
                                {
                                    $('a.arr-prev').attr('href', href + (i - 1))
                                }
                            }
                            else
                            {
                                a.attr('href', href + i);
                            }
                        }
                    }
                }
            });
        }
    };

    b.on('click', 'tr div.true', function (e)
    {
        var self = $(this);
        var id = $(this).parents('tr.parent').attr('id');
        if (id)
        {
            obj.deactivate(id, function ()
                {
                    obj.statusToggle(self)
                },
                function ()
                {
                    showError('Не удалось заблокировать объект');
                })
        }
        return false;
    });

    h.on('click', 'tr div.false', function (e)
    {
        var self = $(this);
        var id = $(this).parents('tr.parent').attr('id');
        if (id)
        {
            obj.activate(id, function ()
                {
                    obj.statusToggle (self)
                },
                function ()
                {
                    showError('Не удалось активировать объект');
                });
        }
        return false;
    });

    return obj;
};
