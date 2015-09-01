jQuery(document).ready(function () {

    jQuery('.wpmcpf-show-all').on("click", function (event) {
        "use strict";

        _Cookies.set(cookieName, {}, {expires: cookieLifeTime, path: ''});
        jQuery('.wpmcpf-current').show();
        jQuery('.wpmcpf-show-all').hide();
        redrawSports();
    });
    var _Cookies = Cookies.noConflict(),
        interval = 10000,   //number of milliseconds between each call
        cookieLifeTime = 1,   //number of days
        cookieName = 'live_results_sports',
        redrawSports = function () {
            var disInd = 0;

            jQuery('.bv-bet-default-index .wpmcpf-items .wpmcpf-item').each(function (id, item) {
                "use strict";
                var sports = _Cookies.getJSON(cookieName) || {},
                    sport = item.dataset.code,
                    $item = jQuery(item),
                    disabledClass = 'wpmcpf-item-disabled',
                    $sport_block = jQuery('.wpmc-page-content .item-block-' + sport);
                if (sports.hasOwnProperty(sport) && sports[sport] === false) {
                    $item.addClass(disabledClass);
                    $sport_block.hide();
                    disInd++;
                } else {
                    $item.removeClass(disabledClass);
                    $sport_block.show();
                }
                $item.off();
                $item.on("click", function (event) {
                    "use strict";

                    var sports = _Cookies.getJSON(cookieName) || {},
                        currSport = event.currentTarget.dataset.code;
                    if (Object.keys(sports).length == 0) {
                        jQuery('.bv-bet-default-index .wpmcpf-items .wpmcpf-item').each(function (cid, citem) {
                            var sport = citem.dataset.code;
                            sports[sport] = false;
                        });
                    }
                    sports[currSport] = sports.hasOwnProperty(currSport)
                        ? !sports[currSport]
                        : !!$item.hasClass(disabledClass);
                    _Cookies.set(cookieName, sports, {expires: cookieLifeTime, path: ''});
                    redrawSports();
                })
            });
            if (disInd > 0) {
                jQuery('.wpmcpf-current').hide();
                jQuery('.wpmcpf-show-all').show();
            } else {
                jQuery('.wpmcpf-current').show();
                jQuery('.wpmcpf-show-all').hide();
            }


        }, refresh = function () {
            jQuery.ajax({
                url: "/bet/live-results/rest",
                cache: false,
                dataType: 'json',
                success: function (json) {
                    jQuery('.bv-bet-default-index .wpmcpf-items .wpmcpf-item').off();
                    jQuery('.bet-default-index .wpmcpf-items').html(
                        json.filter.join('')
                    );
                    jQuery('.bet-default-index .wpmc-page-content').html(
                        json.content.join('')
                    );
                    setTimeout(function () {
                        refresh();
                    }, interval);
                    redrawSports();
                }
            });
        };
    refresh();
});
