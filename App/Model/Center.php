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
     * set of adjacent Centers
     * @var array(Center)
     */
    private $neighbors;

    /**
     * set of bordering Edges
     * @var array(Edge)
     */
    private $borders;

    /**
     * set of Corners around this Center
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
     * @param Corner $corner
     */
    public function addCorner(Corner $corner): void
    {
        $this->corners[] = $corner;
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
     * @param Edge $border
     */
    public function addBorder(Edge $border): void
    {
        $this->borders[] = $border;
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