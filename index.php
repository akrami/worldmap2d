<?php

use App\Model\Center;
use App\Model\Corner;
use App\Model\Edge;

require_once __DIR__ . '/bootstrap.php';

$width = 50;
$dimension = 40;

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

// Center Neighbors
for ($i = 0; $i < $dimension; $i++) {
    for ($j = 0; $j < $dimension; $j++) {
        if ($i-1>=0) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i-1][$j]);
        if ($i+1<$dimension) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i+1][$j]);
        if ($j-1>=0) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i][$j-1]);
        if ($j+1<$dimension) $map["centers"][$i][$j]->addNeighbor($map["centers"][$i][$j+1]);
    }
}

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

// Corner Adjacent
for ($i = 0; $i <= $dimension; $i++) {
    for ($j = 0; $j <= $dimension; $j++) {
        if ($i>0) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i-1][$j]);
        if ($i<$dimension) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i+1][$j]);
        if ($j>0) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i][$j-1]);
        if ($j<$dimension) $map["corners"][$i][$j]->addAdjacent($map["corners"][$i][$j+1]);
    }
}

// Edge Creation & Center.borders & Corner.protrudes
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

$moisture = array(array());

$SR = 0;                 // starting row index
$SC = 0;                 // starting column index
$ER = $dimension;        // ending row index
$EC = $dimension;        // ending column index

for ($i = 0; $i<$dimension; $i++) {
    $x = mt_rand(0, $dimension-1);
    $y = mt_rand(0, $dimension-1);
    $moisture[$x][$y]=100;
}

while ($SR < $ER && $SC < $EC) {
    for ($i = $SC; $i < $EC; ++$i) {
        $moisture[$SR][$i] = calculateNeighbors($moisture, $dimension, $SR, $i);
    }
    $SR++;

    for ($i = $SR; $i < $ER; ++$i) {
        $moisture[$i][$EC-1] = calculateNeighbors($moisture, $dimension, $i, $EC-1);
    }
    $EC--;

    if ($SR < $ER) {
        for ($i = $EC - 1; $i >= $SC; --$i) {
            $moisture[$ER-1][$i] = calculateNeighbors($moisture, $dimension, $ER-1, $i);
        }
        $ER--;
    }

    if ($SC < $EC) {
        for ($i = $ER - 1; $i >= $SR; --$i) {
            $moisture[$i][$SC] = calculateNeighbors($moisture, $dimension, $i, $SC);
        }
        $SC++;
    }
}

function calculateNeighbors($array , $n, $i, $j) {
    if ($i == 0 or $i == $n - 1 or $j == 0 or $j == $n - 1){
        return number_format(100,2);
    }else{
        $sum = 0;
        $count = 0;
        if (isset($array[$i-1][$j])){
            $sum += $array[$i-1][$j];
            $count ++;
        }
        if (isset($array[$i][$j-1])){
            $sum += $array[$i][$j-1];
            $count ++;
        }
        if (isset($array[$i+1][$j])){
            $sum += $array[$i+1][$j];
            $count ++;
        }
        if (isset($array[$i][$j+1])){
            $sum += $array[$i][$j+1];
            $count ++;
        }
        if (isset($array[$i+1][$j+1])){
            $sum += $array[$i+1][$j+1];
            $count ++;
        }
        if (isset($array[$i-1][$j+1])){
            $sum += $array[$i-1][$j+1];
            $count ++;
        }
        if (isset($array[$i+1][$j-1])){
            $sum += $array[$i+1][$j-1];
            $count ++;
        }
        if (isset($array[$i-1][$j-1])){
            $sum += $array[$i-1][$j-1];
            $count ++;
        }
        if ($count===0){
            return number_format(100,2);
        }else{
            return calculateMoisture($sum, $count);
        }
    }
}

function calculateMoisture(int $sum, int $count): float
{
    $random = log10(mt_rand(1,10));
    return number_format(($sum/$count)*$random, 2);
}

