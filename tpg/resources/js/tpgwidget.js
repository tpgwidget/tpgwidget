const $ = Dom7;
let stops = []; // Routes stops autocomplete

if (('standalone' in window.navigator) && !window.navigator.standalone) { // Add to home screen
    const stopName = $('.center').text();
    $.get('/ath.html', data => {
        $('body').html(data);
        $('#ath-stop-name').text(stopName);
    });
} else {
    const f7 = new Framework7({
        statusbarOverlay: false,
        scrollTopOnStatusbarClick: true,
        cache: false,
    });

    // Add view
    const mainView = f7.addView('.view-main', {
        dynamicNavbar: true
    });

    /**
     * Adapted from Framework7 notifications, to make it allow multiple disruptions open at the same time
     */
    let _tempDisruptionsElement;
    f7.addDisruption = function (params) {
        if (!params) return;
        params.place = typeof params.place === 'string' ? `<small>${params.place}</small> — ` : '';

        if (!_tempDisruptionsElement) _tempDisruptionsElement = document.createElement('div');

        var container = $('.notifications');
        if (container.length === 0) {
            f7.root.append('<div class="accordion-list notifications list-block media-list"><ul></ul></div>');
            container = $('.notifications');
        }
        var list = container.children('ul');

        var disruptionsTemplate = '<li class="notification-item notification-hidden accordion-item">' +
            '<div class="item-content">' +
                '<div class="item-inner">' +
                    '<div class="item-title-row">' +
                        '<div class="item-title">{{nature}}</div>' +

                        '<div class="item-after"><a href="#" class="toggle-disruption"><span></span></a></div>' +
                    '</div>' +

                    '<div class="accordion-item-content">' +
                        '<p>{{place}}{{consequence}}</p>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</li>'

        if (!f7._compiledTemplates.disruption) {
            f7._compiledTemplates.disruption = Template7.compile(disruptionsTemplate);
        }
        _tempDisruptionsElement.innerHTML = f7._compiledTemplates.disruption(params);

        var item = $(_tempDisruptionsElement).children();

        item.on('click', function (e) {
            f7.accordionToggle(item);
        });

        list.append(item[0]);
        container.show();

        var itemHeight = item.outerHeight(), clientLeft;

        item.transform('translate3d(0,' + (-itemHeight) + 'px,0)');
        item.transition(0);

        clientLeft = item[0].clientLeft;

        item.transition('');
        item.transform('translate3d(0,0px,0)');

        container.transform('translate3d(0, 0,0)');
        item.removeClass('notification-hidden');

        return item[0];
    };

    $.ajax({
        url: `/ajax/ajaxprochainsdeparts.php?id=${$(".page-index").attr('data-page').split('-')[1]}`,
        cache: false,
        success: (result) => {
            $('.page-index .page-content').html(result);
            $('.page-index').removeClass('layout-dark');
        },
        error: () => {
            $('.preloader').addClass("smileyErreur");
            $('.preloader').removeClass("preloader");
            $('.preloader-white').removeClass("preloader-white");
            $('.graym').append("<span>Impossible de se connecter au serveur TPGwidget</span>");
            $('.graym h2').html("Erreur");
        }
    });

    $(document).on('ajaxStart', function (e) {
        f7.showPreloader('Chargement...')
    });

    $(document).on('ajaxComplete', function () {
        f7.hidePreloader();
        $('.modal-overlay').removeClass('modal-overlay-visible');
    });

    $(document).on('pageBeforeAnimation', function (e) {
        f7.closeNotification(".notifications");

        const page = e.detail.page;
        const p = page.name.split("-");

        if (p[0] === 'infotraffic'){
            $('.pull-to-refresh-content').on('refresh', function (e) {
                $.ajax({ url: '/ajax/ajaxperturbations.php', cache: false, success: (result) => {
                    $('#perturbations-all').html(result);
                    f7.pullToRefreshDone();
                }});
            });
        }

        const $nav = $('.navbar, .subnavbar');
        if (p[0] === 'depart') {
            $nav.css('background-color', p[1]);

            const incidents = [];

            if (p[2]) {
                $nav.addClass('theme-black');
                $nav.removeClass('theme-white');
            } else {
                $nav.removeClass('theme-black');
                $nav.addClass('theme-white');
            }
        } else {
            $nav.css('background-color', '#fb6400');
            $nav.removeClass('theme-black');
            $nav.addClass('theme-white');
        }

    });

    $(document).on('pageAfterAnimation', function (e) {
        // Get page data from event data
        var page = e.detail.page;
        var p = page.name.split("-");

        if (p[0] === 'depart' && page.from != "left") {
            const $page = $('.page-depart .page-content');
            scrollTo(
                $page[0],
                Math.min($('.current').offset().top - 88, $page[0].scrollHeight - $page.height()),
                500
            );
        }

        if (p[0] === 'depart' && $('.disruptions-data').length) {

            $(page.container).find('.page-content').css('padding-bottom', "150px");

            const disruptions = JSON.parse($('.disruptions-data').text());

            disruptions.forEach((disruption, index) => {
                f7.addDisruption(disruption);
            });

            f7.accordionOpen($('.notification-item:first-child'));
        }

        if (p[0] == 'page' || p[0] == 'index') {
            $.ajax({
                url: `/ajax/ajaxprochainsdeparts.php?id=${p[1]}`,
                cache: false,
                success: (result) => {
                    $(page.container).find('.page-content').html(result);
                    $('.page-page').removeClass('layout-dark');
                    $('.page-index').removeClass('layout-dark');
                }
            });
        }

    });

    $(document).on('click', '.show-m', function (e) {
        $('.show-h').removeClass('active');
        $('.show-m').addClass('active');
        $('.h').hide();
        $('.m').show();
    });

    $(document).on('click', '.show-h', function (e) {
        $('.show-m').removeClass('active');
        $('.show-h').addClass('active');
        $('.h').show();
        $('.m').hide();
    });

    f7.onPageInit('itineraire', function () {
        $('form.ajax-submit').on('submitted', function (e) {
            mainView.router.load({
                content: e.detail.data.replace(/SCREENWIDTH/g, screen.width)
            });
        });

        $('.itineraire-invert').on('click', () => {
            const arrivee = $('input[name="depart"]').val();
            const depart  = $('input[name="arrivee"]').val();

            $('input[name="depart"]').val(depart);
            $('.itineraire-depart .item-after').text(depart !== '' ? depart : 'Cliquez pour choisir');

            $('input[name="arrivee"]').val(arrivee);
            $('.itineraire-arrivee .item-after').text(arrivee !== '' ? arrivee : 'Cliquez pour choisir');
        });

        $('.heure-depart').on('click', function(){
            $(this).addClass('active');
            $('.heure-arrivee').removeClass('active');
            $('#isArrivalTime').val('0');
        });

        $('.heure-arrivee').on('click', function(){
            $(this).addClass('active');
            $('.heure-depart').removeClass('active');
            $('#isArrivalTime').val('1');
        });

        if (stops.length === 0) {
            $.ajax({
                url: '/itineraire/stops.json',
                method: 'GET',
                dataType: 'json',
                success(data) {
                    stops = data;
                },
            });
        }

        genererAutocomplete('depart', 'Départ');
        genererAutocomplete('arrivee', 'Arrivée');
    });

    f7.onPageInit('trajets', () => {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination'
        });
    });

    f7.onPageInit('arrets', () => {
        $.ajax({
            url: '/arrets/arrets.json',
            dataType: 'json',
            success(data){
                const template = '<li>'+
                    '<a href="/ajax/page/{{stopCode}}/{{stopNameOriginal}}" class="item-link">'+
                    '<div class="item-content">'+
                    '<div class="item-inner">'+
                    '<div class="item-title">{{stopNameDisplay}}</div>'+
                    '</div>'+
                    '</div>'+
                    '</a>'+
                    '</li>';

                const normalizeForSearch = (word) => {
                    word = word.toLowerCase();

                    word = word.replace(/[éèê]/g, 'e');
                    word = word.replace(/[àâ]/g, 'a');
                    word = word.replace(/[îï]/g, 'i');
                    word = word.replace(/[ûù]/g, 'u');
                    word = word.replace(/[ôö]/g, 'o');

                    word = word.replace(/'/g, '’'); // Use curly apostrophes
                    word = word.replace(/[ \.\(\)\+-]/g, ''); // Remove spaces, dots and dashes

                    return word.trim();
                };

                f7.virtualList('.virtual-list', {
                    items: data,
                    template: template,
                    searchAll(query, stops) {
                        query = normalizeForSearch(query);

                        return Object.keys(stops).filter((stopIndex) => { // We need to return the item indexes
                            const stop = stops[stopIndex];

                            return normalizeForSearch(stop.stopNameRaw).includes(query)
                                || normalizeForSearch(stop.stopCode).includes(query)
                                || normalizeForSearch(stop.stopNameOriginal).includes(query);
                        });
                    },
                });
            },
        });

        // Localisation
        if ('geolocation' in navigator) {

            $('.location-message').hide();
            $('.enable-geolocation').show();

            $('.enable-geolocation').on('click', function(){
                // Quand l'utilisateur appuie sur "Afficher les arrêts à proximité"

                // On retire le bouton
                $('.enable-geolocation').hide();

                // On affiche le message de loading
                $('.location-message').css('display', 'flex');

                // On récupère sa position
                navigator.geolocation.getCurrentPosition((position) => {

                    // On envoie au serveur sa position
                    $.ajax({
                        url: '/arrets/geolocation.json',
                        dataType: 'json',
                        data: {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        },
                        success(nearStops) {

                            if (nearStops.length === 0) { // aucun arrêt
                                $('.location-message .item-title').text('Aucun arrêt proche trouvé');
                            } else {

                                $('.location-message').hide();

                                for(var i = 0; i < nearStops.length; i++){
                                    var stop = nearStops[i];

                                    var html = '<li>'+
                                    '<a href="/ajax/page/'+stop.stopCode+'/'+stop.stopNameOriginal+'" class="item-link item-content">'+
                                    '<div class="item-media">'+
                                    '<i class="icon icon-location"></i>'+
                                    '</div>'+
                                    '<div class="item-inner">'+
                                    '<div class="item-title">'+stop.stopNameDisplay+'</div>'+
                                    '</div>'+
                                    '</a>'+
                                    '</li>';

                                    $('.arrets-location ul').append(html);
                                }
                            }
                        }
                    });
                }, function(){ // Impossible d'obtenir la localisation
                $('.location-message .item-title').text("Impossible d'obtenir votre position");
            });
        });

    } else {
        $('.arrets-location').remove();
    }

    // Quand on recherche un arrêt,
    // on cache les arrêts à proximité
    $('#arrets-search').on('keyup', function() {
        if ($(this).val().trim() !== ''){
            $('.arrets-location').hide();
        } else {
            $('.arrets-location').show();
        }
    });

    });

    function genererAutocomplete(sens, titre) { // sens = 'depart' ou 'arrivee'
        f7.autocomplete({
            openIn: 'page',
            opener: $(`.itineraire-${sens}`),
            backOnSelect: true,
            source(autocomplete, query, render) {
                var results = [];

                if (query.length === 0) {
                    render(stops);
                    return;
                }

                for (var i = 0; i < stops.length; i++) {
                    if (stops[i].toLowerCase().indexOf(query.toLowerCase()) >= 0) {
                        results.push(stops[i]);
                    }
                }

                render(results);
            },
            onOpen() {
                $('.autocomplete-page .searchbar-input input').focus();
            },
            onChange(autocomplete, value) {
                $('.itineraire-' + sens).find('.item-after').text(value[0]);
                $('.itineraire-' + sens).find('input').val(value[0]);
            },
            pageTitle: titre,
            navbarTheme: 'white',
            backText: 'Retour',
            notFoundText: 'Aucun arrêt trouvé',
            searchbarPlaceholderText: 'Rechercher…',
            searchbarCancelText: 'Annuler',
            requestSourceOnOpen: true,
        });
    }
}

// scrollTo animation without jQuery
// Source : https://gist.github.com/andjosh/6764939

function scrollTo(element, to, duration) {
    var start = element.scrollTop,
    change = to - start,
    currentTime = 0,
    increment = 20;

    var animateScroll = () => {
        currentTime += increment;
        var val = Math.easeInOutQuad(currentTime, start, change, duration);
        element.scrollTop = val;
        if (currentTime < duration) {
            setTimeout(animateScroll, increment);
        }
    };

    animateScroll();
}

/**
* @param  {Number} t current time
* @param  {Number} b start value
* @param  {Number} c change in value
* @param  {Number} d duration
* @return {Number}
*/
Math.easeInOutQuad = function (t, b, c, d) {
    t /= (d / 2);
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c/2 * (t*(t-2) - 1) + b;
};
