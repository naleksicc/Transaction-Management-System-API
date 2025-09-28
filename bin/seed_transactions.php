<?php

$envFile = __DIR__ . '/../.env';
$csvPath = null;

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with($line, 'CSV_STORAGE_PATH=')) {
            $csvPath = trim(substr($line, strlen('CSV_STORAGE_PATH=')));
            break;
        }
    }
}

if (!$csvPath) {
    fwrite(STDERR, "CSV_STORAGE_PATH not set in .env\n");
    exit(1);
}

$csvPath = realpath(__DIR__ . '/../') . '/' . $csvPath;

$maxWait = 10;
$waited = 0;
while ((!file_exists($csvPath) || filesize($csvPath) === 0) && $waited < $maxWait) {
    sleep(1);
    $waited++;
}

if (!file_exists($csvPath) || filesize($csvPath) === 0) {
    fwrite(STDERR, "CSV file does not exist or is empty after waiting. Please run the application to initialize the file.\n");
    exit(1);
}

$dummyData = [
    "2025-03-01,7289-3445-1121,Maria Johnson,150.00,Settled",
    "2025-03-02,1122-3456-7890,John Smith,75.50,Pending",
    "2025-03-03,3344-5566-7788,Robert Chen,220.25,Settled",
    "2025-03-04,8899-0011-2233,Sarah Williams,310.75,Failed",
    "2025-03-04,9988-7766-5544,David Garcia,45.99,Pending",
    "2025-03-05,2233-4455-6677,Emily Taylor,500.00,Settled",
    "2025-03-06,1357-2468-9012,Michael Brown,99.95,Settled",
    "2025-03-07,5551-2345-6789,Jennifer Lee,175.25,Pending",
    "2025-03-08,7890-1234-5678,Thomas Wilson,62.50,Failed",
    "2025-03-08,1212-3434-5656,Jessica Martin,830.00,Settled",
    "2025-03-09,9876-5432-1011,Christopher Davis,124.75,Pending",
    "2025-03-10,4646-8282-1919,Amanda Robinson,300.50,Settled",
];

file_put_contents($csvPath, implode("\n", $dummyData) . "\n", FILE_APPEND);
echo "Seeded $csvPath with dummy data.\n";
