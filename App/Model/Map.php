<?php


namespace App\Model;


class Map
{
    /**
     * @var int
     */
    private $dimension;

    /**
     * @var int
     */
    private $width = 2;

    /**
     * @var array
     */
    private $map = ["centers" => array(array()),
        "corners" => array(array()),
        "edges" => ["v" => array(array()),
            "h" => array(array())]
    ];

    /**
     * Map constructor.
     * @param $dimension
     * @param int $width
     */
    public function __construct($dimension, $width = 2)
    {
        $this->dimension = $dimension;
        $this->width = $width;
        $this->createCenters($this->map["centers"]);
        $this->calculateNeighbors($this->map["centers"]);
        $this->createCorners($this->map["corners"], $this->map["centers"]);
        $this->calculateAdjacent($this->map["corners"]);
        $this->createEdges($this->map["edges"], $this->map["centers"], $this->map["corners"]);
        $moisture = $this->calculateMoisture();
        $this->calculateWater($moisture, $this->map["centers"]);
    }

    /**
     * @param array $centers
     */
    private function createCenters(array &$centers)
    {
        for ($i = 0; $i < $this->dimension; $i++) {
            for ($j = 0; $j < $this->dimension; $j++) {
                $centers[$i][$j] = new Center($i * $this->width + $this->width / 2, $j * $this->width + $this->width / 2);
            }
        }
    }

    /**
     * @param array $centers
     */
    private function calculateNeighbors(array &$centers)
    {
        for ($i = 0; $i < $this->dimension; $i++) {
            for ($j = 0; $j < $this->dimension; $j++) {
                if ($i - 1 >= 0) $centers[$i][$j]->addNeighbor($centers[$i - 1][$j]);
                if ($i + 1 < $this->dimension) $centers[$i][$j]->addNeighbor($centers[$i + 1][$j]);
                if ($j - 1 >= 0) $centers[$i][$j]->addNeighbor($centers[$i][$j - 1]);
                if ($j + 1 < $this->dimension) $centers[$i][$j]->addNeighbor($centers[$i][$j + 1]);
            }
        }
    }

    /**
     * @param array $corners
     * @param array $centers
     */
    private function createCorners(array &$corners, array &$centers)
    {
        for ($i = 0; $i <= $this->dimension; $i++) {
            for ($j = 0; $j <= $this->dimension; $j++) {
                $corners[$i][$j] = new Corner($i * $this->width, $j * $this->width);

                if ($i != 0 and $j != 0) {
                    $corners[$i][$j]->addTouches($centers[$i - 1][$j - 1]);
                    $centers[$i - 1][$j - 1]->addCorner($corners[$i][$j]);
                }
                if ($i != 0 and $j != $this->dimension) {
                    $corners[$i][$j]->addTouches($centers[$i - 1][$j]);
                    $centers[$i - 1][$j]->addCorner($corners[$i][$j]);
                }
                if ($i != $this->dimension and $j != 0) {
                    $corners[$i][$j]->addTouches($centers[$i][$j - 1]);
                    $centers[$i][$j - 1]->addCorner($corners[$i][$j]);
                }
                if ($i != $this->dimension and $j != $this->dimension) {
                    $corners[$i][$j]->addTouches($centers[$i][$j]);
                    $centers[$i][$j]->addCorner($corners[$i][$j]);
                }
            }
        }
    }

    /**
     * @param array $corners
     */
    private function calculateAdjacent(array &$corners)
    {
        for ($i = 0; $i <= $this->dimension; $i++) {
            for ($j = 0; $j <= $this->dimension; $j++) {
                if ($i > 0) $corners[$i][$j]->addAdjacent($corners[$i - 1][$j]);
                if ($i < $this->dimension) $corners[$i][$j]->addAdjacent($corners[$i + 1][$j]);
                if ($j > 0) $corners[$i][$j]->addAdjacent($corners[$i][$j - 1]);
                if ($j < $this->dimension) $corners[$i][$j]->addAdjacent($corners[$i][$j + 1]);
            }
        }
    }

    /**
     * @param array $edges
     * @param array $centers
     * @param array $corners
     */
    private function createEdges(array &$edges, array &$centers, array &$corners)
    {
        for ($i = 0; $i < $this->dimension; $i++) {
            for ($j = 0; $j < $this->dimension; $j++) {
                if ($j < $this->dimension - 1) {
                    $edges["v"][$i][$j] = new Edge($centers[$i][$j],
                        $centers[$i][$j + 1],
                        $corners[$i][$j + 1],
                        $corners[$i + 1][$j + 1]);
                    $centers[$i][$j]->addBorder($edges["v"][$i][$j]);
                    $centers[$i][$j + 1]->addBorder($edges["v"][$i][$j]);
                    $corners[$i][$j + 1]->addProtrude($edges["v"][$i][$j]);
                    $corners[$i + 1][$j + 1]->addProtrude($edges["v"][$i][$j]);
                }
                if ($i < $this->dimension - 1) {
                    $edges["h"][$i][$j] = new Edge($centers[$i][$j],
                        $centers[$i + 1][$j],
                        $corners[$i + 1][$j],
                        $corners[$i + 1][$j + 1]);
                    $centers[$i][$j]->addBorder($edges["h"][$i][$j]);
                    $centers[$i + 1][$j]->addBorder($edges["h"][$i][$j]);
                    $corners[$i + 1][$j]->addProtrude($edges["h"][$i][$j]);
                    $corners[$i + 1][$j + 1]->addProtrude($edges["h"][$i][$j]);
                }
            }
        }
    }

