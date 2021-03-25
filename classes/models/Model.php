<?php

class Model
{
    protected $meta = 'templates/parts/meta.php';

    /**
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }
}