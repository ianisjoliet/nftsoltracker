<?php

namespace App\Manager;

class RoyaltiesManager
{
    public function CalcFloorPrice(string $buySell, float $rt, float $price): float
    {
        if ($buySell == "buy") {
            return $price + (($rt/100)*$price);
        } else if ($buySell == "sell") {
            $marge = ($rt * $price / 100);
            return ($price - $marge);
        }
        return 0;
    }
}