for ($i=0; $i<$dimension; $i++){
    for ($j=0; $j<$dimension; $j++){
        if ($moisture[$i][$j]>mt_rand(20,50)){
            $map["centers"][$i][$j]->setWater(true);
        }
    }
}

$output = array(array());
for ($i=0; $i<$dimension; $i++) {
    for ($j=0; $j<$dimension; $j++) {
        $type = $map["centers"][$i][$j]->isWater()?"W":"S";
        $output[$i*2][$j*2]     = $type;
        $output[$i*2+1][$j*2]   = $type;
        $output[$i*2][$j*2+1]   = $type;
        $output[$i*2+1][$j*2+1] = $type;
    }
}

for ($i=0; $i<$dimension*2; $i++) {
    for ($j = 0; $j < $dimension*2; $j++) {
        if ($i>0 and $j>0 and $output[$i][$j]=="S" and $output[$i-1][$j]=="S" and $output[$i][$j-1]=="S" and $output[$i-1][$j-1]=="W"){
            echo "<img src='Public/images/tiles/i3.png' />";
        }elseif($i<$dimension*2-1 and $j>0 and $output[$i][$j]=="S" and $output[$i+1][$j]=="S" and $output[$i][$j-1]=="S" and $output[$i+1][$j-1]=="W"){
            echo "<img src='Public/images/tiles/i2.png' />";
        }elseif ($i<$dimension*2-1 and $j<$dimension*2-1 and $output[$i][$j] == "S" and $output[$i + 1][$j] == "S" and $output[$i][$j + 1] == "S" and $output[$i+1][$j+1]=="W") {
            echo "<img src='Public/images/tiles/i1.png' />";
        }elseif ($i>0 and $j<$dimension*2-1 and $output[$i][$j] == "S" and $output[$i - 1][$j] == "S" and $output[$i][$j + 1] == "S" and $output[$i-1][$j+1]=="W") {
            echo "<img src='Public/images/tiles/i4.png' />";
        }elseif ($i > 0 and $j > 0 and $output[$i][$j] == "S" and $output[$i - 1][$j] == "W" and $output[$i][$j - 1] == "W") {
            echo "<img src='Public/images/tiles/o1.png' />";
        }elseif ($i<$dimension*2-1 and $j > 0 and $output[$i][$j] == "S" and $output[$i + 1][$j] == "W" and $output[$i][$j - 1] == "W") {
            echo "<img src='Public/images/tiles/o4.png' />";
        }elseif ($i<$dimension*2-1 and $j<$dimension*2-1 and $output[$i][$j] == "S" and $output[$i + 1][$j] == "W" and $output[$i][$j + 1] == "W") {
            echo "<img src='Public/images/tiles/o3.png' />";
        }elseif ($i>0 and $j<$dimension*2-1 and $output[$i][$j] == "S" and $output[$i - 1][$j] == "W" and $output[$i][$j + 1] == "W") {
            echo "<img src='Public/images/tiles/o2.png' />";
        }elseif ($i>0 and $output[$i][$j]=="S" and $output[$i-1][$j]=="W") {
            echo "<img src='Public/images/tiles/u.png' />";
        }elseif ($i<$dimension*2-1 and $output[$i][$j]=="S" and $output[$i+1][$j]=="W") {
            echo "<img src='Public/images/tiles/d.png' />";
        }elseif ($j>0 and $output[$i][$j]=="S" and $output[$i][$j-1]=="W") {
            echo "<img src='Public/images/tiles/l.png' />";
        }elseif ($j<$dimension*2-1 and $output[$i][$j]=="S" and $output[$i][$j+1]=="W") {
            echo "<img src='Public/images/tiles/r.png' />";
        }elseif ($output[$i][$j]=="W"){
            echo "<img src='Public/images/tiles/W.gif' />";
        }elseif ($output[$i][$j]=="S"){
            echo "<img src='Public/images/tiles/S.png' />";
        }
    }
    echo "<br/>";
}