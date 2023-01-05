<?php

namespace App\Services;

use App\Models\Tax;
/**
 * Class TaxCalculator.
 */
class TaxCalculator
{
    public function getTax($subTotal){
        $taxPercentage = Tax::first();
        return ($taxPercentage->tax_percentage??0.16) * $subTotal;
    }
}
