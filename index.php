<?php

use App\Model\Center;

require_once __DIR__ . '/bootstrap.php';

$width = 50;
$dimension = 4;
$map = array(array());
for ($i = 0; $i < $dimension; $i++) {
    for ($j = 0; $j < $dimension; $j++) {
        $map[$i][$j] = new Center($i * $width + $width / 2, $j * $width + $width / 2);
    }
}

for ($i = 0; $i < $dimension; $i++) {
    for ($j = 0; $j < $dimension; $j++) {
        if ($i-1>=0) $map[$i][$j]->addNeighbor($map[$i-1][$j]);
        if ($i+1<$dimension) $map[$i][$j]->addNeighbor($map[$i+1][$j]);
        if ($j-1>=0) $map[$i][$j]->addNeighbor($map[$i][$j-1]);
        if ($j+1<$dimension) $map[$i][$j]->addNeighbor($map[$i][$j+1]);
    }
}

// Don't try to print or dump map => infinite loop