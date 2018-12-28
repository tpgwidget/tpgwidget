var f7 = new Framework7({
    material: true,
    statusbarOverlay: false,
    scrollTopOnStatusbarClick: true,
    cache: false,
    notificationCloseIcon: false
});

var $ = Dom7;
let stops = []; // Routes stops autocomplete

var mainView = f7.addView('.view-main', {});

$(document).on('ajaxStart', function(e) {
    f7.showPreloader('Chargement...');
});

$(document).on('ajaxComplete', function() {
    f7.hidePreloader();
});

if (f7.device.android) {
    window.oncontextmenu = function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    };
}

var isStandalone = window.matchMedia('(display-mode: standalone)').matches;

if (f7.device.android && !isStandalone) {
    $('.left').remove();
    $('.center').text('TPGwidget');
    $('.page-content').html(`<div class="content-block">
        <p>Pour installer un raccourci pour l’arrêt <strong>${$('.center').text()}</strong> :</p>
        <ul class="tutorial">
            <li>
                <div class="step-number">1</div>
                <p>Appuyez sur le bouton <strong>Plus <i class="more"></i></strong> tout en haut à droite de l\'écran</p>
            </li>
            <li>
                <div class="step-number">2</div>
                <p>Sélectionnez <strong>Ajouter à l’écran d’accueil</strong></p>
                <img src="/resources/img/ath.png" alt="Ajouter à l’écran d’accueil">
            </li>
        </ul>
    </div>`);
    $('.page-index').removeClass('layout-dark');
} else {
    $.ajax({
        url: `/ajax/ajaxprochainsdeparts.php?id=${$('.page-index').attr('data-page').split('-')[1]}`,
        cache: false,
        success: (result) => {
            $('.page-content').html(result);
            $('.page-index').removeClass('layout-dark');
        },
        error: () => {
            $('.preloader').addClass("smileyErreur");
            $('.preloader').removeClass("preloader");
            $('.preloader-white').removeClass('preloader-white');
            $('.page-content').html(`<div class="graym"><h2>Erreur</h2><span>Impossible de se connecter au serveur TPGwidget</span></div>`);
        }
    });

    $(document).on('pageBeforeAnimation', function(e) {
        f7.closeNotification(".notifications");

        var page = e.detail.page;
        var p = page.name.split("-");

        if (p[0] == 'infotraffic') {
            $('.pull-to-refresh-content').on('refresh', function(e) {
                $.ajax({
                    url: "/ajax/ajaxperturbations.php",
                    cache: false,
                    success: (result) => {
                        $('#perturbations-all').html(result);
                        f7.pullToRefreshDone();
                    }
                });
            });
        }

        function changeStatusbarColor(color) {
            $('meta[name=theme-color]').remove();
            $('head').append(`<meta name="theme-color" content="${color}">`);
        }

        if(p[0] == 'depart'){
            changeStatusbarColor(p[1]);
        } else if (p == 'vehicule' || p == 'itineraire') {
            changeStatusbarColor('#333');
        } else {
            changeStatusbarColor('#F60');
        }
    });

    $(document).on('pageAfterAnimation', function(e) {

        // Get page data from event data
        var page = e.detail.page;
        var p = page.name.split('-');

        if (p[0] === 'depart' && page.from !== 'left') {
            const $page = $('.page-depart .page-content');
            scrollTo(
                $page[0],
                Math.min($('.current').offset().top - 88, $page[0].scrollHeight - $page.height()),
                500
            );
        }

        if (p[0] === 'depart' && $('.pdata').length) {

            $(page.container).find('.page-content').css('padding-bottom', "150px");

            if ($('.pdata').length > 0) { // S'il y a des perturbations
                f7.addNotification({
                    message: $('.pdata').html(),
                    button: false
                });
            }
        }

        if (p[0] == 'page' || p[0] == 'index') {
            $.ajax({
                url: `/ajax/ajaxprochainsdeparts.php?id=${p[1]}`,
                cache: false,
                success: (result) => {
                    $(page.container).find('.page-content').html(result);
                    $('.page-page, .page-index').removeClass('layout-dark');
                }
            });
        }

    });

    $(document).on('click', '.show-m', function(e) {
        $('.show-h').removeClass('active');
        $('.show-m').addClass('active');
        $('.h').hide();
        $('.m').show();
        $('.tab-link-highlight').css('transform', 'translate3d(0%, 0px, 0px)');
    });

    $(document).on('click', '.show-h', function(e) {
        $('.show-m').removeClass('active');
        $('.show-h').addClass('active');
        $('.h').show();
        $('.m').hide();
        $('.tab-link-highlight').css('transform', 'translate3d(100%, 0px, 0px)');
    });

    // Back button pressed
    window.addEventListener('popstate', function(event) {
        // Stay on the current page.
        history.pushState(null, null, window.location.pathname);

        mainView.router.back();
    }, false);
}

