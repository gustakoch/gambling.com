<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    public static function readRawFileAndConvertItToAnIterateArray()
    {
        if (!file_exists('affiliates.txt')) {
            return false;
        }

        $fopen = fopen('affiliates.txt', 'r');
        $data = [];

        while ($line = fgets($fopen)) {
            if (trim($line) != '') {
                $item = json_decode($line);

                $data[] = $item;
            }
        }

        return $data;
    }

    public function getAffiliatesWhoWillBeInvited()
    {
        $affiliates = [];
        $affiliatesData = $this->readRawFileAndConvertItToAnIterateArray();

        if (!$affiliatesData) {
            return $affiliates;
        }

        foreach ($affiliatesData as $affiliate) {
            $distance = $this->twoPointsOnEarth(
                env('DUBLIN_LATITUDE'),
                env('DUBLIN_LONGITUDE'),
                $affiliate->latitude,
                $affiliate->longitude
            );


            if ($distance <= 100) {
                $affiliate->distanceInKm = $distance;
                $affiliates[] = $affiliate;
            }
        }

        $affiliateId = array_column($affiliates, 'affiliate_id');
        array_multisort($affiliateId, SORT_ASC, $affiliates);

        return $affiliates;
    }

    public static function twoPointsOnEarth($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $longFrom = deg2rad($longitudeFrom);
        $longTo = deg2rad($longitudeTo);
        $latFrom = deg2rad($latitudeFrom);
        $latTo = deg2rad($latitudeTo);

        $dlong = $longTo - $longFrom;
        $dlati = $latTo - $latFrom;

        $value = pow(sin($dlati / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($dlong / 2), 2);
        $result = 2 * asin(sqrt($value));

        $radius = 6371; // Radius of Earth to calculate in kilometers

        return ($result * $radius);
    }
}
