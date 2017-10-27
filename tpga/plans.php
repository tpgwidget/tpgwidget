<?php

require_once('../tpgdata/plans/plans.php');

?>
<div data-page="plans" class="page plans">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="#" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Plans</div>
        </div>
    </div>

    <div class="page-content">

        <?php foreach(Plans::all() as $yearNumber => $year) { ?>

            <div class="content-block-title"><?= $year["name"] ?></div>

            <?php foreach($year["plans"] as $id => $plan) { ?>

                <a href="https://tpgdata.nicolapps.ch/plans/pdf/<?= $yearNumber ?>/<?= $id ?>" class="external plan">
                    <div class="plan-bg" style="background-image:url(https://tpgdata.nicolapps.ch/plans/icons/<?= $id ?>)">
                        <div class="plan-desc">
                            <h3><?= $plan["name"] ?></h3>
                        </div>
                    </div>
                </a>
            <?php } ?>
        <?php } ?>
    </div>
</div>
