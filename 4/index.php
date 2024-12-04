<?php

$input = file_get_contents('./input.txt');

$lines = [];
foreach (array_filter(explode("\n", $input)) as $line) {
    $lines[] = str_split($line);
}

$lineCount = count($lines);
$lineLength = count($lines[0]);

$columns = [];
foreach ($lines as $line) {
    foreach ($line as $i => $letter) {
        $columns[$i][] = $letter;
    }
}

$diagonals = [];
for ($startingLineNumber = 0; $startingLineNumber < $lineCount; $startingLineNumber++) {
    $maxStartingColumn = $startingLineNumber === 0 ? $lineLength : 1;
    for ($startingColumnNumber = 0; $startingColumnNumber < $maxStartingColumn; $startingColumnNumber++) {
        $diagonal = [];
        $i = 0;
        while (isset($lines[$startingLineNumber + $i][$startingColumnNumber + $i])) {
            $diagonal[] = $lines[$startingLineNumber + $i][$startingColumnNumber + $i];
            $i++;
        }
        $diagonals[] = $diagonal;
    }
}

$reverseDiagonals = [];
for ($startingLineNumber = $lineCount - 1; $startingLineNumber >= 0; $startingLineNumber--) {
    $startingColumn = $startingLineNumber === ($lineCount - 1) ? $lineLength - 1 : 0;
    for ($startingColumnNumber = $startingColumn; $startingColumnNumber >= 0; $startingColumnNumber--) {
        $diagonal = [];
        $i = 0;
        while (isset($lines[$startingLineNumber - $i][$startingColumnNumber + $i])) {
            $diagonal[] = $lines[$startingLineNumber - $i][$startingColumnNumber + $i];
            $i++;
        }
        $reverseDiagonals[] = $diagonal;
    }
}

$xmasesFound = 0;
foreach ([$lines, $columns, $diagonals, $reverseDiagonals] as $dataset) {
    foreach ($dataset as $arrayOfChars) {
        $string = implode('', $arrayOfChars);
        if (strlen($string) < 4) {
            continue;
        }

        $xmasesFound += substr_count($string, 'XMAS');
        $xmasesFound += substr_count($string, 'SAMX');
    }
}

echo "<strong>XMASes found:</strong> $xmasesFound<br />";

// Disgusting

$x_masesFound = 0;
foreach ($lines as $i => $line) {
    if ($i === 0 || $i === $lineCount - 1) {
        continue; // An 'A' here can't be in the middle of a X-MAS
    }

    foreach ($line as $j => $letter) {
        if ($j === 0 || $j === $lineLength - 1) {
            continue; // An 'A' here can't be in the middle of a X-MAS
        }
        if ($letter !== 'A') {
            continue;
        }

        if (
            // Check diagonally NW->SE
            (
                (($lines[$i-1][$j-1] ?? '') === 'M' && ($lines[$i+1][$j+1] ?? '') === 'S')
                || (($lines[$i-1][$j-1] ?? '') === 'S' && ($lines[$i+1][$j+1] ?? '') === 'M')
            )
            &&
            // Check diagonally SW->NE
            (
                (($lines[$i+1][$j-1] ?? '') === 'M' && ($lines[$i-1][$j+1] ?? '') === 'S')
                || (($lines[$i+1][$j-1] ?? '') === 'S' && ($lines[$i-1][$j+1] ?? '') === 'M')
            )
        ) {
            $x_masesFound++;
        }
    }
}

echo "<strong>X-MASes found:</strong> $x_masesFound<br />";
