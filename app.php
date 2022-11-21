<?php
require_once __DIR__.'/Services.php';

use Current\ApiServices;
use Current\OtherServices;

$api = new ApiServices();
$services = new OtherServices();

try {
    $fileName = 'input.txt';
    $fileData = $services->getFileData($fileName);
    foreach ($fileData as $datum) {
        if (!$datum) {
            continue;
        }
        $transaction = json_decode($datum);
        $cardInfo = $api->getCardInfo($transaction->bin);
        $isEu = $services->checkIsCountryInEU($cardInfo->country->alpha2);
        $rate = $api->getRate($transaction->currency);
        if ($transaction->currency != 'EUR' && $rate > 0) {
            $transaction->amount /= $rate;
        }
        echo (string)bcmul($transaction->amount, $isEu ? 0.01 : 0.02, 2);
        print "\n";
    }
} catch (\Exception $e) {
    echo 'ERROR - ' . $e->getMessage(). "\n";
}
