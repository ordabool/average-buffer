<?php

include 'average_buffer.php';

$cycArr = new CyclicArray(10);

echo "---------------- CyclicArray Test ---------------- \n";

for ($i = 0; $i < 12; $i++) {
    $cycArr->append($i + 1);
}

echo $cycArr;

echo "first 2 elements: \n";
echo $cycArr->getElementAtIndex(0) . "\n";
echo $cycArr->getElementAtIndex(1) . "\n";

