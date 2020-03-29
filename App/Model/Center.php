<?php
namespace App\Model;

class Center
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var array(Center)
     */
    private $neighbors;

    /**
     * @var array(Edge)
     */
    private $borders;

    /**
     * @var array(Corner)
     */
    private $corners;

    /**
     * if this tile has water. if it's water and connected to ocean so it is ocean
     * @var bool
     */
    private $water = false;

    /**
     * it is a land but connected to an ocean tile
     * @var bool
     */
    private $coast = false;

    /**
     * all border tiles are ocean. all water tiles connected to ocean are ocean
     * @var bool
     */
    private $ocean = false;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function __toString()
    {
        return '('.$this->x.','.$this->y.')';
    }

    /**
     * @param int $x
     * @param int $y
     */
    public function setCoordination(int $x, int $y): void
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return array
     */
    public function getCoordination(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }

    /**
     * @param array $corners
     */
    public function setCorners(array $corners): void
    {
        $this->corners = $corners;
    }

    /**
     * @return array
     */
    public function getCorners(): array
    {
        return $this->corners;
    }

    /**
     * @param array $borders
     */
    public function setBorders(array $borders): void
    {
        $this->borders = $borders;
    }

    /**
     * @return array
     */
    public function getBorders(): array
    {
        return $this->borders;
    }

    /**
     * @param array $neighbors
     */
    public function setNeighbors(array $neighbors): void
    {
        $this->neighbors = $neighbors;
    }

    /**
     * @param Center $neighbor
     */
    public function addNeighbor(Center $neighbor): void
    {
        $this->neighbors[] = $neighbor;
    }

    /**
     * @return array
     */
    public function getNeighbors(): array
    {
        return $this->neighbors;
    }
}