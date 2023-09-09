<?php

class AverageBuffer
{
    private $sumForever = 0;
    private $elementsCountForever = 0;
    private $size;
    private $cyclicArray;

    function __construct($size)
    {
        $this->size = $size;
        $this->cyclicArray = new CyclicArray($size);
    }

    function __toString()
    {
        $str = '';
        for ($i = 0; $i < $this->cyclicArray->getSize(); $i++) {
            $cyclicIndex = $this->cyclicArray->getCyclicIndex($i);
            $str .= "CyclicArray[{$cyclicIndex}] = {$this->cyclicArray->getElementAtIndex($cyclicIndex)} \n";
        }
        return $str;
    }

    function addSample($sample)
    {
        $this->cyclicArray->append($sample);
        $this->sumForever += $sample;
        $this->elementsCountForever++;
    }

    function getAverage()
    {
        $elementsCount = $this->cyclicArray->getSize();
        if ($elementsCount == 0) {
            return 0;
        }
        $sum = 0;
        for ($i = 0; $i < $this->cyclicArray->getSize(); $i++) {
            $sum += $this->cyclicArray->getElementAtIndex($i);
        }
        return $sum / $elementsCount;
    }

    function getAverageForever()
    {
        if ($this->elementsCountForever == 0) {
            return 0;
        }
        return $this->sumForever / $this->elementsCountForever;
    }

    function getElementCountInQuarter()
    {
        return floor($this->cyclicArray->getSize() / 4);
    }

    function getQuarterAverage($isUpper)
    {
        $elementCountInQuarter = $this->getElementCountInQuarter();
        if ($elementCountInQuarter == 0) {
            return 0;
        }
        $sum = 0;
        for ($i = 0; $i < $elementCountInQuarter; $i++) {
            $index = $isUpper ? ($this->cyclicArray->getSize() - $i - 1) : $i;
            $sum += $this->cyclicArray->getElementAtIndex($index);
            echo $this->cyclicArray->getElementAtIndex($index) . ",";
        }
        echo "\n";
        return $sum / $elementCountInQuarter;
    }

    function getUpperQuarterAverage()
    {
        return $this->getQuarterAverage(true);
    }

    function getLowerQuarterAverage()
    {
        return $this->getQuarterAverage(false);
    }

    function clear()
    {
        unset($this->cyclicArray);
        $this->cyclicArray = new CyclicArray($this->size);
        $this->sumForever = 0;
        $this->elementsCountForever = 0;
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
    function getSize()
    {
        return $this->arraySize;
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
