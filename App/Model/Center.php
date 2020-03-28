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
     * @return array
     */
    public function getNeighbors(): array
    {
        return $this->neighbors;
    }
}