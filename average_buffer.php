<?php

class AverageBuffer
{
    private $sumForever;
    private $elementsCountForever;
    private $sum;
    private $elementsCount;
    private $size;
    private $cyclicArray;

    function __construct($size)
    {
        $this->size = $size;
        $this->cyclicArray = new CyclicArray($size);
    }

    function addSample($sample)
    {
    }

    function getAverage()
    {
    }

    function getAverageForever()
    {
    }

    function getUpperQuarterAverage()
    {
    }

    function getLowerQuarterAverage()
    {
    }

    function clear()
    {
    }
}


// TODO: Implemet ArrayAccess as shown here: 
// https://www.php.net/manual/en/class.arrayaccess.php

class CyclicArray
{
    private $startIndex = 0;
    private $arraySize = 0;
    private $data;
    private $maxSize;
    function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }
    function append($value)
    {
        if ($this->arraySize < $this->maxSize) {
            $this->data[$this->arraySize] = $value;
            $this->arraySize++;
        } else {
            $this->data[$this->startIndex] = $value;
            $this->startIndex++;
        }
    }
    function getCyclicIndex($i)
    {
        return ($this->startIndex + $i) % $this->arraySize;
    }
    function getElementAtIndex($i)
    {
        $cyclicIndex = $this->getCyclicIndex($i);
        return ($cyclicIndex < $this->arraySize) ? $this->data[$cyclicIndex] : null;
    }
    function __toString()
    {
        $str = '';
        for ($i = 0; $i < $this->arraySize; $i++) {
            $cyclicIndex = $this->getCyclicIndex($i);
            $str .= "CyclicArray[{$cyclicIndex}] = {$this->data[$cyclicIndex]} \n";
        }
        return $str;
    }
}
