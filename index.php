<?php

use App\Model\Map;

require_once __DIR__ . '/bootstrap.php';

$dimension = 40;
$map = new Map($dimension);
$output = $map->getOutput();

$m = count($output);
$n = count($output[0]);

for ($i=0; $i<$m; $i++) {
    for ($j = 0; $j < $n; $j++) {
        if ($i>0 and $j>0 and $output[$i][$j]=="S" and $output[$i-1][$j]=="S" and $output[$i][$j-1]=="S" and $output[$i-1][$j-1]=="W"){
            echo "<img src='Public/images/tiles/i3.png' />";
        }elseif($i<$m-1 and $j>0 and $output[$i][$j]=="S" and $output[$i+1][$j]=="S" and $output[$i][$j-1]=="S" and $output[$i+1][$j-1]=="W"){
            echo "<img src='Public/images/tiles/i2.png' />";
        }elseif ($i<$m-1 and $j<$n-1 and $output[$i][$j] == "S" and $output[$i + 1][$j] == "S" and $output[$i][$j + 1] == "S" and $output[$i+1][$j+1]=="W") {
            echo "<img src='Public/images/tiles/i1.png' />";
        }elseif ($i>0 and $j<$n-1 and $output[$i][$j] == "S" and $output[$i - 1][$j] == "S" and $output[$i][$j + 1] == "S" and $output[$i-1][$j+1]=="W") {
            echo "<img src='Public/images/tiles/i4.png' />";
        }elseif ($i > 0 and $j > 0 and $output[$i][$j] == "S" and $output[$i - 1][$j] == "W" and $output[$i][$j - 1] == "W") {
            echo "<img src='Public/images/tiles/o1.png' />";
        }elseif ($i<$m-1 and $j > 0 and $output[$i][$j] == "S" and $output[$i + 1][$j] == "W" and $output[$i][$j - 1] == "W") {
            echo "<img src='Public/images/tiles/o4.png' />";
        }elseif ($i<$m-1 and $j<$n-1 and $output[$i][$j] == "S" and $output[$i + 1][$j] == "W" and $output[$i][$j + 1] == "W") {
            echo "<img src='Public/images/tiles/o3.png' />";
        }elseif ($i>0 and $j<$n-1 and $output[$i][$j] == "S" and $output[$i - 1][$j] == "W" and $output[$i][$j + 1] == "W") {
            echo "<img src='Public/images/tiles/o2.png' />";
        }elseif ($i>0 and $output[$i][$j]=="S" and $output[$i-1][$j]=="W") {
            echo "<img src='Public/images/tiles/u.png' />";
        }elseif ($i<$m-1 and $output[$i][$j]=="S" and $output[$i+1][$j]=="W") {
            echo "<img src='Public/images/tiles/d.png' />";
        }elseif ($j>0 and $output[$i][$j]=="S" and $output[$i][$j-1]=="W") {
            echo "<img src='Public/images/tiles/l.png' />";
        }elseif ($j<$n-1 and $output[$i][$j]=="S" and $output[$i][$j+1]=="W") {
            echo "<img src='Public/images/tiles/r.png' />";
        }elseif ($output[$i][$j]=="W"){
            echo "<img src='Public/images/tiles/W.gif' />";
        }elseif ($output[$i][$j]=="S"){
            echo "<img src='Public/images/tiles/S.png' />";
        }
    }
    echo "<br/>";
}