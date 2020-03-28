<?php
namespace App\Model;

class Edge
{
    /**
     * @var Center
     */
    private $d0;

    /**
     * @var Center
     */
    private $d1;

    /**
     * @var Corner
     */
    private $v0;

    /**
     * @var Corner
     */
    private $v1;


    /**
     * Edge constructor.
     * @param Center $d0
     * @param Center $d1
     * @param Corner $v0
     * @param Corner $v1
     */
    public function __construct(Center $d0, Center $d1, Corner $v0, Corner $v1)
    {
        $this->d0 = $d0;
        $this->d1 = $d1;
        $this->v0 = $v0;
        $this->v1 = $v1;
    }

    /**
     * @param Center $d0
     */
    public function setD0(Center $d0): void
    {
        $this->d0 = $d0;
    }

    /**
     * @return Center
     */
    public function getD0(): Center
    {
        return $this->d0;
    }

    /**
     * @param Center $d1
     */
    public function setD1(Center $d1): void
    {
        $this->d1 = $d1;
    }

    /**
     * @return Center
     */
    public function getD1(): Center
    {
        return $this->d1;
    }

    /**
     * @param Corner $v0
     */
    public function setV0(Corner $v0): void
    {
        $this->v0 = $v0;
    }

    /**
     * @return Corner
     */
    public function getV0(): Corner
    {
        return $this->v0;
    }

    /**
     * @param Corner $v1
     */
    public function setV1(Corner $v1): void
    {
        $this->v1 = $v1;
    }

    /**
     * @return Corner
     */
    public function getV1(): Corner
    {
        return $this->v1;
    }
}