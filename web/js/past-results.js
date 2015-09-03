jQuery(document).ready(function () {

    window.ownersTree = (function () {
        var r = {}, o = window['objectSports']['owners'];
        for (var i in o) {
            if (o.hasOwnProperty(i)) {
                var oid = o[i]['ownerid'];
                if (!r[oid]) {
                    r[oid] = {};
                }
                r[oid][Object.keys(r).length] = o[i]['id'];
            }
        }
        return r;
    })();

    var dateFormat = 'YYYY-MM-DD',
        todayDate = moment(new Date()),
        $navLeft = jQuery('.nev-c-left'),
        $navRight = jQuery('.nev-c-right'),
        $allSports = jQuery('.pr-filter-all-sports'),

        $calendarLine = jQuery('.pr-filter-inline-calendar'),

        pageLoading = false,

        pageInfo = {
            count: 0,
            page: 0,
            totalpages: 0
        },

        _Cookies = Cookies.noConflict(),
        cookieLifeTime = 1,   //number of days
        cookieName = 'past_results_sports',
        moreClass = 'more-block',

        postData = {},

        object_values = function (obj) {
            "use strict";

            return Object.keys(obj).map(function (key) {
                return obj[key]
            });
        },

        resetPost = function () {
            "use strict";

            return {
                sports: window['objectSports']['all'],
                dateFrom: false,
                dateTo: false,
                search: false,
                page: 1
            }
        },

        redrawSports = function () {
            "use strict";

            var firstObj = window['objectSports']['first'];

            jQuery('.bet-past-index .pr-filter-items .pr-filter-item').each(function (id, item) {
                "use strict";
                var sports = _Cookies.getJSON(cookieName) || postData.sports,
                    sport = item.dataset.sid,
                    $item = jQuery(item),
                    disabledClass = 'wpmcpf-item-disabled';
                if (sports.hasOwnProperty(sport) && sports[sport] === false) {
                    $item.addClass(disabledClass);
                } else {
                    $item.removeClass(disabledClass);
                }
                $item.off();
                $item.on("click", function (event) {
                    "use strict";

                    var sports = _Cookies.getJSON(cookieName) || postData.sports,
                        currSport = event.currentTarget.dataset.sid;
                    if (JSON.stringify(sports) === JSON.stringify(resetPost().sports)) {
                        for (var i in firstObj) {
                            if (firstObj.hasOwnProperty(i)) {
                                sports[firstObj[i]['id']] = false;
                            }
                        }
                    }
                    sports[currSport] = sports.hasOwnProperty(currSport)
                        ? !sports[currSport]
                        : !!$item.hasClass(disabledClass);
                    _Cookies.set(cookieName, sports, {expires: cookieLifeTime, path: ''});
                    postData.sports = sports;
                    redrawSports();
                })
            });

        },

        calendarOnClick = function (event) {
            "use strict";

            $calendarLine[0].dataset.selected = moment(event.currentTarget.dataset.date).format(dateFormat);

            $calendarLine.find('li[data-date]').each(function (id, item) {
                "use strict";

                jQuery(item).removeClass('active');

            });

            jQuery(event.currentTarget).addClass('active');

        },

        navLeftClick = function (event) {
            "use strict";

            event.preventDefault();
            updateCalendarLine(moment($calendarLine[0].dataset.to).subtract(7, 'days').format(dateFormat));
            return false;
        },

        navRightClick = function (event) {
            "use strict";

            event.preventDefault();
            updateCalendarLine(moment($calendarLine[0].dataset.to).add(7, 'days').format(dateFormat));
            return false;
        },

        toggleMoreSportsArea = function (status /**status='hide' || 'show'*/) {
            "use strict";

            var $more = jQuery('.' + moreClass);
            jQuery('.owners-block').hide();

            if ($more.hasClass('i-visible-block')) {
                if (status != 'show') {
                    $more.removeClass('i-visible-block');
                }
            } else {
                if (status != 'hide') {
                    $more.addClass('i-visible-block');
                }
            }
        },

        updateMoreSports = function (ownerid) {
            "use strict";

            var sports = _Cookies.getJSON(cookieName) || postData.sports,
                $moreSports,
                ownersObj = window['objectSports']['owners'],
                walkingObj = window['objectSports']['more'],
                x = 0,
                y = 0;

            for (var o in ownersObj) {
                if (ownersObj.hasOwnProperty(o)) {
                    walkingObj.push(ownersObj[o]);
                }
            }

            if ($allSports.find('.' + moreClass).length < 1) {

                $allSports.append(
                    jQuery('<div>').attr('class', moreClass + ' pr-filter-more-sports pr-page-filter').
                        append(jQuery('<div>').attr('class', 'pr-div-block')).hide()
                ).append(
                    jQuery('<div>').attr('class', moreClass + ' pr-filter-separator').hide()
                );

            }

            $moreSports = jQuery('.pr-filter-more-sports .pr-div-block');
            $moreSports.find('.pr-sport-item').off();
            $moreSports.empty();

            for (var i in walkingObj) {
                if (walkingObj.hasOwnProperty(i)) {
                    var sport = walkingObj[i];

                    if ((sports[sport['id']] === true || sport['ownerid'] == 0) &&
                            // one sport can be in some other groups, but here we need only one
                        $moreSports.find('.pr-si-' + sport['id']).length == 0
                    ) {

                        $moreSports.append(
                            jQuery('<span>').
                                attr({
                                    'class': 'pr-sport-item' + (x == 5 ? ' last' : '') + ' pr-si-' + sport['id'] +
                                    (sports[sport['id']] === true || ownerid == sport['id'] ? ' active' : ''),
                                    'data-sid': sport['id']
                                }).append(
                                jQuery('<span>').
                                    text(sport['name']).
                                    attr({
                                        'class': ( window.ownersTree.hasOwnProperty(sport['id']) &&
                                            Object.keys(window.ownersTree[sport['id']]).length > 0
                                                ? 'icon-prjExpand'
                                                : ''
                                        )
                                    })
                            )
                        ).find('.pr-si-' + sport['id']).on('click', onClickSportItem);
                        x++;
                        if (x > 5) {
                            x = 0;
                            y++;
                        }
                    }
                }
            }
            toggleMoreSportsArea('show');
            if (window.ownersTree.hasOwnProperty(ownerid) && Object.keys(window.ownersTree[ownerid]).length > 0) {
                updateOtherSports(ownerid);
            }
        },

        updateOtherSports = function (id) {
            "use strict";

            var sports = _Cookies.getJSON(cookieName) || postData.sports,
                $owners, sport,
                ownersObj = window['objectSports']['owners'],
                x = 0,
                y = 0;

            if ($allSports.find('.owners-block').length < 1) {

                $allSports.append(
                    jQuery('<div>').attr('class', 'owners-block pr-filter-more-sports pr-page-filter').
                        append(jQuery('<div>').attr('class', 'pr-div-block')).hide()
                ).append(
                    jQuery('<div>').attr('class', 'owners-block pr-filter-separator').hide()
                );

            }

            $owners = jQuery('.owners-block .pr-div-block');
            $owners.find('.pr-sport-item').off();
            $owners.empty();

            if (window.ownersTree.hasOwnProperty(id) &&
                Object.keys(window.ownersTree[id]).length > 0) {

                for (var i in ownersObj) {
                    if (ownersObj.hasOwnProperty(i) &&
                        ownersObj[i]['ownerid'] == id &&
                        sports[ownersObj[i]['id']] === false) {

                        sport = ownersObj[i];
                        $owners.append(
                            jQuery('<span>').
                                attr({
                                    'class': 'pr-sport-item' + (x == 5 ? ' last' : '') + ' pr-si-' + sport['id'],
                                    'data-oid': sport['ownerid'],
                                    'data-sid': sport['id']
                                }).append(
                                jQuery('<span>').
                                    text(sport['name'])
                            )
                        ).find('.pr-si-' + sport['id']).on('click', onClickSportItem);
                        x++;
                        if (x > 5) {
                            x = 0;
                            y++;
                        }
                    }
                }
                if (x > 0 || y > 0) {
                    jQuery('.owners-block').show();
                } else {
                    jQuery('.owners-block').hide();
                }
            } else {
                jQuery('.owners-block').hide();
            }
        },

        onClickSportItem = function (event) {
            "use strict";

            var sports = _Cookies.getJSON(cookieName) || postData.sports,
                item = event.currentTarget,
                currSport = item.dataset.sid,
                $item = jQuery(item);

            jQuery('.' + moreClass + '.pr-filter-more-sports .pr-div-block .icon-prjExpand').parent().removeClass('active');
            if (item.dataset.hasOwnProperty('oid') && parseInt(item.dataset['oid']) == 0) {
                jQuery('.owners-block').hide();
            }

            if ($item.find('.icon-prjExpand').length < 1) {
                sports[currSport] = !sports[currSport];
                _Cookies.set(cookieName, sports, {expires: cookieLifeTime, path: ''});
                postData.sports = sports;
            }

            updateMoreSports(item.dataset['oid'] ? item.dataset['oid'] : item.dataset['sid']);
//            updateOtherSports(item.dataset['sid']);
        },

        updateCalendarLine = function (/** moment*/endDate) {
            "use strict";

            endDate = (moment(endDate).format('X') > todayDate.format('X')
                    ? moment(todayDate.format(dateFormat))
                    : moment(moment(endDate).format(dateFormat))
            );

            $calendarLine[0].dataset.selected = '';
            $calendarLine[0].dataset.to = endDate.format(dateFormat);
            $calendarLine[0].dataset.from = endDate.subtract(6, 'days').format(dateFormat);

            var fromDate = moment($calendarLine[0].dataset.from);
            $calendarLine.find('li[data-date]').each(function (id, item) {
                "use strict";

                item.dataset.date = fromDate.format(dateFormat);

                var $item = jQuery(item),
                    itemDate = moment(item.dataset.date),
                    $a = $item.find('a');

                $a.empty().
                    append(itemDate.format('D MMMM')).
                    append(document.createElement('br')).
                    append(itemDate.format('dddd'));

                $item.off();
                $a.off();

                $item.on("click", calendarOnClick);
                $a.on("click", calendarOnClick);

                fromDate.add(1, 'days');

                $item.removeClass('active');
            });

            var disCss = 'nev-c-disabled-nav';

            if ($navRight.find('.' + disCss).length < 1) {
                //$navRight.append();
                $navRight.append(jQuery('<div>').attr('class', disCss));
            }

            if (moment($calendarLine[0].dataset.to).format('YYYYMMDD') === todayDate.format('YYYYMMDD')) {
                $navRight.off();
                $navRight.find('a').hide();
                $navRight.find('.' + disCss).show();
            } else {
                $navRight.off();
                $navRight.on('click', navRightClick);
                $navRight.find('a').show();
                $navRight.find('.' + disCss).hide();
            }
        },

        dateRangeToggle = function (/*event*/) {
            var $dis = jQuery('.nev-c-disabled-block');

            if (jQuery(this).is(':checked')) {
                jQuery(this).closest('form').find('input[type="date"]').removeAttr('disabled');
                $dis.show();
            } else {
                jQuery(this).closest('form').find('input[type="date"]').attr('disabled', true);
                $dis.hide();
            }
        },

        getResults = function () {

            if (pageInfo.page == 0 || pageInfo.page < pageInfo.totalpages) {

                pageLoading = true;

                var ch = document.getElementById('drSelect').checked,
                    cDateA = jQuery('.pr-filter-inline-calendar').find('li.active'),
                    cDate = cDateA.length == 1 ? cDateA[0].dataset.date : moment(new Date()).format(dateFormat),
                    data = {
                        '_csrf': document.getElementById('past-results-form').elements['_csrf'].value,
                        'past-results-form': {
                            'sports': (function () {
                                "use strict";
                                var r = [];
                                for (var k in postData.sports) {
                                    if (postData.sports.hasOwnProperty(k) && postData.sports[k] === true) {
                                        r.push(k);
                                    }
                                }
                                return r.join(',');
                            })(),
                            'search': document.getElementById('ffsrInput').value.trim(),
                            'dateFrom': (ch ? document.getElementById('drFrom').value : cDate),
                            'dateTo': (ch ? document.getElementById('drTo').value : cDate),
                            'page': parseInt(pageInfo.page) + 1
                        }
                    };

                jQuery.ajax({
                    url: "/bet/past-results/rest",
                    cache: false,
                    dataType: 'json',
                    data: data,
                    method: 'POST'
                }).done(function (json) {
                    "use strict";
                    if (json.status == 2) {
                        jQuery('.bet-past-index .pr-filter-content').html(
                            '<h3 class="errors no-results-for-criteria">' + json.errors.join('</h3><h3>') + '</h3>'
                        );
                    } else if (json.status == 0) {
                        jQuery('.bet-past-index .pr-filter-content').html(
                            '<h3 class="errors">' + json.errors.join('</h3><h3>') + '</h3>'
                        );
                    } else if (json.status == 3) {
                    } else {
                        if (json.total.page == 1) {
                            jQuery('.bet-past-index .pr-content-table').html(
                                json.content.join('')
                            );
                        } else {
                            jQuery('.bet-past-index .pr-content-table table > tbody').append(
                                json.content.join('')
                            );
                        }
                    }
                    pageInfo = json.total;
                    pageInfo.count = parseInt(pageInfo.count);
                    pageInfo.page = parseInt(pageInfo.page);
                    pageInfo.totalpages = parseInt(pageInfo.totalpages);

                    pageLoading = false;
                }).always(function (json) {
                    "use strict";

                    pageLoading = false;
                });
            }
        };

    jQuery('#drSelect:checkbox').on('change', dateRangeToggle);

    jQuery('.pr-filter-date-range').on('reset', function () {
        "use strict";

        var $cb = jQuery('#drSelect:checkbox');

        $cb.attr('checked', false);
        dateRangeToggle.apply($cb[0]);

        return true;
    });

    jQuery('.ffsr-btn-clear').on('click', function (event) {
        "use strict";

        event.preventDefault();

        jQuery('.bet-past-index .pr-content-table').empty();

        jQuery('.pr-filter-form-search')[0].reset();
        jQuery('.pr-filter-date-range')[0].reset();

        updateCalendarLine(todayDate.format(dateFormat));

        calendarOnClick({
            currentTarget: document.getElementsByClassName('pr-filter-inline-calendar')[0].children[0].children[7]
        });

        return false;
    });

    jQuery('.ffsr-btn-submit').on('click', function (event) {
        "use strict";

        event.preventDefault();

        jQuery('.bet-past-index .pr-content-table').empty();

        pageInfo = {
            count: 0,
            page: 0,
            totalpages: 0
        };

        getResults();

        return false;
    });

    jQuery('.pr-filter-more-bth').on('click', function (event) {
        "use strict";

        event.preventDefault();

        toggleMoreSportsArea();
        if (jQuery('.' + moreClass).length == 0 || jQuery('.' + moreClass).hasClass('i-visible-block')) {
            updateMoreSports();
        }

        return false;
    });

    jQuery('.pr-filter-show-all').on('click', function (event) {
        "use strict";

        event.preventDefault();

        var reset = resetPost();

        postData.sports = reset.sports;

        _Cookies.set(cookieName, postData.sports, {expires: cookieLifeTime, path: ''});

        toggleMoreSportsArea('hide');

        redrawSports();

        return false;
    });

    $navLeft.off();
    $navRight.off();
    $navLeft.on('click', navLeftClick);
    $navRight.on('click', navRightClick);

    var reset = resetPost();
    postData = reset;
    postData.sports = _Cookies.getJSON(cookieName) || postData.sports;
    if (Object.prototype.toString.call(postData.sports) != '[object Object]' ||
        Object.keys(postData.sports).length != Object.keys(reset.sports).length) {
        postData.sports = reset.sports;
        _Cookies.set(cookieName, postData.sports, {expires: cookieLifeTime, path: ''});
    }

    updateCalendarLine(todayDate.format(dateFormat));
    calendarOnClick({
        currentTarget: document.getElementsByClassName('pr-filter-inline-calendar')[0].children[0].children[7]
    });
    redrawSports();

    jQuery(window).scroll(function () {

        if (pageInfo.totalpages > 0 && pageInfo.page < pageInfo.totalpages && pageLoading === false) {
            var wHeight = jQuery(document).height(),
                wScrollTop = jQuery(window).scrollTop(),
                posPercent = 100 / wHeight * wScrollTop,
                loadOnPercent = 80;

            if (posPercent > loadOnPercent) {
                console.log($(window).scrollTop(), posPercent);
                getResults();
            }

        }

    });

});
