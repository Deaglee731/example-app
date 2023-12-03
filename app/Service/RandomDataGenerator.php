<?php

namespace App\Service;

class RandomDataGenerator
{
    public function generateRandomData($count = 10000)
    {
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'account_id' => rand(1, 1000),
                'phone_number' => self::generateRandomPhoneNumber(),
            ];
        }

        return $data;
    }

    private static function generateRandomPhoneNumber()
    {
        $phonePrefix = '8'; // You may adjust the prefix based on your country code
        $phoneSuffix = mt_rand(1000000, 9999999);

        return $phonePrefix . $phoneSuffix;
    }
}
