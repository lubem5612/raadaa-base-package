<?php


namespace RaadaaPartners\RaadaaBase\Actions\Paystack;


use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\PaystackHelper;

class BankList extends Action
{

    public function __construct()
    {
    }

    public function handle()
    {
        $response = (new PaystackHelper([
            'method' => 'GET',
            'url' => '/bank',
        ]))->execute();
        return json_decode($response->getContent(), true);
    }
}