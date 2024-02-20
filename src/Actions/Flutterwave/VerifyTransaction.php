<?php


namespace RaadaaPartners\RaadaaBase\Actions\Flutterwave;


use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\FlutterwaveHelper;

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
        return $this->queryTransactionStatus();
    }

    private function queryTransactionStatus()
    {
        $response = (new FlutterwaveHelper([
            'method' => 'GET',
            'url' => '/transactions/verify_by_reference?tx_ref='.$this->validatedData['tx_ref']
        ]))->execute();

        if (!$response['success']) {
            abort(404, 'unable to verify transaction');
        }

        return $response;
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'tx_ref' => 'required|string|min:15',
            'description' => 'required|string',
        ]);
    }
}