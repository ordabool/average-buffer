<?php

include 'average_buffer.php';


echo "------------------------------ Test 1 ----------------------------- \n";
echo "Building [ 10 ... 10, 20 ... 20, 30 ... 30, 40 ... 40 ] \n";

$test1 = new AverageBuffer(100);
for ($i = 0; $i < 25; $i++) {
    $test1->addSample(10);
}
for ($i = 25; $i < 50; $i++) {
    $test1->addSample(20);
}
for ($i = 50; $i < 75; $i++) {
    $test1->addSample(30);
}
for ($i = 75; $i < 100; $i++) {
    $test1->addSample(40);
}

// echo "AverageBuffer: \n" . $test1;
echo "getUpperQuarterAverage = " . $test1->getUpperQuarterAverage() . ", should be 40" . "\n";
echo "getLowerQuarterAverage = " . $test1->getLowerQuarterAverage() . ", should be 10" . "\n";
echo "getAverage = " . $test1->getAverage() . ", should be 25" . "\n";
echo "getAverageForever = " . $test1->getAverageForever() . ", should be 25" . "\n";

echo "\nAdding the value 50, 100 times.. \n";
for ($i = 0; $i < 100; $i++) {
    $test1->addSample(50);
}
echo "getAverage = " . $test1->getAverage() . ", should be 50" . "\n";
echo "getAverageForever = " . $test1->getAverageForever() . ", should be 37.5" . "\n";

echo "\nClearing AverageBuffer.. \n";
$test1->clear();
echo "Building [ 1 ... 25 ] \n";
for ($i = 1; $i <= 25; $i++) {
    $test1->addSample($i);
}
echo "AverageBuffer: \n" . $test1;
echo "getAverage = " . $test1->getAverage() . ", should be 13" . "\n";
echo "getAverageForever = " . $test1->getAverageForever() . ", should be 13" . "\n";
echo "getUpperQuarterAverage = " . $test1->getUpperQuarterAverage() . ", should be 22.5" . "\n";
echo "getLowerQuarterAverage = " . $test1->getLowerQuarterAverage() . ", should be 3.5" . "\n";

echo "------------------------------ Test 2 ----------------------------- \n";
echo "Building [10, 40, 30, 44, 20, 50, 35, 55]: \n";
$test2 = new AverageBuffer(8);
$test2->addSample(10);
$test2->addSample(40);
$test2->addSample(30);
$test2->addSample(44);
$test2->addSample(20);
$test2->addSample(50);
$test2->addSample(35);
$test2->addSample(55);
echo "AverageBuffer: \n" . $test2;
echo "getAverage = " . $test2->getAverage() . ", should be 35.5" . "\n";
echo "getAverageForever = " . $test2->getAverageForever() . ", should be 35.5" . "\n";
echo "getUpperQuarterAverage = " . $test2->getUpperQuarterAverage() . ", should be 45" . "\n";
echo "getLowerQuarterAverage = " . $test2->getLowerQuarterAverage() . ", should be 25" . "\n";

echo "\nAdding in reverse order.. \n";
$test2->addSample(55);
$test2->addSample(35);
$test2->addSample(50);
$test2->addSample(20);
$test2->addSample(44);
$test2->addSample(30);
$test2->addSample(40);
$test2->addSample(10);
echo "AverageBuffer: \n" . $test2;
echo "getAverage = " . $test2->getAverage() . ", should be 35.5" . "\n";
echo "getAverageForever = " . $test2->getAverageForever() . ", should be 35.5" . "\n";
echo "getUpperQuarterAverage = " . $test2->getUpperQuarterAverage() . ", should be 25" . "\n";
echo "getLowerQuarterAverage = " . $test2->getLowerQuarterAverage() . ", should be 45" . "\n";

echo "------------------------------ Test 3 ----------------------------- \n";
echo "Adding 1 .. 25 to AverageBuffer with size 10: \n";
$test3 = new AverageBuffer(10);
for ($i = 1; $i <= 25; $i++) {
    $test3->addSample($i);
}
echo "AverageBuffer: \n" . $test3;
echo "getAverage = " . $test3->getAverage() . ", should be 20.5" . "\n";
echo "getAverageForever = " . $test3->getAverageForever() . ", should be 13" . "\n";
echo "getUpperQuarterAverage = " . $test3->getUpperQuarterAverage() . ", should be 24.5" . "\n";
echo "getLowerQuarterAverage = " . $test3->getLowerQuarterAverage() . ", should be 16.5" . "\n";
