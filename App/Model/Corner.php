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
     * @var array(Center)
     */
    private $touches;

    /**
     * @var array(Edge)
     */
    private $protrudes;

    /**
     * @var array(Corner)
     */
    private $adjacent;


    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
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