f7.onPageInit('arrets', function(){
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

            f7.virtualList('.virtual-list', {
                items: data,
                template: template,
                searchAll(query, items) {
                    const foundItems = [];
                    for (let i = 0; i < items.length; i++) {
                        if (
                            items[i].stopCode.toLowerCase().indexOf(query.toLowerCase().trim()) >= 0
                            || items[i].stopNameRaw.toLowerCase().indexOf(query.toLowerCase().trim()) >= 0
                            || items[i].stopNameOriginal.toLowerCase().indexOf(query.toLowerCase().trim()) >= 0
                        ) {
                            foundItems.push(i);
                        }
                    }
                    return foundItems;
                },
            });
        },
    });

    // Localisation
    if ('geolocation' in navigator){

        $('.location-message').hide();
        $('.enable-geolocation').show();

        $('.enable-geolocation').on('click', function(){
            // Quand l'utilisateur appuie sur "Afficher les arrêts à proximité"

            // On retire le bouton
            $('.enable-geolocation').hide();
            // On affiche le message de loading
            $('.location-message').css('display', 'flex');

            // On récupère sa position
            navigator.geolocation.getCurrentPosition(function(position){

                // On envoie au serveur sa position
                $.ajax({
                    url: '/arrets/geolocation.json',
                    dataType: 'json',
                    data: {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    },
                    success(nearStops) {

                        if(nearStops.length == 0){ // aucun arrêt
                            $('.location-message .item-title').text('Aucun arrêt proche trouvé');
                        } else {

                            $('.location-message').hide();

                            for(var i = 0; i < nearStops.length; i++){
                                var stop = nearStops[i];

                                var html =  '<li>'+
                                            '<a href="/ajax/page/' + stop.stopCode + '/' + stop.stopNameOriginal+'" class="item-link item-content">'+
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
    $('#arrets-search').on('keyup', function(){
        if($(this).val().trim() !== ''){
            $('.arrets-location').hide();
        } else {
            $('.arrets-location').show();
        }
    });

});

f7.onPageInit('itineraire', function () {

    $('form.ajax-submit').on('submitted', function (e) {
        mainView.router.load({
            content: e.detail.data.replace(/SCREENWIDTH/g, screen.width)
        });
    });

    $('.itineraire-invert').on('click', () => {
        const arrivee = $('input[name="depart"]').val();
        const depart = $('input[name="arrivee"]').val();

        $('input[name="depart"]').val(depart);
        $('.itineraire-depart .item-after').text(depart !== '' ? depart : 'Cliquez pour choisir');

        $('input[name="arrivee"]').val(arrivee);
        $('.itineraire-arrivee .item-after').text(arrivee !== '' ? arrivee : 'Cliquez pour choisir');
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

f7.onPageInit('trajets', function(){
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination'
    });
});

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
        backText: 'Retour',
        notFoundText: 'Aucun arrêt trouvé',
        searchbarPlaceholderText: 'Rechercher…',
        searchbarCancelText: 'Annuler',
        requestSourceOnOpen: true,
    });
}
