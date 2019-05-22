<?php

namespace PHPSTORM_META {


    use Robocash\GrafanaTableReporter\GrafanaTableReporter;

    expectedArguments(GrafanaTableReporter::getData(), 4, 'csv', 'raw');


    expectedArguments(GrafanaTableReporter::getData(), 0, now()->subDays(30));
    expectedArguments(GrafanaTableReporter::getData(), 1, now());
}

