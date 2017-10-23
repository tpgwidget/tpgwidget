<?php

require_once('../tpgdata/plans/plans.php');

?>
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Retour</span></a></div>
    <div class="center sliding">Plans</div>
    <div class="right">
      <a href="#" class="open-panel link icon-only"><i class="icon icon-panel"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <div data-page="about" class="page">
    <div class="page-content">

        <?php foreach(Plans::all() as $yearNumber => $year) { ?>

            <div class="content-block-title"><?= $year["name"] ?></div>

            <div class="list-block media-list">
                <ul>
                    <?php foreach($year["plans"] as $id => $plan) { ?>

                        <li>
                            <a href="https://tpgdata.nicolapps.ch/plans/pdf/<?= $yearNumber ?>/<?= $id ?>" class="external item-link item-content">
                                <div class="item-media">
                                    <img src="https://tpgdata.nicolapps.ch/plans/icons/<?= $id ?>" alt="<?= $plan["name"] ?>" width="80">
                                </div>
                                <div class="item-inner">
                                    <div class="item-title-row">
                                        <div class="item-title"><?= $plan["name"] ?></div>
                                    </div>
                                    <div class="item-text"><?= $plan['desc'] ?></div>
                                </div>
                            </a>
                        </li>

                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <!--<div class="content-block-title">Plans valables jusqu'au 10 décembre 2016</div>
        <div class="list-block media-list">
            <ul>
                <li>
                    <a href="http://cdn.nicolapps.ch/plans/urbain2016.pdf" class="item-link item-content external">
                        <div class="item-media"><img src="/resources/img/plans/urbain.png" width="80">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">Plan urbain</div>
                            </div>
                            <div class="item-text">Lignes de tram, de trolleybus et de bus urbain</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="http://cdn.nicolapps.ch/plans/regional2016.pdf" class="item-link item-content external">
                        <div class="item-media"><img src="/resources/img/plans/peripherique.png" width="80">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">Plan régional</div>
                            </div>
                            <div class="item-text">Lignes régionales et transfrontalières</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="http://cdn.nicolapps.ch/plans/noctambusregional2016.pdf" class="item-link item-content external">
                        <div class="item-media"><img src="/resources/img/plans/noctambus.png" width="80">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">Plan Noctambus régional</div>
                            </div>
                            <div class="item-text">Lignes Noctambus régionales (vendredi et samedi soir)</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="http://cdn.nicolapps.ch/plans/noctambusurbain2016.pdf" class="item-link item-content external">
                        <div class="item-media"><img src="/resources/img/plans/noctambusurbain.png" width="80">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">Plan Noctambus urbain</div>
                            </div>
                            <div class="item-text">Lignes Noctambus urbaines (vendredi et samedi soir)</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="http://cdn.nicolapps.ch/plans/zonestarifaires2016.pdf" class="item-link item-content external">
                        <div class="item-media"><img src="/resources/img/plans/zones.jpg" width="80">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">Plan des zones tarifaires</div>
                            </div>
                            <div class="item-text">Toutes les zones tarifaires unireso</div>
                        </div>
                    </a>
                </li>
            </ul>
</div>-->
    </div>
  </div>
</div>
