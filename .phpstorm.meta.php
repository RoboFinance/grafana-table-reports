<?php

namespace PHPSTORM_META {
    expectedArguments(\Robocash\GrafanaTableReporter\GrafanaTableReporter::getData(), 4, 'csv', 'raw');
    expectedArguments(\Robocash\GrafanaTableReporter\GrafanaTableReporter::getData(), 0, now()->subDays(30));
    expectedArguments(\Robocash\GrafanaTableReporter\GrafanaTableReporter::getData(), 1, now());
}
