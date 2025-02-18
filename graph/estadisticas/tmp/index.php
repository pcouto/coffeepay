    <?php
    require_once "../koolreport/core/autoload.php";
    require_once "Report.php";

    $report = new Report;
    $report->run()->render(); ?>
