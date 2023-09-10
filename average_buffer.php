<?php

/**
 * 
 * AverageBuffer Class 
 * 
 * This class implements an AverageBuffer. The AverageBuffer allows to save up to $size numerical samples,
 * and then get different averages for the given samples (i.e. Forever, UpperQuarter..)
 * 
 * Class properties:
 * $sampleSumForever   - sums all of the samples that are inserted into the AverageBuffer
 * $sampleCountForever - counts all of the samples that are inserted into the AverageBuffer
 * $size               - holds the size of the AverageBuffer
 *                       There can be less samples than $size at a given moment, but not more
 * $cyclicArray        - an instance of CyclicArray that stores the samples in a cyclic manner
 * 
 */
class AverageBuffer
{
    // --- Private properties ------------------------------------------------------------------------------------------
    private float $sampleSumForever = 0;
    private int $sampleCountForever = 0;
    private int $size;
    private CyclicArray $cyclicArray;

    // --- Public API functions ----------------------------------------------------------------------------------------
    /**
     * Constructs the AverageBuffer with a specified size
     * 
     * @param int $size - the size of the AverageBuffer
     */
    function __construct(int $size)
    {
        $this->size = $size;
        $this->cyclicArray = new CyclicArray($size);
    }

    /**
     * Destroys the AverageBuffer, and specifically it's CyclicArray object
     */
    function __destruct()
    {
        unset($this->cyclicArray);
    }

    /**
     * Get a string representation of the AverageBuffer
     *
     * @return string representation of the AverageBuffer
     */
    function __toString(): string
    {
        $str = '';
        for ($i = 0; $i < count($this->cyclicArray); $i++) {
            $str .= "AverageBuffer[{$i}] = {$this->cyclicArray[$i]} \n";
        }
        return $str;
    }

    /**
     * Adds a new sample to the AverageBuffer. If the AverageBuffer is full, the new sample will replace the oldest
     * sample
     * The new sample is added to $sampleSumForever, and increases $sampleCountForever in order to 
     * support getAverageForever()
     * 
     * @param float $sample - the numeric sample to add
     */
    function addSample(float $sample): void
    {
        $this->cyclicArray[] = $sample;
        $this->sampleSumForever += $sample;
        $this->sampleCountForever++;
    }

    /**
     * Get the average of all the samples in AverageBuffer
     * 
     * @return float the average of the samples in AverageBuffer
     */
    function getAverage(): float
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

    /**
     * Get the average of all the samples that ever was in the AverageBuffer
     * 
     * @return float the average of all the samples that ever was in the AverageBuffer
     */
    function getAverageForever(): float
    {
        if ($this->sampleCountForever == 0) {
            return 0;
        }
        return $this->sampleSumForever / $this->sampleCountForever;
    }

    /**
     * Get the quarter average for the given type (upper/lower quarter)
     * 
     * @param QuarterAverageType $type - the type of the quarter
     * @return float the average of the quarter in the AverageBuffer
     */
    function getQuarterAverage(QuarterAverageType $type): float
    {
        $elementCountInQuarter = floor(count($this->cyclicArray) * 0.25);
        if ($elementCountInQuarter == 0) {
            return 0;
        }
        $sum = 0;
        $lastElementIndex = count($this->cyclicArray) - 1;
        for ($i = 0; $i < $elementCountInQuarter; $i++) {
            $index = ($type == QuarterAverageType::Upper) ? ($lastElementIndex - $i) : $i;
            $sum += $this->cyclicArray[$index];
        }
        return $sum / $elementCountInQuarter;
    }

    /**
     * Get the upper quarter average
     * 
     * @return float the average of the upper quarter in the AverageBuffer
     */
    function getUpperQuarterAverage(): float
    {
        return $this->getQuarterAverage(QuarterAverageType::Upper);
    }

    /**
     * Get the lower quarter average
     * 
     * @return float the average of the lower quarter in the AverageBuffer
     */
    function getLowerQuarterAverage(): float
    {
        return $this->getQuarterAverage(QuarterAverageType::Lower);
    }

    /**
     * Clear and reset the current AverageBuffer
     */
    function clear(): void
    {
        unset($this->cyclicArray);
        $this->cyclicArray = new CyclicArray($this->size);
        $this->sampleSumForever = 0;
        $this->sampleCountForever = 0;
    }
}

