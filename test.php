<?php

include 'average_buffer.php';

$cycArr = new CyclicArray(10);

echo "---------------- CyclicArray Test ---------------- \n";

for ($i = 0; $i < 22; $i++) {
    $cycArr[] = $i + 1;
}

echo $cycArr;

echo "first 2 elements: \n";
echo $cycArr[0] . "\n";
echo $cycArr[1] . "\n";

echo "---------------- AverageBuffer Test ---------------- \n";

$avgBuffer = new AverageBuffer(10);

for ($i = 10; $i < 23; $i++) {
    $avgBuffer->addSample($i + 1);
}

echo $avgBuffer;

echo "--------------- clear() ------------------ \n";
echo $avgBuffer;
$avgBuffer->clear();

echo "--------------------------------- \n";

$avgBuffer2 = new AverageBuffer(8);
$avgBuffer2->addSample(10);
$avgBuffer2->addSample(40);
$avgBuffer2->addSample(30);
$avgBuffer2->addSample(44);
$avgBuffer2->addSample(20);
$avgBuffer2->addSample(50);
$avgBuffer2->addSample(35);
$avgBuffer2->addSample(55);

echo $avgBuffer2;
echo "Upper: {$avgBuffer2->getUpperQuarterAverage()} \n";
echo "Lower: {$avgBuffer2->getLowerQuarterAverage()} \n";
