const $$ = Dom7;
let stops = []; // Routes stops autocomplete

if (('standalone' in window.navigator) && !window.navigator.standalone) { // Add to home screen
    const stopName = $$('.center').text();
    $$.get('/ath.html', data => {
        $$('body').html(data);
        $$('#ath-stop-name').text(stopName);
    });
} else {
    const f7 = new Framework7({
        statusbarOverlay: false,
        scrollTopOnStatusbarClick: true,
        cache: false,
        notificationCloseIcon: false
    });

    // Add view
    const mainView = f7.addView('.view-main', {
        dynamicNavbar: true
    });

    $$.ajax({
        url: `/ajax/ajaxprochainsdeparts.php?id=${$$(".page-index").attr('data-page').split('-')[1]}`,
        cache: false,
        success: (result) => {
            $$('.page-index .page-content').html(result);
            $$('.page-index').removeClass('layout-dark');
        },
        error: () => {
            $$('.preloader').addClass("smileyErreur");
            $$('.preloader').removeClass("preloader");
            $$('.preloader-white').removeClass("preloader-white");
            $$('.graym').append("<span>Impossible de se connecter au serveur TPGwidget</span>");
            $$('.graym h2').html("Erreur");
        }
    });

    $$(document).on('ajaxStart', function (e) {
        f7.showPreloader('Chargement...')
    });

    $$(document).on('ajaxComplete', function () {
        f7.hidePreloader();
    });

    let popupHTML = '';
    $$(document).on('click', '.open-disruptions', () => {
        f7.popup(popupHTML);
    });

    $$(document).on('pageBeforeAnimation', function (e) {
        f7.closeNotification(".notifications");

        var page = e.detail.page;
        var p = page.name.split("-");

        if (p[0] == 'infotraffic'){
            $$('.pull-to-refresh-content').on('refresh', function (e) {
                $$.ajax({ url: '/ajax/ajaxperturbations.php', cache: false, success: (result) => {
                    $$('#perturbations-all').html(result);
                    f7.pullToRefreshDone();
                }});
            });
        }

        if (p[0] === 'depart') {
            $$('.navbar, .subnavbar').css('background-color', `#${p[1]}`);

            const incidents = [];

            const $disruptions = $$('.disruptions-data');

            if ($disruptions.length) {
                const disruptionsHtml = JSON.parse($disruptions.text()).reduce((acc, disruption) => `${acc}<div class="card">
                    <div class="card-header">
                    ${disruption.nature}
                    </div>
                    <div class="card-content">
                        <div class="card-content-inner">
                            ${disruption.consequence}
                        </div>
                    </div>
                </div>`, '');

                popupHTML = `<div class="popup popup-perturbations">
                    <div class="content-block">
                    ${disruptionsHtml}
                        <div class="actions-modal-group">
                            <div class="actions-modal-button color-red close-popup">Fermer</div>
                        </div>
                    </div>
                </div>`;
            }

            if (p[2]) {
                $$('.navbar, .subnavbar').addClass('theme-black');
                $$('.navbar, .subnavbar').removeClass('theme-white');
            } else {
                $$('.navbar, .subnavbar').removeClass('theme-black');
                $$('.navbar, .subnavbar').addClass('theme-white');
            }
        } else {
            $$('.navbar, .subnavbar').css('background-color', '#f60');
            $$('.navbar, .subnavbar').removeClass('theme-black');
            $$('.navbar, .subnavbar').addClass('theme-white');
        }

    });

    $$(document).on('pageAfterAnimation', function (e) {
        // Get page data from event data
        var page = e.detail.page;
        var p = page.name.split("-");

        if (p[0] === 'depart' && page.from != "left") {
            const $page = $$('.page-depart .page-content');
            scrollTo(
                $page[0],
                Math.min($$('.current').offset().top - 88, $page[0].scrollHeight - $page.height()),
                500
            );
        }

        if (p[0] === 'depart' && $$('.disruptions-data').length) {

            $$(page.container).find('.page-content').css('padding-bottom', "150px");

            JSON.parse($$('.disruptions-data').text()).forEach((d) => {
                f7.addNotification({
                    title: d.nature,
                    message: '<a href="#" class="button open-disruptions">En savoir plus</a>'
                });
            });
        }

        if (p[0] == 'page' || p[0] == 'index') {
            $$.ajax({
                url: `/ajax/ajaxprochainsdeparts.php?id=${p[1]}`,
                cache: false,
                success: (result) => {
                    $$(page.container).find('.page-content').html(result);
                    $$('.page-page').removeClass('layout-dark');
                    $$('.page-index').removeClass('layout-dark');
                }
            });
        }

    });

    $$(document).on('click', '.show-m', function (e) {
        $$('.show-h').removeClass('active');
        $$('.show-m').addClass('active');
        $$('.h').hide();
        $$('.m').show();
    });

    $$(document).on('click', '.show-h', function (e) {
        $$('.show-m').removeClass('active');
        $$('.show-h').addClass('active');
        $$('.h').show();
        $$('.m').hide();
    });

    f7.onPageInit('itineraire', function () {
        $$('form.ajax-submit').on('submitted', function (e) {
            mainView.router.load({
                content: e.detail.data.replace(/SCREENWIDTH/g, screen.width)
            });
        });

        $$('.heure-depart').on('click', function(){
            $$(this).addClass('active');
            $$('.heure-arrivee').removeClass('active');
            $$('#isArrivalTime').val('0');
        });

        $$('.heure-arrivee').on('click', function(){
            $$(this).addClass('active');
            $$('.heure-depart').removeClass('active');
            $$('#isArrivalTime').val('1');
        });

        if (stops.length === 0) {
            $$.ajax({
                url: '/itineraire/stops.json',
                method: 'GET',
                dataType: 'json',
                success(data) {
                    stops = data;
                },
            });

            genererAutocomplete('depart', 'Départ');
            genererAutocomplete('arrivee', 'Arrivée');
        }
    });

    f7.onPageInit('trajets', () => {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination'
        });
    });

    f7.onPageInit('arrets', () => {
        $$.ajax({
            url: '/arrets/arrets.json',
            dataType: 'json',
            success: function(data){
                var template = '<li>'+
                '<a href="/ajax/page/{{stopCode}}/{{stopName}}" class="item-link">'+
                '<div class="item-content">'+
                '<div class="item-inner">'+
                '<div class="item-title">{{stopName}}</div>'+
                '</div>'+
                '</div>'+
                '</a>'+
                '</li>';

                f7.virtualList('.virtual-list', {
                    items: data,
                    template: template,
                    searchAll: function (query, items) {
                        var foundItems = [];
                        for (var i = 0; i < items.length; i++) {
                            if (items[i].stopCode.toLowerCase().indexOf(query.toLowerCase().trim()) >= 0 || items[i].stopName.toLowerCase().indexOf(query.toLowerCase().trim()) >= 0) {
                                foundItems.push(i);
                            }
                        }
                        return foundItems;
                    }
                });
            }
        });

        // Localisation
        if ('geolocation' in navigator) {

            $$('.location-message').hide();
            $$('.enable-geolocation').show();

            $$('.enable-geolocation').on('click', function(){
                // Quand l'utilisateur appuie sur "Afficher les arrêts à proximité"

                // On retire le bouton
                $$('.enable-geolocation').hide();

                // On affiche le message de loading
                $$('.location-message').css('display', 'flex');

                // On récupère sa position
                navigator.geolocation.getCurrentPosition((position) => {

                    // On envoie au serveur sa position
                    $$.ajax({
                        url: '/arrets/geolocation.json',
                        dataType: 'json',
                        data: {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        },
                        success(nearStops) {

                            if (nearStops.length === 0) { // aucun arrêt
                                $$('.location-message .item-title').text('Aucun arrêt proche trouvé');
                            } else {

                                $$('.location-message').hide();

                                for(var i = 0; i < nearStops.length; i++){
                                    var stop = nearStops[i];

                                    var html = '<li>'+
                                    '<a href="/ajax/page/'+stop.stopCode+'/'+stop.stopName+'" class="item-link item-content">'+
                                    '<div class="item-media">'+
                                    '<i class="icon icon-location"></i>'+
                                    '</div>'+
                                    '<div class="item-inner">'+
                                    '<div class="item-title">'+stop.stopName+'</div>'+
                                    '</div>'+
                                    '</a>'+
                                    '</li>';

                                    $$('.arrets-location ul').append(html);
                                }
                            }
                        }
                    });
                }, function(){ // Impossible d'obtenir la localisation
                $$('.location-message .item-title').text("Impossible d'obtenir votre position");
            });
        });

    } else {
        $$('.arrets-location').remove();
    }

    // Quand on recherche un arrêt,
    // on cache les arrêts à proximité
    $$('#arrets-search').on('keyup', () => {
        if ($$(this).val().trim() !== ''){
            $$('.arrets-location').hide();
        } else {
            $$('.arrets-location').show();
        }
    });

    });

    function genererAutocomplete(sens, titre) { // sens = 'depart' ou 'arrivee'
        f7.autocomplete({
            openIn: 'page',
            opener: $$(`.itineraire-${sens}`),
            backOnSelect: true,
            source(autocomplete, query, render) {
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
            onChange(autocomplete, value){
                $$('.itineraire-' + sens).find('.item-after').text(value[0]);
                $$('.itineraire-' + sens).find('input').val(value[0]);
            },
            pageTitle: titre,
            navbarTheme: 'white',
            backText: 'Retour',
            notFoundText: 'Aucun arrêt trouvé',
            searchbarPlaceholderText: 'Rechercher…',
            searchbarCancelText: 'Annuler',
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
