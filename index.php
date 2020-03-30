<?php

use App\Model\Center;
use App\Model\Corner;

require_once __DIR__ . '/bootstrap.php';

echo "[STATUS]: Program starts\n";
$width = 50;
$dimension = 4;

$map = [
    "centers" => array(array()),
    "corners" => array(array()),
    "edges"   => array(array())
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
echo "Corners around Center(0,0) are: ";
foreach ($map["centers"][0][0]->getCorners() as $corner){
    echo $corner;
}

echo "\n";
echo "Centers around Corner(0,0) are: ";
foreach ($map["corners"][0][0]->getTouches() as $center){
    echo $center;
}

echo "\n";
echo "Centers around Corner(0,1) are: ";
foreach ($map["corners"][0][1]->getTouches() as $center){
    echo $center;
}

echo "\n";
echo "Centers around Corner(1,1) are: ";
foreach ($map["corners"][1][1]->getTouches() as $center){
    echo $center;
}

// TODO: Corner Adjacent

// TODO: Edge Creation & Center.borders & Corner.protrudes