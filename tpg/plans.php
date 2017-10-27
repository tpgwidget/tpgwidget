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
    </div>
  </div>
</div>
