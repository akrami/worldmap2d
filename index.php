<?php

use App\Model\Center;
use App\Model\Corner;
use App\Model\Edge;

require_once __DIR__ . '/bootstrap.php';

echo "[STATUS]: Program starts\n";
$width = 50;
$dimension = 4;

$map = [
    "centers" => array(array()),
    "corners" => array(array()),
    "edges"   => [
        "v" => array(array()),
        "h" => array(array())
    ]
];

// Center Creation
for ($i = 0; $i < $dimension; $i++) {
    for ($j = 0; $j < $dimension; $j++) {
        $map["centers"][$i][$j] = new Center($i * $width + $width / 2, $j * $width + $width / 2);
    }
}

echo "[STATUS]: Center creation finished\n";

// Center Neighbors
for ($i = 0; $i < $dimension; $i++) {
    for ($j = 0; $j < $dimension; $j++) {
        if ($i-1>=0) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i-1][$j]);
        if ($i+1<$dimension) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i+1][$j]);
        if ($j-1>=0) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i][$j-1]);
        if ($j+1<$dimension) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i][$j+1]);
    }
}

echo "[STATUS]: Center neighbors connected\n";

// Corner Creation & Corner.touches & Center.corners
for ($i = 0; $i <= $dimension; $i++){
    for ($j = 0; $j <= $dimension; $j++){
        $map["corners"][$i][$j] = new Corner($i*$width,$j*$width);

        if ($i!=0 and $j!=0) {
            $map["corners"][$i][$j]->addTouches($map["centers"][$i-1][$j-1]);
            $map["centers"][$i-1][$j-1]->addCorner($map["corners"][$i][$j]);
        }
        if ($i!=0 and $j!=$dimension) {
            $map["corners"][$i][$j]->addTouches($map["centers"][$i-1][$j]);
            $map["centers"][$i-1][$j]->addCorner($map["corners"][$i][$j]);
        }
        if ($i!=$dimension and $j!=0) {
            $map["corners"][$i][$j]->addTouches($map["centers"][$i][$j-1]);
            $map["centers"][$i][$j-1]->addCorner($map["corners"][$i][$j]);
        }
        if ($i!=$dimension and $j!=$dimension) {
            $map["corners"][$i][$j]->addTouches($map["centers"][$i][$j]);
            $map["centers"][$i][$j]->addCorner($map["corners"][$i][$j]);
        }
    }
}

echo "[STATUS]: Corner creation finished\n";

// Corner Adjacent
for ($i = 0; $i <= $dimension; $i++) {
    for ($j = 0; $j <= $dimension; $j++) {
        if ($i>0) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i-1][$j]);
        if ($i<$dimension) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i+1][$j]);
        if ($j>0) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i][$j-1]);
        if ($j<$dimension) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i][$j+1]);
    }
}

echo "[STATUS]: Corner Adjacent finished\n";

// TODO: Edge Creation & Center.borders & Corner.protrudes
for ($i = 0; $i < $dimension; $i++) {
    for ($j = 0; $j < $dimension; $j++) {
        if ($j<$dimension-1) {
            $map["edges"]["v"][$i][$j] = new Edge($map["centers"][$i][$j],
                                                  $map["centers"][$i][$j+1],
                                                  $map["corners"][$i][$j+1],
                                                  $map["corners"][$i+1][$j+1]);
            $map["centers"][$i][$j]->addBorder($map["edges"]["v"][$i][$j]);
            $map["centers"][$i][$j+1]->addBorder($map["edges"]["v"][$i][$j]);
            $map["corners"][$i][$j+1]->addProtrude($map["edges"]["v"][$i][$j]);
            $map["corners"][$i+1][$j+1]->addProtrude($map["edges"]["v"][$i][$j]);
        }
        if ($i<$dimension-1) {
            $map["edges"]["h"][$i][$j] = new Edge($map["centers"][$i][$j],
                                                  $map["centers"][$i+1][$j],
                                                  $map["corners"][$i+1][$j],
                                                  $map["corners"][$i+1][$j+1]);
            $map["centers"][$i][$j]->addBorder($map["edges"]["h"][$i][$j]);
            $map["centers"][$i+1][$j]->addBorder($map["edges"]["h"][$i][$j]);
            $map["corners"][$i+1][$j]->addProtrude($map["edges"]["h"][$i][$j]);
            $map["corners"][$i+1][$j+1]->addProtrude($map["edges"]["h"][$i][$j]);
        }
    }
}

echo "[STATUS]: Edge creation finished\n";