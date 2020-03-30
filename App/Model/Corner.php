<?php
namespace App\Model;

class Corner
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
     * set of Centers which touch this Corner
     * @var array(Center)
     */
    private $touches;

    /**
     * set of Edges touching this Corner
     * @var array(Edge)
     */
    private $protrudes;

    /**
     * set of Corners connected to this Corner
     * @var array(Corner)
     */
    private $adjacent;

    /**
     * if all around centers are water
     * @var bool
     */
    private $water = false;

    /**
     * one of centers is water and another is ocean
     * @var bool
     */
    private $coast = false;

    /**
     * all centers around are ocean
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

    public function getCoordination(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }

    /**
     * @param array $adjacent
     */
    public function setAdjacent(array $adjacent): void
    {
        $this->adjacent = $adjacent;
    }

    /**
     * @return array
     */
    public function getAdjacent(): array
    {
        return $this->adjacent;
    }

    /**
     * @param array $touches
     */
    public function setTouches(array $touches): void
    {
        $this->touches = $touches;
    }

    /**
     * @param Center $touch
     */
    public function addTouches(Center $touch): void
    {
        $this->touches[] = $touch;
    }

    /**
     * @return array
     */
    public function getTouches(): array
    {
        return $this->touches;
    }

    /**
     * @param array $protrudes
     */
    public function setProtrudes(array $protrudes): void
    {
        $this->protrudes = $protrudes;
    }

    /**
     * @return array
     */
    public function getProtrudes(): array
    {
        return $this->protrudes;
    }
}