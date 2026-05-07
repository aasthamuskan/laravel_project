<?php
// Test with direct connection string (non-SRV)
$uri = "mongodb://aastha:hgN2wJ0ADoKDCeYQ@ac-nirojgg-shard-00-00.supuvy4.mongodb.net:27017,ac-nirojgg-shard-00-01.supuvy4.mongodb.net:27017,ac-nirojgg-shard-00-02.supuvy4.mongodb.net:27017/?ssl=true&replicaSet=atlas-ywn2wq-shard-0&authSource=admin&retryWrites=true&w=majority";

echo "Testing direct connection string...\n";

try {
    $client = new MongoDB\Driver\Manager($uri, [
        'serverSelectionTimeoutMS' => 15000,
        'connectTimeoutMS' => 10000,
    ]);
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $result = $client->executeCommand('admin', $command);
    echo "SUCCESS: MongoDB Atlas connected!\n";
    print_r($result->toArray());
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