/**
 * 
 * CyclicArray Class
 * 
 * This class implements an array that holds up to $maxSize elements.
 * The idea is to make the array cyclic, so if it's full, it will regard another element as the $startIndex,
 * which means it doesn't have to be 0 like in a traditional array.
 * Thus, the CyclicArray can always hold up to $maxSize of elements, without needing to use operations such
 * as array_shift and array_slice.
 * 
 * CyclicArray also implements the Countable and ArrayAccess interfaces, in order to function just like a
 * traditional array, i.e. CyclicArray[0] will point to the element that's in $startIndex, because it is
 * really the first element, even if $startIndex != 0
 * 
 * Class properties:
 * $startIndex  - the index of the first element
 * $arraySize   - how many elements are in the array
 * $maxSize     - the max amount of permitted elements in the array
 *                Once $arraySize = $maxSize, the array will replace older elements upon inserting new ones
 * $data        - a primitive array that holds all of the elements in the CyclicArray
 * 
 */
class CyclicArray implements ArrayAccess, Countable
{
    // --- Private properties ------------------------------------------------------------------------------------------
    private int $startIndex = 0;
    private int $arraySize = 0;
    private int $maxSize;
    private array $data;

    // --- Private helper functions ------------------------------------------------------------------------------------
    /**
     * Append a new element to the CyclicArray
     * If the CyclicArray is full, replace the oldest element and mark the start of the array as the next oldest element
     * Otherwise, just append the element to $data in the next available index
     * 
     * @param float $value - the value of the new element to append to the CyclicArray
     */
    private function append(float $value): void
    {
        if ($this->arraySize < $this->maxSize) {
            $this->data[$this->arraySize] = $value;
            $this->arraySize++;
        } else {
            $this->data[$this->startIndex] = $value;
            $this->startIndex = $this->getCyclicIndex(1);
        }
    }

    // --- Public API functions ----------------------------------------------------------------------------------------
    /**
     * Constructs the CyclicArray with a maximun size
     * 
     * @param int $maxSize - the maximun size of the CyclicArray
     */
    function __construct(int $maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Get a string representation of the CyclicArray
     *
     * @return string representation of the CyclicArray
     */
    function __toString(): string
    {
        $str = '';
        for ($i = 0; $i < $this->arraySize; $i++) {
            $cyclicIndex = $this->getCyclicIndex($i);
            $str .= "CyclicArray[{$cyclicIndex}] = {$this->data[$cyclicIndex]} \n";
        }
        return $str;
    }

    /**
     * Get the cyclic index for a "regular" index argument. Meaning that for $startIndex = x, 
     * getCyclicIndex(y) will result to ((x+y) % $arraySize) in a cyclic fashion
     * 
     * @param int $regularIndex - the index to convert to a cyclic index
     */
    private function getCyclicIndex(int $regularIndex): int
    {
        return ($this->startIndex + $regularIndex) % $this->arraySize;
    }

    /**
     * Sets a value at a given offset in the CyclicArray. If no offset is given, just append the value as a new element
     * 
     * Part of the ArrayAccess interface
     * 
     * @param mixed $offset - the offset to set
     * @param mixed $value - the value to set
     */
    function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->append((float)$value);
        } else {
            if ($this->offsetExists($offset)) {
                $cyclicIndex = $this->getCyclicIndex((int)$offset);
                $this->data[$cyclicIndex] = (float)$value;
            }
        }
    }

    /**
     * Checks if the offset exists in the CyclicArray
     * 
     * Part of the ArrayAccess interface
     * 
     * @param mixed $offset - the offset to check
     * @return bool true if the offeset exists
     */
    function offsetExists(mixed $offset): bool
    {
        $cyclicIndex = $this->getCyclicIndex((int)$offset);
        if ($cyclicIndex < $this->arraySize) {
            return true;
        }
        return false;
    }

    /**
     * Unset the value in the given offset in the CyclicArray
     * 
     * Part of the ArrayAccess interface
     * 
     * @param mixed $offset - the offset to unset
     */
    function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    /**
     * Get the value in the given offset in the CyclicArray
     * 
     * Part of the ArrayAccess interface
     * 
     * @param mixed $offset - the offset to get
     * @return mixed the value at the given offset, or null if doesn't exist
     */
    function offsetGet($offset): mixed
    {
        $cyclicIndex = $this->getCyclicIndex((int)$offset);
        return ($cyclicIndex < $this->arraySize) ? $this->data[$cyclicIndex] : null;
    }

    /**
     * Counts the amount of elements in the CyclicArray
     * 
     * Part of the Countable interface
     * 
     * @return int the amount of elements in the CyclicArray
     */
    function count(): int
    {
        return $this->arraySize;
    }
}

// Helper enum of the different quarter average types: upper / lower
enum QuarterAverageType
{
    case Upper;
    case Lower;
}
