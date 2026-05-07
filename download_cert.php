<?php
// Download fresh Mozilla CA certificate bundle
$url = 'https://curl.se/ca/cacert.pem';

$ctx = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

$data = file_get_contents($url, false, $ctx);

if ($data && strlen($data) > 1000) {
    $dest = 'C:\\xampp\\php\\extras\\ssl\\cacert.pem';
    
    // Create directory if it doesn't exist
    $dir = dirname($dest);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($dest, $data);
    echo "SUCCESS: Downloaded " . strlen($data) . " bytes to $dest\n";
} else {
    echo "FAILED to download CA bundle\n";
}
