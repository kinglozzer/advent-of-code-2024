<?php

$input = file_get_contents('./input.txt');

$sections = explode("\n\n", $input);

// Explode the rules here because we don't want to have to do it over and over when looping them later
$pageOrderingRules = [];
foreach (array_filter(explode("\n", $sections[0])) as $rule) {
    [$before, $after] = explode("|", $rule);
    $pageOrderingRules[] = [
        (int)$before,
        (int)$after
    ];
}

$updatePageNumbers = array_filter(explode("\n", $sections[1]));

// Check the given number combo against every rule
function isValidOrder(int $before, int $after, $pageOrderingRules)
{
    foreach ($pageOrderingRules as $rule) {
        [$ruleBefore, $ruleAfter] = $rule;
        // If there's a rule that explicitly says these two are the wrong way round, then fail
        if ($before === $ruleAfter && $after === $ruleBefore) {
            return false;
        }
    }
    // Otherwise, just assume all is fine
    return true;
}

$pageUpdatesInCorrectOrder = [];
foreach ($updatePageNumbers as $pageNumberList) {
    $pageNumbers = explode(",", $pageNumberList);

    for ($i = 0; $i < count($pageNumbers); $i++) {
        $before = $pageNumbers[$i];

        // Check this number when paired with every number after this one
        // I'm not sure if I actually need to do this... but never mind
        for ($j = $i + 1; $j < count($pageNumbers); $j++) {
            $after = $pageNumbers[$j];
            if (!isValidOrder($before, $after, $pageOrderingRules)) {
                continue 3;
            }
        }
    }

    $pageUpdatesInCorrectOrder[] = $pageNumberList;
}

$total = 0;
foreach ($pageUpdatesInCorrectOrder as $pageNumberList) {
    $pageNumbers = explode(",", $pageNumberList);
    $mid = intval(count($pageNumbers) / 2);
    $total += $pageNumbers[$mid] ?? 0;
}

echo "<strong>Total:</strong> $total<br />";

$pageUpdatesInIncorrectOrder = [];
foreach ($updatePageNumbers as $pageNumberList) {
    $isInvalid = false;
    $pageNumbers = explode(",", $pageNumberList);

    for ($i = 0; $i < count($pageNumbers); $i++) {
        $before = $pageNumbers[$i];
        $after = $pageNumbers[$i + 1] ?? null;

        if (!$after) {
            break;
        }

        // We don't want to do a nested loop like before, because we only want to swap one at a time, then re-check
        if (!isValidOrder($before, $after, $pageOrderingRules)) {
            $isInvalid = true;
            $pageNumbers[$i] = $after;
            $pageNumbers[$i + 1] = $before;
            $pageNumberList = implode(",", $pageNumbers);

            $i = -1; // Go back to the start of the loop
        }
    }

    if ($isInvalid) {
        $pageUpdatesInIncorrectOrder[] = $pageNumberList;
    }
}

$newTotal = 0;
foreach ($pageUpdatesInIncorrectOrder as $pageNumberList) {
    $pageNumbers = explode(",", $pageNumberList);
    $mid = intval(count($pageNumbers) / 2);
    $newTotal += $pageNumbers[$mid] ?? 0;
}

echo "<strong>Total after re-ordering:</strong> $newTotal<br />";
