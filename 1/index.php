<?php

$input = file_get_contents('./input.txt');
$lines = array_filter(explode("\n", $input));

// Split the lines into two lists
$teamA = $teamB = [];
foreach ($lines as $line) {
    [$teamALine, $teamBLine] = preg_split('/\s+/', $line);
    $teamA[] = $teamALine;
    $teamB[] = $teamBLine;
}

// Sort them, duh
sort($teamA);
sort($teamB);

// Find the difference between each item
$totalDistance = 0;
for ($i = 0; $i < count($teamA); $i++) {
    $totalDistance += abs($teamB[$i] - $teamA[$i]);
}

echo "<strong>List distance:</strong> " . $totalDistance . '<br />';

// Get number of times each value appears in list B
$counts = array_count_values($teamB);

// Multiply list A values by number of times it appears in list B
$similarityScore = 0;
foreach ($teamA as $id) {
    $similarityScore += $id * ($counts[$id] ?? 0);
}

echo "<strong>Similarity score:</strong> " . $similarityScore;
