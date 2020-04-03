<?php
class Vehicule {

    public $id;
    public $type;
    public $name;
    public $img;
    public $icon;
    public $enSavoirPlus = true;
    public $wifi = false;

    public $places_assises = null;
    public $places_debout = null;
    public $places_totales = null;

    public $tpg_link = null;

    public function __construct(string $id) {
        if (strlen($id) !== 4 && $id !== '') {
            if ($id[0] === '2') {
                $id = substr($id, 1);
            }

            if ($id[0] === '0') {
                $id = substr($id, 1);
            }
        }

        if ($id == 75) {
            $id = '???';
        }

        $this->id = $id;

        /* On détecte le modèle du véhicule */

        /* AUTOBUS */
        if (500 <= $id && $id <= 545) { // Citaro solo

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro O530";
            $this->icon = 'citaro';
            $this->img = 'citarosolo';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/f1vHBg';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530';
            $this->places_assises = 32;
            $this->places_debout = 55;
            $this->places_totales = 87;
            $this->year = "2008-2010";

        } elseif ((106 <= $id && $id <= 198) || (1101 <= $id && $id <= 1197)) { // Citaro articulé

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro O530G";
            $this->icon = 'citaro';
            $this->img = 'citaro';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kZ14Eq';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530g';
            $this->places_assises = 46;
            $this->places_debout = 103;
            $this->places_totales = 149;
            $this->year = "2008-2013";

        } elseif (1917 <= $id && $id <= 1918) { // Citaro articulé RATP Dev

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro O530G";
            $this->icon = 'citaro';
            $this->img = 'citaro';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kZ14Eq';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530g';
            $this->places_assises = 46;
            $this->places_debout = 103;
            $this->places_totales = 149;
            $this->year = "2011";

        } elseif (943 <= $id && $id <= 944) { // Citaro solo RATP Dev

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro O530 II";
            $this->icon = 'citaro';
            $this->img = 'citarosolo';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/f1vHBg';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530';
            $this->places_assises = 32;
            $this->places_debout = 55;
            $this->places_totales = 87;

        } elseif (1912 <= $id && $id <= 1916) { // Citaro solo RATP Dev

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro O530 II";
            $this->icon = 'citaro';
            $this->img = 'citarosolo';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/f1vHBg';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530';
            $this->places_assises = 32;
            $this->places_debout = 55;
            $this->places_totales = 87;

        } elseif (2022 <= $id && $id <= 2025 || $id == 2028) { // MAN solo RATP

            $this->type = "Autobus solo";
            $this->name = "MAN Lion's City";
            $this->icon = 'lionscity';
            $this->img = '2022';
            $this->year = 2018;
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif (101 <= $id && $id <= 105) { // Mégabus VanHool

            $this->type = "Autobus à double articulation";
            $this->name = "VanHool AGG300 New";
            $this->icon = 'megabus';
            $this->img = 'vanhool';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/dr1LvG';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-vanhool-agg300';
            $this->places_assises = 70;
            $this->places_debout = 104;
            $this->places_totales = 174;
            $this->year = "2005";

        /* TROLLEYBUS */

        } elseif (1271 <= $id && $id <= 1283) { // TOSA

            $this->type = 'Autobus articulé électrique';
            $this->name = "TOSA";
            $this->icon = 'tosa';
            $this->img = 'tosa';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kYYeMp';
            $this->tpg_link = 'https://www.tpg.ch/fr/tosa';
            $this->year = '2017';
            $this->wifi = true;

        } elseif (701 <= $id && $id <= 713) { // NAW

            $this->type = "Trolleybus";
            $this->name = "NAW/Hess/Siemens BGT-N2";
            $this->icon = 'naw';
            $this->img = 'naw';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/gnTYVE';
            $this->tpg_link = 'https://www.tpg.ch/fr/trolleybus-naw-siemens';
            $this->places_assises = 43;
            $this->places_debout = 107;
            $this->places_totales = 150;
            $this->year = "1992-1993";

        } elseif ($id == 721) { // NAW mégatrolleybus (protoype LighTram)

            $this->type = "Trolleybus à double articulation";
            $this->name = "NAW/Hess/Siemens LighTram";
            $this->icon = 'hess';
            $this->img = '721';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/GeFaWU';
            $this->tpg_link = 'https://www.tpg.ch/fr/trolleybus-naw-siemens-lightram';
            $this->places_assises = 57;
            $this->places_debout = 122;
            $this->places_totales = 179;
            $this->year = "2003";

        } elseif (731 <= $id && $id <= 768) { // Trolleybus Hess

            $this->type = "Trolleybus";
            $this->name = "Hess/Kiepe Swisstrolley";
            $this->icon = 'hess';
            $this->img = 'hess';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/nGST6m';
            $this->tpg_link = 'https://www.tpg.ch/fr/trolleybus-hess';
            $this->places_assises = 46;
            $this->places_debout = 80;
            $this->places_totales = 126;
            $this->year = "2005";

        } elseif (781 <= $id && $id <= 790) { // Mégatrolleybus Hess

            $this->type = "Trolleybus à double articulation";
            $this->name = "Hess/Kiepe LighTram";
            $this->icon = 'hess';
            $this->img = 'megahess';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/eEUQu5';
            $this->tpg_link = 'https://www.tpg.ch/fr/trolleybus-hess-lightram';
            $this->places_assises = 66;
            $this->places_debout = 126;
            $this->places_totales = 192;
            $this->year = "2005-2006";
            $this->wifi = true;

        } elseif (1601 <= $id && $id <= 1633) { // Exqui City

            $this->type = "Trolleybus";
            $this->name = "VanHool Exqui.City";
            $this->icon = 'exquicity';
            $this->img = 'exquicity';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/pzQnpM';
            $this->tpg_link = 'https://www.tpg.ch/fr/trolleybus-vanhool-exqui.city';
            $this->places_assises = 41;
            $this->places_debout = 90;
            $this->places_totales = 131;
            $this->year = "2014";
            $this->wifi = true;

        /* TRAMWAYS */


        } elseif (1820 == $id) { // Tango rose

            $this->type = "Tramway";
            $this->name = "Stadler Tango";
            $this->icon = '1820';
            $this->img = '1820';
            $this->img_author = 'Stéphane Wicht';
            $this->img_link = 'https://www.facebook.com/groups/458151547721494/permalink/568957933307521/?';
            $this->tpg_link = 'https://www.tpg.ch/fr/stadler-tango';
            $this->places_assises = 80;
            $this->places_debout = 308;
            $this->places_totales = 388;
            $this->year = 2016;

        } elseif (1801 <= $id && $id <= 1833) { // Tango

            $this->type = "Tramway";
            $this->name = "Stadler Tango";
            $this->icon = 'tango';
            $this->img = 'tango';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kxjWWK';
            $this->tpg_link = 'https://www.tpg.ch/fr/stadler-tango';
            $this->places_assises = 80;
            $this->places_debout = 308;
            $this->places_totales = 388;
            $this->year = "2011-2016";

        } elseif (861 <= $id && $id <= 899) { // Cityrunner

            $this->type = "Tramway";
            $this->name = "Bombardier Cityrunner";
            $this->icon = 'cityrunner';
            $this->img = 'cityrunner';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/ebCHJv';
            $this->tpg_link = 'https://www.tpg.ch/fr/bombardier-cityrunner';
            $this->places_assises = 66;
            $this->places_debout = 300;
            $this->places_totales = 366;
            $this->year = "2004-2010";

        } elseif (801 <= $id && $id <= 852) { // DAV

            $this->type = "Tramway";
            $this->name = "Duewag-Vevey (DAV)";
            $this->icon = 'dav';
            $this->img = 'dav';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/ebCHJv';
            $this->tpg_link = 'https://www.tpg.ch/fr/bombardier-cityrunner';
            $this->places_assises = 66;
            $this->places_debout = 300;
            $this->places_totales = 366;
            $this->year = "1987-1989";

        /* SOUS-TRAITANT */

        /* GLOBE LIMOUSINES */

        } elseif (471 <= $id && $id <= 472) { // Minibus électriques ligne 36

            $this->type = "Minibus électrique";
            $this->name = "BredaMenarinibus Zeus";
            $this->icon = 'notfound';
            $this->img = 'minibus36';
            $this->year = 2014;

        } elseif (1901 <= $id && $id <= 1911) { // MAN solo Globe

            $this->type = "Autobus solo";
            $this->name = "MAN Lion's City";
            $this->icon = 'lionscity';
            $this->img = 'mansolo';
            $this->year = 2011;

        } elseif ($id == 1938) { // MAN solo Globe 2014

            $this->type = "Autobus solo";
            $this->name = "MAN Lion's City";
            $this->icon = 'lionscity';
            $this->img = 'mansolo';
            $this->year = 2014;

        } elseif (1959 <= $id && $id <= 1961) { // MAN solo

            $this->type = "Autobus solo";
            $this->name = "MAN Lion's City";
            $this->icon = 'lionscity';
            $this->img = 'mansolo';
            $this->year = 2015;

        } elseif ($id == 1927) { // MAN solo

            $this->type = "Autobus solo";
            $this->name = "MAN Lion's City";
            $this->icon = 'lionscity';
            $this->img = 'mansolo';

        } elseif ($id == 1962) { // MAN midibus

            $this->type = "Midibus";
            $this->name = "MAN Lion's City M";
            $this->icon = 'lionscity';
            $this->img = 'midibus';
            $this->year = 2015;

        } elseif ((481 <= $id && $id <= 499) || (901 <= $id && $id <= 903)) { // MAN NL 283

            $this->type = "Autobus solo";
            $this->name = "MAN NL 283";
            $this->icon = 'lionscity';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';
            $this->img = 'nl283';
            $this->year = 2004;

        } elseif (1951 <= $id && $id <= 1958) { // MAN articulé

            $this->type = "Autobus articulé";
            $this->name = "MAN Lion's City G";
            $this->icon = 'lionscity';
            $this->img = 'man';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/xyZgwd';
            $this->year = 2015;
            $this->places_assises = 44;
            $this->places_debout = 85;
            $this->places_totales = 129;

        } elseif (1967 <= $id && $id <= 1968) { // Nouveaux Citaro C2

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2';
            $this->img = 'citaroc2articule';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif (2000 <= $id && $id <= 2002) { // Citaro C2 Hybride

            $this->type = "Autobus hybride";
            $this->name = "Mercedes-Benz Citaro C2";
            $this->icon = 'c2';
            $this->img = 'hybride';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';

        } elseif (2003 <= $id && $id <= 2010) { // Sprinter

            $this->type = "Minibus";
            $this->name = "Mercedes-Benz Sprinter City 65";
            $this->icon = 'notfound';
            $this->img = 'sprinter';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';

        } elseif (1701 <= $id && $id <= 1708) { // MAN articulé TAC (61)

            $this->type = "Autobus articulé";
            $this->name = "MAN Lion's City G";
            $this->icon = 'lionscity';
            $this->img = 'man61';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/fdTi22';
            $this->places_assises = 36;
            $this->places_debout = 140;
            $this->places_totales = 176;

        } elseif (940 <= $id && $id <= 946) { // Citaro midibus

            $this->type = "Midibus";
            $this->name = "Mercedes-Benz Citaro O530K";
            $this->icon = 'citaro';
            $this->img = 'citarosolo';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/f1vHBg';

        } elseif (938 <= $id && $id <= 393) { // Volvo/HESS

            $this->type = "Autobus";
            $this->name = "Volvo/HESS B7L";
            $this->icon = 'hess';
            $this->img = 'volvohess';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/js5FNH';

        } elseif (1949 == $id) { // Midibus ex-TL

            $this->type = "Midibus";
            $this->name = "MAN NM223";
            $this->icon = 'lionscity';
            $this->img = '1949';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';
            $this->year = 2005;

        } elseif (1950 == $id) { // Citaro 1 (Ligne S)

            $this->type = "Autobus";
            $this->name = "Mercedes-Benz Citaro I O530";
            $this->icon = 'citaro';
            $this->img = '1950';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif (1947 == $id) { // Citaro 1 (Ligne S)

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro I O530G";
            $this->icon = 'citaro';
            $this->img = '1947';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif (1963 <= $id && $id <= 1964) { // Citaro C2 Globe

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2';
            $this->img = 'citaroc2articule';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        /* GEM'BUS */

        } elseif ($id == 1919) { // Citaro solo GEM’BUS

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro II O530";
            $this->icon = 'citaro';
            $this->img = '1919';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';

        } elseif (971 <= $id && $id <= 979) { // Citaro articulé GEM'BUS

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro II O530G";
            $this->icon = 'citaro';
            $this->img = 'citaro';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kZ14Eq';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530g';
            $this->places_assises = 46;
            $this->places_debout = 103;
            $this->places_totales = 149;
            $this->year = "2010";

        } elseif (1984 <= $id && $id <= 1990) { // Citaro C2 Gem’Bus

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2';
            $this->img = 'citaroc2articule';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif (2026 <= $id && $id <= 2027) { // Citaro C2K midibus

            $this->type = "Midibus";
            $this->name = "Mercedes-Benz Citaro C2 O530K";
            $this->icon = 'c2';
            $this->img = 'c2k';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';

        /* Genève-Tours */

        } elseif (925 <= $id && $id <= 927) { // MIDIBUS CITARO GETOURS

            $this->type = "Midibus";
            $this->name = "Mercedes-Benz Citaro O530K";
            $this->icon = 'citaro';
            $this->img = 'citarosolo';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/f1vHBg';

        } elseif ($id == 929) { // CITARO ARTICULE GETOURS

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro O530G";
            $this->icon = 'citaro';
            $this->img = 'citaro';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kZ14Eq';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530g';
            $this->places_assises = 46;
            $this->places_debout = 103;
            $this->places_totales = 149;

        } elseif (981 <= $id && $id <= 987) { // Citaro solo GeTours

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro O530";
            $this->icon = 'citaro';
            $this->img = 'citarosolo';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/f1vHBg';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530';
            $this->places_assises = 32;
            $this->places_debout = 55;
            $this->places_totales = 87;

        } elseif (1925 <= $id && $id <= 1926) { // Nouveaux Citaro articulé GeTours

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro O530G";
            $this->icon = 'citaro';
            $this->img = 'citaro';
            $this->img_author = 'André Knoerr';
            $this->img_link = 'https://flic.kr/p/kZ14Eq';
            $this->tpg_link = 'https://www.tpg.ch/fr/autobus-evobus-0530g';
            $this->places_assises = 46;
            $this->places_debout = 103;
            $this->places_totales = 149;

        } elseif ($id == 1939) { // CITARO ARTICULE GETOURS

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2';
            $this->img = 'citaroc2articule';
            $this->img_author = 'Rémi Chauvet';
            $this->img_link = 'https://www.facebook.com/tpg979';

        } elseif (2011 <= $id && $id <= 2012) { // Nouveaux Citaro C2

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2';
            $this->img = 'citaroc2articule';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';
            $this->year = '2018';

        } elseif ($id == 486) { // Autobus Vanhool (ligne 32)

            $this->type = "Autobus solo";
            $this->name = "VanHool New A330";
            $this->icon = 'citaro';
            $this->img = '486';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif (($id == 1965) || (1970 <= $id && $id <= 1972)) { // Minibus GLOBE

            $this->type = "Minibus";
            $this->name = "Mercedes-Benz Sprinter City 515TDCI";
            $this->icon = 'notfound';
            $this->img = '1965';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        } elseif ($id == 1966 || $id == 1969 || (1973 <= $id && $id <= 1983)) { // Citaro C2 solo Globe

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = 'c2';
            $this->img = '1966';
            $this->img_author = 'FDTPG';
            $this->img_link = 'https://www.facebook.com/FanDeTransportsPublicsGenevois/';

        /* REGIE DEPARTEMENTALE DES TRANSPORTS DE L'AIN (RDTA) */

        } elseif (950 <= $id && $id <= 960) { // Citaro articulé

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro II O530G";
            $this->icon = 'citaro';
            $this->img = 'citarordta';
            $this->img_author = 'Sin-Aly Sangare';
            $this->img_link = 'https://www.facebook.com/photo.php?fbid=505939609784386&set=pcb.505941263117554&type=3&theater';
            $this->year = "2010";

        } elseif (961 <= $id && $id <= 967) { // Citaro solo RDTA

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro II O530";
            $this->icon = 'citaro';
            $this->img = 'citarosolordta';
            $this->img_author = 'Deyan Marinov – GéNav';
            $this->img_link = 'https://twitter.com/Ge_nav';
            $this->year = "2010";

        } elseif (1920 <= $id && $id <= 1924) { // Citaro C2 solo RDTA

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = 'c2';
            $this->img = 'c2rdta';
            $this->img_author = 'Deyan Marinov – GéNav';
            $this->img_link = 'https://twitter.com/Ge_nav';
            $this->year = '2012';

        } elseif (2013 <= $id && $id <= 2017 || 2032 <= $id && $id <= 2034 || 2041 <= $id && $id <= 2043) { // Citaro C2 articulé RDTA €6

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2';
            $this->img = '2014';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';
            $this->year = '2018';

        } elseif (2018 <= $id && $id <= 2021 || 2029 <= $id && $id <= 2031)  { // Citaro C2 solo RDTA €6

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = 'c2';
            $this->img = '2020';
            $this->img_author = 'Deyan Marinov – GéNav';
            $this->img_link = 'https://twitter.com/Ge_nav';
            $this->year = '2018';

        /* ALSA */

        } elseif (2035 <= $id && $id <= 2040) { // Citaro C2 solo livrée 2013

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = 'c2-new';
            $this->img = 'c2-new';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';
            $this->year = '2019';

        /* New C2 */

        } elseif (2054 <= $id && $id <= 2055) { // Citaro C2 solo livrée 2013 (orange)

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = 'c2-bump-new';
            $this->img = '2054';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';
            $this->year = '2019';

        } elseif (2066 <= $id && $id <= 2068) {

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = '2066';
            $this->img = '2066';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';
            $this->year = '2019';

        } elseif (2050 <= $id && $id <= 2051) { // Citaro C2 solo livrée 2013 (orange)

            $this->type = "Autobus solo";
            $this->name = "Mercedes-Benz Citaro C2 O530";
            $this->icon = 'c2-bump-new';
            $this->img = '2050';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';
            $this->year = '2019';

        } elseif (2052 <= $id && $id <= 2053) {

            $this->type = "Autobus articulé";
            $this->name = "Mercedes-Benz Citaro C2 O530G";
            $this->icon = 'c2-bump-new';
            $this->img = '2053';
            $this->img_author = 'TramBusAl';
            $this->img_link = 'https://twitter.com/trambusalOff';
            $this->year = '2019';

        /* SOUS-TRAITANT AUTRES */

        } elseif ((900 <= $id && $id <= 999) || (1900 <= $id && $id <= 2099)) {

            $this->type = "Autobus";
	        $this->name = "Sous-traitant";
	        $this->enSavoirPlus = false;
            $this->icon = 'soustraitant';

        } elseif ('???' == $id) {

            $this->type = "Véhicule inconnu";
	        $this->name = "Sous-traitant";
	        $this->enSavoirPlus = false;
            $this->icon = 'soustraitant';

        } else {

            $this->type = "";
	        $this->name = "Véhicule inconnu";
	        $this->enSavoirPlus = false;
            $this->icon = 'notfound';
        }
    }

    /* Afficher le véhicule */
    public function renderCard_iOS() {
        if ($this->enSavoirPlus) {
            echo '<a href="/vehicule?id='.$this->id.'">';
        }
            ?>
            <div class="card card-vehicule">
                <div class="card-content">
                    <img class="vehicle-icon" src="https://tpgdata.nicolapps.ch/vehicules/img/icons/<?=$this->icon?>.png" alt="<?=$this->name?>">
                    <?php if ($this->wifi) { ?>
                        <img class="wifi-icon" src="/resources/img/wifi.svg" alt="Wi-Fi gratuit">
                    <?php } ?>

                    <span class="id">N° <?=$this->id?></span>

                    <h2>
                        <?=$this->type?>
                        <strong><?=$this->name?></strong>
                    </h2>
                    <?php
                        if ($this->enSavoirPlus) {
                            echo '<span class="en-savoir-plus">En savoir plus</span>';
                        }
                    ?>
                </div>
            </div>
        <?php
        if ($this->enSavoirPlus) {
            echo '</a>';
        }
    }

    public function renderCard_Android() {
        if ($this->enSavoirPlus) {
            echo '<a href="/vehicule?id='.$this->id.'">';
        }
            ?>
            <div class="card card-vehicule">
                <div class="card-content">
                    <img class="vehicle-icon" src="https://tpgdata.nicolapps.ch/vehicules/img/icons/<?=$this->icon?>.png" alt="<?=$this->name?>">
                    <?php if ($this->wifi) { ?>
                        <img class="wifi-icon" src="/resources/img/wifi.svg" alt="Wi-Fi gratuit">
                    <?php } ?>

                    <span class="id">N° <?=$this->id?></span>

                    <h2>
                        <?=$this->type?>
                        <strong><?=$this->name?></strong>
                    </h2>
                    <?php
                        if ($this->enSavoirPlus) {
                            echo '<span class="button">En savoir plus</span>';
                        }
                    ?>
                </div>
            </div>
            <?php
        if ($this->enSavoirPlus) {
            echo '</a>';
        }
    }
}
