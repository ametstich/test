<?php

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    /**
     * @dataProvider  urlProvider
     */
    public function testApp(string $fileData, string $result)
    {
        file_put_contents(getcwd().'/../input.txt', $fileData);
        exec('php app/console', $output);

        $this->assertEquals($output, [$result]);
    }

    public function urlProvider(): iterable
    {
        $testData = [
            ['{"bin":"45717360","amount":"100.00","currency":"EUR"}', "1.00"],
            ['{"bin":"516793","amount":"50.00","currency":"USD"}', "0.48"],
            ['{"bin":"45417360","amount":"10000.00","currency":"JPY"}', "1.37"],
            ['{"bin":"41417360","amount":"130.00","currency":"USD"}', "2.53"],
            ['{"bin":"4745030","amount":"2000.00","currency":"GBP"}', "46.15"],
            ['{"bin":"45717360","amount":"200.00","currency":"AED"}', "0.53"],
            ['{"bin":"516793","amount":"150.00","currency":"CNY"}', "0.20"],
            ['{"bin":"45417360","amount":"10500.00","currency":"ILS"}', "59.00"],
            ['{"bin":"41417360","amount":"131.00","currency":"MDL"}', "0.13"],
            ['{"bin":"4745030","amount":"2012.00","currency":"GEL"}', "14.57"]
        ];
        foreach ($testData as $datum) {
            yield [$datum[0], $datum[1]];
        }
    }
}
