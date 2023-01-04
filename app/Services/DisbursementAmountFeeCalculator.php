<?php

namespace App\Services;

/**
 * Class AgencyFeeCalculator.
 */
class AgencyFeeCalculator
{
    private $fee,$transactionFee;

    public function disbursementFeeAmount($amount){
        if($amount <= 25000000)
        {
            $fee = 50000;
        }
        if($amount > 25000000 || $amount <= 3999999 )
        {
            $fee = 0.02 * $amount;
        }
        if($amount > 4000000 || $amount <= 10999999 )
        {
            $fee = 0.015 * $amount;
        }
        if($amount >= 11000000)
        {
            $fee = 0.01 * $amount;
        }
        return $fee;
    }

    public function getTransactionFee($transactionQuantity){
        if ($transactionQuantity = 10 || $transactionQuantity< 3500){
            $transactionFee = 50000;
        }
        if($transactionQuantity > 3500){
            $transactionFee = $transactionQuantity * 15;
        }
         return $transactionFee;
    }


}
