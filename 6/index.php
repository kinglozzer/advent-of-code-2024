<?php

$input = file_get_contents('./input.txt');

$grid = [];
// Build a grid of characters
foreach (array_filter(explode("\n", $input)) as $line) {
    foreach (str_split($line) as $x => $char) {
        $grid[$x][] = $char;
    }
}

$width = count($grid);
$height = count($grid[0]);

// Find the starting position of the caret
$currentX = $currentY = $direction = null;
foreach ($grid as $x => $column) {
    foreach ($column as $y => $char) {
        if (in_array($char, ['^', 'v', '<', '>'])) {
            $currentX = $x;
            $currentY = $y;
            $direction = $char;
            break 2;
        }
    }
}

if ($currentX === null || $currentY === null || $direction === null) {
    die('Something has gone catastrophically wrong');
}

do {
    $char = $grid[$currentX][$currentY] ?? null;

    if ($char === '#') {
        switch ($direction) {
            case '^':
                $currentY++;
                $direction = '>';
                break;
            case 'v':
                $currentY--;
                $direction = '<';
                break;
            case '>':
                $currentX--;
                $direction = 'v';
                break;
            case '<':
                $currentX++;
                $direction = '^';
                break;
        }
        continue;
    } else if ($char === '.' || in_array($char, ['^', 'v', '<', '>'])) {
        $grid[$currentX][$currentY] = 'X';
    }

    switch ($direction) {
        case '^':
            $currentY--;
            break;
        case 'v':
            $currentY++;
            break;
        case '>':
            $currentX++;
            break;
        case '<':
            $currentX--;
            break;
    }
} while ($currentX >= 0 && $currentX < $width && $currentY >= 0 && $currentY < $height);

// This will print the grid for debugging
//echo '<pre>';
//$inverseGrid = [];
//foreach ($grid as $x => $column) {
//    foreach ($column as $y => $char) {
//        $inverseGrid[$y][] = $char;
//    }
//}
//
//foreach ($inverseGrid as $y => $column) {
//    foreach ($column as $x => $char) {
//        echo $char;
//    }
//    echo "\n";
//}

$xCount = 0;
foreach ($grid as $x => $column) {
    foreach ($column as $y => $char) {
        if ($char === 'X') {
            $xCount++;
        }
    }
}

echo "<strong>Positions guard will visit:</strong> $xCount<br />";

// This holds all coordinates visited (except the first one) and the direction the guard was travelling in
// e.g. [^ => ['1,2', '4,5'], > => ['3,6', '7,8']]
$coordinatesTravelledTo = [];

// Do all this again, just to keep the two parts separate

$grid = [];
// Build a grid of characters
foreach (array_filter(explode("\n", $input)) as $line) {
    foreach (str_split($line) as $x => $char) {
        $grid[$x][] = $char;
    }
}

$width = count($grid);
$height = count($grid[0]);

// Find the starting position of the caret
$currentX = $currentY = $direction = null;
foreach ($grid as $x => $column) {
    foreach ($column as $y => $char) {
        if (in_array($char, ['^', 'v', '<', '>'])) {
            $currentX = $x;
            $currentY = $y;
            $direction = $char;
            break 2;
        }
    }
}

if ($currentX === null || $currentY === null || $direction === null) {
    die('Something has gone catastrophically wrong');
}

$count = 0;
$existingObstaclesHit = [];
$shouldRecordVisit = true;

$startingX = $currentX;
$startingY = $currentY;
$startingDirection = $direction;

// I gave up, so I'm brute forcing it
// This takes a while...

foreach ($grid as $x => $column) {
    foreach ($column as $y => $char) {
        if ($x === $startingX && $y === $startingY) {
            continue;
        }
        if ($char === '#') {
            continue;
        }

        $gridToTest = $grid;
        $gridToTest[$x][$y] = '#';
        $coordinatesTravelledTo = [];

        $currentX = $startingX;
        $currentY = $startingY;
        $direction = $startingDirection;

        do {
            $char = $gridToTest[$currentX][$currentY] ?? null;

            if ($char === '#') {
                switch ($direction) {
                    case '^':
                        $currentY++;
                        $direction = '>';
                        break;
                    case 'v':
                        $currentY--;
                        $direction = '<';
                        break;
                    case '>':
                        $currentX--;
                        $direction = 'v';
                        break;
                    case '<':
                        $currentX++;
                        $direction = '^';
                        break;
                }
                $shouldRecordVisit = false;
                continue;
            } else if (in_array($char, ['.', 'X', '^', 'v', '<', '>'])) {
                $gridToTest[$currentX][$currentY] = 'X';
                if ($shouldRecordVisit) {
                    if (in_array("{$currentX}, {$currentY}", $coordinatesTravelledTo[$direction] ?? [])) {
                        $count++;
                        break;
                    }
                    $coordinatesTravelledTo[$direction][] = "{$currentX}, {$currentY}";
                } else {
                    $shouldRecordVisit = true;
                }
            }

            switch ($direction) {
                case '^':
                    $currentY--;
                    break;
                case 'v':
                    $currentY++;
                    break;
                case '>':
                    $currentX++;
                    break;
                case '<':
                    $currentX--;
                    break;
            }
        } while ($currentX >= 0 && $currentX < $width && $currentY >= 0 && $currentY < $height);
    }
}

echo "<strong>Number of possible obstacle locations:</strong> $count<br />";
