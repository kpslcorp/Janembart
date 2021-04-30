/* ===========================================================
 * Trumbowyg counter plugin
 * ===========================================================
 * Author : Paul Json
 *          http://pauljson.com
 *
 * Author : Konrad Kraska
 *
 * License: MIT
 *
 * Version: 1.1.0
 */

(function ($) {
    'use strict';

    var defaultOptions = {
        class: 'trumbowyg-counter',
        align: 'left',
        showWordsCounter: false,
        showCharsCounter: true,
    };

    function initializeCounter(trumbowyg) {
        trumbowyg.o.plugins.counter = $.extend(true, {}, defaultOptions, trumbowyg.o.plugins.counter || {});

        $(trumbowyg.$box).append('<div class="' + trumbowyg.o.plugins.counter.class + ' ' + trumbowyg.o.plugins.counter.class + '-' + trumbowyg.o.plugins.counter.align + '"></div>');

        $(trumbowyg.$box).on('tbwchange tbwpaste', function() {
            var text = $(trumbowyg.$ed).text(),
                words = (text !== ''? $(trumbowyg.$ed).text().match(/\S+/g).length: 0),
                characters = (text !== ''? $(trumbowyg.$ed).text().length: 0),
                output = '';

            if (trumbowyg.o.plugins.counter.showWordsCounter) {
                output += '<span class="words-counter">' + words + ' ' + trumbowyg.lang.counter.words + '</span>';
            }

            if (trumbowyg.o.plugins.counter.showCharsCounter) {
                output += '<span class="chars-counter">' + characters + ' ' +  trumbowyg.lang.counter.characters + '</span>';
            }

            $(trumbowyg.$box).find('.' + trumbowyg.o.plugins.counter.class).html(output);
        }).trigger('tbwchange');
    }

    $.extend(true, $.trumbowyg, {
        langs: {
            en: {
                counter: {
                    words: 'Mots',
                    characters: 'Charactères'
                }
            }
        },

        plugins: {
            counter: {
                init: function (trumbowyg) {
                    setTimeout(function() {
                        initializeCounter(trumbowyg);
                    });
                }
            }
        }
    });
})(jQuery);