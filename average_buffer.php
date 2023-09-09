<?php

// TODO: Add class description 
// TODO: Write JavaDoc style documentation
// TODO: public/private fields and functions
// TODO: Add time complexities for functions
// TODO: Write calculated tests (with expected output)
// TODO: Set debugger and check memory usage and especially the clear() func!!!
// TODO: Ask about the implementation of the clear() func - delete or reset?
// TODO: Change properties to start with underscore (maybe)

class AverageBuffer
{
    private $sampleSumForever = 0;
    private $sampleCountForever = 0;
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
        for ($i = 0; $i < count($this->cyclicArray); $i++) {
            $str .= "AverageBuffer[{$i}] = {$this->cyclicArray[$i]} \n";
        }
        return $str;
    }

    function addSample($sample)
    {
        $this->cyclicArray[] = $sample;
        $this->sampleSumForever += $sample;
        $this->sampleCountForever++;
    }

    function getAverage()
    {
        $sampleCount = count($this->cyclicArray);
        if ($sampleCount == 0) {
            return 0;
        }
        $sum = 0;
        for ($i = 0; $i < $sampleCount; $i++) {
            $sum += $this->cyclicArray[$i];
        }
        return $sum / $sampleCount;
    }

    function getAverageForever()
    {
        if ($this->sampleCountForever == 0) {
            return 0;
        }
        return $this->sampleSumForever / $this->sampleCountForever;
    }

    // TODO: Change isUpper to enum instead of bool
    function getQuarterAverage($isUpper)
    {
        $elementCountInQuarter = floor(count($this->cyclicArray) * 0.25);
        if ($elementCountInQuarter == 0) {
            return 0;
        }
        $sum = 0;
        $lastElementIndex = count($this->cyclicArray) - 1;
        for ($i = 0; $i < $elementCountInQuarter; $i++) {
            $index = $isUpper ? ($lastElementIndex - $i) : $i;
            $sum += $this->cyclicArray[$index];
            echo $this->cyclicArray[$index] . ","; // TODO: remove
        }
        echo "\n"; // TODO: remove
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
        $this->sampleSumForever = 0;
        $this->sampleCountForever = 0;
    }
}


// TODO: Implemet ArrayAccess as shown here: 
// https://www.php.net/manual/en/class.arrayaccess.php

class CyclicArray implements Countable, ArrayAccess
{
    private $startIndex = 0;
    private $arraySize = 0;
    private $data;
    private $maxSize;
    function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }
    private function append($value)
    {
        if ($this->arraySize < $this->maxSize) {
            $this->data[$this->arraySize] = $value;
            $this->arraySize++;
        } else {
            $this->data[$this->startIndex] = $value;
            $this->startIndex = $this->getCyclicIndex(1);
        }
    }
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->append($value);
        } else {
            if ($this->offsetExists($offset)) {
                $cyclicIndex = $this->getCyclicIndex($offset);
                $this->data[$cyclicIndex] = $value;
            }
        }
    }
    public function offsetExists($offset): bool
    {
        $cyclicIndex = $this->getCyclicIndex($offset);
        if ($cyclicIndex < $this->arraySize) {
            return true;
        }
        return false;
    }
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }
    public function offsetGet($offset): mixed
    {
        $cyclicIndex = $this->getCyclicIndex($offset);
        return ($cyclicIndex < $this->arraySize) ? $this->data[$cyclicIndex] : null;
    }
    private function getCyclicIndex($i)
    {
        return ($this->startIndex + $i) % $this->arraySize;
    }
    public function count(): int
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
