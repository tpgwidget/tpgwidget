var f7 = new Framework7({
    material: true,
    statusbarOverlay: false,
    scrollTopOnStatusbarClick: true,
    cache: false,
    notificationCloseIcon: false,
});

var $ = Dom7;
let stops = []; // Routes stops autocomplete

var mainView = f7.addView('.view-main', {
    pushState: true,
});

// Make the back button work
window.history.pushState(null, null, window.location.pathname);

/* Set Android Back Button  */
document.addEventListener("backbutton", function(){
    mainView.router.back();

    // Exit from app if user press back in home page
    if($('.page-current').data('name') === 'home'){
        navigator.app.exitApp();
    }
}, false);

if (f7.device.android) {
    window.oncontextmenu = function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    };
}

var isStandalone = window.matchMedia('(display-mode: standalone)').matches;
