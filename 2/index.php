<?php

$input = file_get_contents('./input.txt');

$reports = array_filter(explode("\n", $input));

function countSafeReports(array $reports) {
    $safe = 0;
    foreach ($reports as $report) {
        $levels = explode(" ", $report);
        // Ezpz, just count them
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
        // This is almost certainly the least efficient way to do this, but fuck it
        // Check the list itself (i = -1), then remove each item in turn and re-check the list
        for ($i = -1; $i < count($levels); $i++) {
            $adjustedLevels = $levels;
            if ($i > -1) {
                unset($adjustedLevels[$i]);
                // array_values, because otherwise lists go [0, 2, 3] and the next/prev logic
                // breaks and I cba to refactor it to use prev() and next()
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
        // If this is either the first or last item, we don't need to bother
        if (!$prev || !$next) {
            continue;
        }

        // If the direction we're heading changes, UH OH!
        if (
            ($prev > $level && $next > $level)
            || ($prev < $level && $next < $level)
        ) {
            return false;
        }

        // Check the diff isn't outside allowed amount of 1 to 3
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
