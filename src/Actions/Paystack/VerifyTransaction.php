<?php


namespace RaadaaPartners\RaadaaBase\Actions\Paystack;


use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\PaystackHelper;

class VerifyTransaction extends Action
{
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validateRequest();
        return $this->getTransactionReference();
    }

    private function getTransactionReference()
    {
        $reference = $this->validatedData['reference'];
        $response = (new PaystackHelper([
            'url' => "/transaction/verify/$reference",
            'method' => 'GET'
        ]))->execute();
        return json_decode($response->getContent(), true);
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'reference' => 'required|string|min:8'
        ]);
    }
}