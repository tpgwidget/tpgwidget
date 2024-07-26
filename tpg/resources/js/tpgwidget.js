const $ = Dom7;

const f7 = new Framework7({
    statusbarOverlay: false,
    scrollTopOnStatusbarClick: true,
    cache: false,
});

f7.addView('.view-main', {
    dynamicNavbar: true
});
