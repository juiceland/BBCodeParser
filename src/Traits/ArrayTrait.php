<?php namespace Golonka\BBCode\Traits;

trait ArrayTrait
{

    /**
     * Filters all parsers that you don´t want
     * @param  array $only chosen parsers
     * @return array parsers
     */
    private function arrayOnly($only)
    {
        return array_intersect_key($this->parsers, array_flip((array) $only));
    }

    /**
     * Removes the parsers that you don´t want
     * @param  array $except parsers to exclude
     * @return array parsers
     */
    private function arrayExcept($excepts)
    {
        return array_diff_key($this->parsers, array_flip((array) $excepts));
    }
}
