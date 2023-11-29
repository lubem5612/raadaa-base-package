<?php


namespace RaadaaPartners\RaadaaBase\Actions\Flutterwave;


use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\FlutterwaveHelper;

class BankList extends Action
{
    private $request, $validatedData, $url;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validateRequest();
        $this->setCountry();
        return $this->getBanks();
    }

    private function getBanks()
    {
        return (new FlutterwaveHelper([
            'method' => 'GET',
            'url' => $this->url
        ]))->execute();
    }

    private function setCountry()
    {
        if (!array_key_exists('country', $this->validatedData)) {
            $this->validatedData['country'] = 'NG';
        }
        $this->url = '/banks/'.$this->validatedData['country'];
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validatedData = $this->validate($this->request, [
            "country" => "nullable|string|size:2",
        ]);
        return $this;
    }
}