<?php


namespace RaadaaPartners\RaadaaBase\Actions\Flutterwave;


use Illuminate\Encryption\Encrypter;
use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\FlutterwaveHelper;
use RaadaaPartners\RaadaaBase\Helpers\SessionHelper;


class InitiateCardPayment extends Action
{
    use SessionHelper;
    private $request, $validatedData, $chargeCard, $encrytedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validateRequest();
        $this->setReference();
        $this->setEmail();
        $this->setUserName();
        $this->setCurrency();
        $this->setRedirectUrl();
        $this->encryptPayloadData();
        return $this->chargeCard();
        return $this->sendSuccess($this->chargeCard, 'card transaction initiated successfully');
    }

    private function encryptPayloadData()
    {
        $encrypter = new Encrypter(config('raadaa.flutterwave.encryption_key'), 'AES-256-CBC');
        $this->encrytedData = $encrypter->encrypt( $this->validatedData );
//        $decrypted = $newEncrypter->decrypt( $encrypted );
    }

    private function chargeCard()
    {
        return $this->chargeCard = (new FlutterwaveHelper([
            'method' => 'POST',
            'url' => '/charges?type=card',
            'data' => $this->encrytedData,
        ]))->execute();
    }

    private function setCurrency()
    {
        if (!array_key_exists('currency', $this->validatedData)) {
            $this->validatedData['currency'] = "NGN";
        }
    }

    private function setReference()
    {
        $this->validatedData['tx_ref'] = $this->generateReference();
    }

    private function setRedirectUrl()
    {
        $this->validatedData['redirect_url'] = route('flutterwave.redirect', ['id' => auth()->id()]);
    }

    private function setEmail()
    {
        if (!array_key_exists('email', $this->validatedData)) {
            $this->validatedData['email'] = auth()->user()->email;
        }
    }

    private function setUserName()
    {
        if (!array_key_exists('fullname', $this->validatedData)) {
            $this->validatedData['fullname'] = auth()->user()->name;
        }
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            "card_number" => "required|numeric",
            "cvv" => "required|string|size:3",
            "expiry_month" => "required|string|size:2",
            "expiry_year" => "required|string|size:2",
            "amount" => "required|numeric|gt:0",
            "currency" => "nullable",
            "fullname" => "nullable|string",
            "email" => "nullable",
        ]);
    }
}
