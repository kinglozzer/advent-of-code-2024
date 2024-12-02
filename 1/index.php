<?php

$input = file_get_contents('./input.txt');
$lines = array_filter(explode("\n", $input));

$teamA = $teamB = [];
foreach ($lines as $line) {
    [$teamALine, $teamBLine] = preg_split('/\s+/', $line);
    $teamA[] = $teamALine;
    $teamB[] = $teamBLine;
}

sort($teamA);
sort($teamB);

$totalDistance = 0;
for ($i = 0; $i < count($teamA); $i++) {
    $totalDistance += abs($teamB[$i] - $teamA[$i]);
}

echo "<strong>List distance:</strong> " . $totalDistance . '<br />';

$counts = array_count_values($teamB);

$similarityScore = 0;
foreach ($teamA as $id) {
    $similarityScore += $id * ($counts[$id] ?? 0);
}

echo "<strong>Similarity score:</strong> " . $similarityScore;
