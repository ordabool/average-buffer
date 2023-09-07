<?php

    // - Create a buffer of 100 samples that will calculate
    //   'Average', 'Average-forever', 'Upper Quarter Average' and 'Lower Quarter Average'.
    //   Note: Buffer size can vary.
    // - Every time a new sample enters the buffer the oldest sample will be extracted
    //   from the buffer (if the buffer is full).
    // - 'Average' is the average of the 100 samples that are currently in the buffer.
    // - 'Average-forever' is the average of all samples from the beginning of the run.
    // - 'Upper Quarter Average' is the average of the newest 25 samples.
    // - 'Lower Quarter Average' is the average of the oldest 25 samples.
    //    For example, if the buffer size is 8 and the last samples are 
    //      [10,40,30,44,20,50,35,55]:
    //      'Upper Quarter Average' is the average of [35,55] (the newest two samples).
    //      'Lower Quarter Average' is the average of [10,40] (the oldest two samples).

include 'average_buffer.php';

$avgBuff = new AverageBuffer(100);

for($i = 0; $i < 200; $i++) {
    $rand = rand() % 100;
    $avgBuff->addSample($rand);

    echo "i = $i, rand = $rand\n";
    echo "getAverage = " . $avgBuff->getAverage() . "\n";
    echo "getAverageForever = " . $avgBuff->getAverageForever() . "\n";
    echo "getUpperQuarterAverage = " . $avgBuff->getUpperQuarterAverage() . "\n";
    echo "getLowerQuarterAverage = " . $avgBuff->getLowerQuarterAverage() . "\n";

    usleep(1000);
}

$avgBuff->clear();

?>
