<?php

namespace Current;

use Exception;

class ApiServices
{

    const BIN_LIST_API = 'https://lookup.binlist.net/';
    const RATE_API = 'https://api.apilayer.com/exchangerates_data/latest?base=EUR';
    const API_KEY = 'Key';

    /**
     * @throws Exception
     */
    public function getCardInfo(string $bin): \stdClass
    {
        try {
            return $this->request(self::BIN_LIST_API . $bin);
        } catch (Exception $e) {
            throw new Exception(sprintf('Can\'t recognize card with BIN %s', $bin));
        }
    }

    /**
     * @throws Exception
     */
    public function getRate(string $currency): float
    {
        $currentCurrencies = $this->request(self::RATE_API, true);
        if ($currentCurrencies->success) {
            return (float)$currentCurrencies->rates->$currency;
        } else {
            throw new Exception($currentCurrencies->error->info);
        }
    }

    private function request(string $url, bool $headerKey = false)
    {
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                "Content-Type: text/plain"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ];
        if ($headerKey) {
            $options[CURLOPT_HTTPHEADER][] = "apikey: " . self::API_KEY;
        }
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        curl_close($curl);
        return @json_decode($response);
    }
}

class OtherServices
{
    const PATH = getcwd() . '/';
    const EU_COUNTRIES_LIST = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',];

    /**
     * @throws Exception
     */
    public function getFileData(string $fileName): array
    {
        if (!file_exists(self::PATH . $fileName)) {
            throw new Exception(sprintf('File - %s - not found', self::PATH . $fileName));
        }
        $fileData = file_get_contents(self::PATH . $fileName);

        return explode("\n", $fileData);
    }

    public function checkIsCountryInEU(string $country): bool
    {
        if (in_array($country, self::EU_COUNTRIES_LIST)) {
            return true;
        }

        return false;
    }
}
