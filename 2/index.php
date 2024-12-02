<?php

$input = file_get_contents('./input.txt');

$reports = array_filter(explode("\n", $input));

function countSafeReports(array $reports) {
    $safe = 0;
    foreach ($reports as $report) {
        $levels = explode(" ", $report);
        if (isReportSafe($levels)) {
            $safe++;
        }
    }

    return $safe;
}

function countSafeReportsWithProblemDampener(array $reports) {
    $safe = 0;
    foreach ($reports as $report) {
        $levels = explode(" ", $report);
        for ($i = -1; $i < count($levels); $i++) {
            $adjustedLevels = $levels;
            if ($i > -1) {
                unset($adjustedLevels[$i]);
                $adjustedLevels = array_values($adjustedLevels);
            }
            if (isReportSafe($adjustedLevels)) {
                $safe++;
                break;
            }
        }
    }

    return $safe;
}

function isReportSafe(array $levels): bool
{
    foreach ($levels as $index => $level) {
        $prev = $levels[$index - 1] ?? null;
        $next = $levels[$index + 1] ?? null;
        if (!$prev || !$next) {
            continue;
        }

        if (
            ($prev > $level && $next > $level)
            || ($prev < $level && $next < $level)
        ) {
            return false;
        }

        $diffToPrev = abs((int)$level - (int)$prev);
        $diffToNext = abs((int)$level - (int)$next);
        if ($diffToPrev < 1 || $diffToPrev > 3 || $diffToNext < 1 || $diffToNext > 3) {
            return false;
        }
    }

    return true;
}

echo "<strong>Safe reports:</strong> " . countSafeReports($reports);

echo "<br /><strong>Safe reports with problem dampener:</strong>" . countSafeReportsWithProblemDampener($reports);
