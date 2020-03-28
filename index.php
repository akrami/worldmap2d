<?php

use App\Model\Center;

require_once __DIR__.'/bootstrap.php';

$width = 50;
$result = array(array());
for ($i=0; $i<4; $i++) {
    for ($j=0; $j<4; $j++){
        $result[$i][$j] = new Center($i*$width+$width/2,$j*$width+$width/2);
        $coords = $result[$i][$j]->getCoordination();
        print_r($coords);
    }
}