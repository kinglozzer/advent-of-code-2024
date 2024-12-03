<?php

$input = file_get_contents('./input.txt');

preg_match_all('/mul\((\d+),(\d+)\)/', $input, $matches);

// Grab the numbers, smash 'em together, done
$total = 0;
foreach ($matches[0] as $i => $match) {
    $total += $matches[1][$i] * $matches[2][$i];
}

echo "<strong>Multiplication total:</strong> " . $total;

preg_match_all('/mul\((\d+),(\d+)\)/', $input, $matches, PREG_OFFSET_CAPTURE);
preg_match_all('/do(n\'t)?\(\)/', $input, $doDontMatches, PREG_OFFSET_CAPTURE);

// Find all multiplications and do()/don't(), store them in an instructions array
// but use their *positions in the string* as keys, so then we can sort them
$instructions = [];
foreach ($matches[0] as $i => $match) {
    $instructions[$match[1]] = $matches[1][$i][0] * $matches[2][$i][0];
}
foreach ($doDontMatches[0] as $match) {
    $instructions[$match[1]] = $match[0];
}

// You won't believe what happens next
ksort($instructions);

// Loop over the instructions. It's probably possible to not have this stupid boolean
// flag and instead slice & splice the arrays, but unfortunately I don't care
$total = 0;
$do = true;
foreach ($instructions as $instruction) {
    if ($instruction === 'do()') {
        $do = true;
        continue;
    } else if ($instruction === "don't()") {
        $do = false;
        continue;
    }

    if (!$do) {
        continue;
    }

    $total += $instruction;
}

echo "<br /><strong>Multiplication total with do/don’t:</strong> " . $total;
