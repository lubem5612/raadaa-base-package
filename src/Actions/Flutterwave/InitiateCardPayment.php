<?php


namespace RaadaaPartners\RaadaaBase\Actions\Flutterwave;


use RaadaaPartners\RaadaaBase\Actions\Action;
use RaadaaPartners\RaadaaBase\Helpers\FlutterwaveHelper;
use RaadaaPartners\RaadaaBase\Helpers\SessionHelper;


class InitiateCardPayment extends Action
{
    use SessionHelper;
    private $request, $validatedData, $chargeCard;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this
            ->validateRequest()
            ->setReference()
            ->setEmail()
            ->setUserName()
            ->setCurrency()
            ->setRedirectUrl()
            ->chargeCard()
            ->sendSuccess($this->chargeCard, 'card transaction initiated successfully');
    }

    private function chargeCard()
    {
        $this->chargeCard = (new FlutterwaveHelper([
            'method' => 'POST',
            'url' => '/charges?type=card',
            'data' => $this->validatedData,
        ]))->execute();
        return $this;
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

    private function setRedirectUrl()
    {
        $this->validatedData['redirect_url'] = route('flutterwave.redirect', ['id' => auth()->id()]);
        return $this;
    }

    private function setEmail()
    {
        if (!array_key_exists('email', $this->validatedData)) {
            $this->validatedData['email'] = auth()->user()->email;
        }
        return $this;
    }

    private function setUserName()
    {
        if (!array_key_exists('fullname', $this->validatedData)) {
            $this->validatedData['fullname'] = auth()->user()->name;
        }
        return $this;
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
        return $this;
    }
}