<?php


namespace RaadaaPartners\RaadaaBase\Actions\Flutterwave;


use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\FlutterwaveHelper;
use RaadaaPartners\RaadaaBase\Helpers\SessionHelper;


class InitiateBankPayment extends Action
{
    use SessionHelper;
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validateRequest();
        $this->setCurrency();
        $this->setEmail();
        $this->setReference();
        $this->setFullName();
        $this->setPhoneNumber();
        return $this->initiateTransfer();
    }

    private function initiateTransfer()
    {
        return (new FlutterwaveHelper([
            "method" => 'POST',
            "url" => '/charges?type=mono',
            "data" => $this->validatedData
        ]))->execute();
    }

    private function setCurrency()
    {
        if (!array_key_exists('currency', $this->validatedData)) {
            $this->validatedData['currency'] = "NGN";
        }
        return $this;
    }

    private function setReference()
    {
        $this->validatedData['tx_ref'] = $this->generateReference();
        return $this;
    }

    private function setEmail()
    {
        if (!array_key_exists('email', $this->validatedData)) {
            if (auth()->check()) $this->validatedData['email'] = auth()->user()->email;
        }
        return $this;
    }

    private function setFullName()
    {
        if (!array_key_exists('email', $this->validatedData)) {
            if (auth()->check()) $this->validatedData['fullname'] = auth()->user()->name;
        }
        return $this;
    }

    private function setPhoneNumber()
    {
        if (!array_key_exists('phone_number', $this->validatedData)) {
            if (auth()->check()) $this->validatedData['phone_number'] = auth()->user()->phone;
        }
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validatedData = $this->validate($this->request, [
            "amount" =>"required|numeric|gt:0",
            "account_bank" => "required|size:3",
            "account_number" => "required",
            "currency" => "nullable",
            "email" => "nullable",
            "phone_number" => "nullable",
            "fullname" => "required|string"
        ]);
        return $this;
    }
}