    /**
     * @return array
     */
    private function calculateMoisture(): array
    {
        $moisture = array(array());
        $SR = 0;                 // starting row index
        $SC = 0;                 // starting column index
        $ER = $this->dimension;        // ending row index
        $EC = $this->dimension;        // ending column index

        for ($i = 0; $i < $this->dimension; $i++) {
            $x = mt_rand(0, $this->dimension - 1);
            $y = mt_rand(0, $this->dimension - 1);
            $moisture[$x][$y] = 100;
        }

        while ($SR < $ER && $SC < $EC) {
            for ($i = $SC; $i < $EC; ++$i) {
                $moisture[$SR][$i] = $this->neighborsMoisture($moisture, $this->dimension, $SR, $i);
            }
            $SR++;

            for ($i = $SR; $i < $ER; ++$i) {
                $moisture[$i][$EC - 1] = $this->neighborsMoisture($moisture, $this->dimension, $i, $EC - 1);
            }
            $EC--;

            if ($SR < $ER) {
                for ($i = $EC - 1; $i >= $SC; --$i) {
                    $moisture[$ER - 1][$i] = $this->neighborsMoisture($moisture, $this->dimension, $ER - 1, $i);
                }
                $ER--;
            }

            if ($SC < $EC) {
                for ($i = $ER - 1; $i >= $SR; --$i) {
                    $moisture[$i][$SC] = $this->neighborsMoisture($moisture, $this->dimension, $i, $SC);
                }
                $SC++;
            }
        }

        return $moisture;
    }

    /**
     * @param array $moisture
     * @param int $n
     * @param int $i
     * @param int $j
     * @return float
     */
    private function neighborsMoisture(array $moisture, int $n, int $i, int $j): float
    {
        if ($i == 0 or $i == $n - 1 or $j == 0 or $j == $n - 1) {
            return number_format(100, 2);
        } else {
            $sum = 0;
            $count = 0;
            if (isset($moisture[$i - 1][$j])) {
                $sum += $moisture[$i - 1][$j];
                $count++;
            }
            if (isset($moisture[$i][$j - 1])) {
                $sum += $moisture[$i][$j - 1];
                $count++;
            }
            if (isset($moisture[$i + 1][$j])) {
                $sum += $moisture[$i + 1][$j];
                $count++;
            }
            if (isset($moisture[$i][$j + 1])) {
                $sum += $moisture[$i][$j + 1];
                $count++;
            }
            if (isset($moisture[$i + 1][$j + 1])) {
                $sum += $moisture[$i + 1][$j + 1];
                $count++;
            }
            if (isset($moisture[$i - 1][$j + 1])) {
                $sum += $moisture[$i - 1][$j + 1];
                $count++;
            }
            if (isset($moisture[$i + 1][$j - 1])) {
                $sum += $moisture[$i + 1][$j - 1];
                $count++;
            }
            if (isset($moisture[$i - 1][$j - 1])) {
                $sum += $moisture[$i - 1][$j - 1];
                $count++;
            }
            if ($count === 0) {
                return number_format(100, 2);
            } else {
                return $this->moistureProbability($sum, $count);
            }
        }
    }

    /**
     * @param int $sum
     * @param int $count
     * @return float
     */
    private function moistureProbability(int $sum, int $count): float
    {
        $random = log10(mt_rand(1, 10));
        return number_format(($sum / $count) * $random, 2);
    }

    /**
     * @param array $moisture
     * @param array $centers
     */
    private function calculateWater(array $moisture, array &$centers):void
    {
        for ($i = 0; $i < $this->dimension; $i++) {
            for ($j = 0; $j < $this->dimension; $j++) {
                if ($moisture[$i][$j] > mt_rand(20, 50)) {
                    $centers[$i][$j]->setWater(true);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getOutput(): array
    {
        $output = array(array());
        for ($i = 0; $i < $this->dimension; $i++) {
            for ($j = 0; $j < $this->dimension; $j++) {
                $type = $this->map["centers"][$i][$j]->isWater() ? "W" : "S";
                $output[$i * 2][$j * 2] = $type;
                $output[$i * 2 + 1][$j * 2] = $type;
                $output[$i * 2][$j * 2 + 1] = $type;
                $output[$i * 2 + 1][$j * 2 + 1] = $type;
            }
        }

        return $output;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @return array
     */
    public function getCenters(): array
    {
        return $this->map["centers"];
    }

    /**
     * @return array
     */
    public function getCorners(): array
    {
        return $this->map["corners"];
    }

    /**
     * @return array
     */
    public function getEdges(): array
    {
        return $this->map["edges"];
    }
}