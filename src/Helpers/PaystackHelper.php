<?php


namespace RaadaaPartners\RaadaaBase\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class PaystackHelper
{
    use ValidationHelper, ResponseHelper;
    private $request;
    private $validatedData;
    private $response;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->generateReference()
                ->initiateTransfer()
                ->buildResponse();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function initiateTransfer()
    {
        $url = env('PAYSTACK_BASE_URL', 'https://api.paystack.co').$this->validatedData['url'];
        $builder = Http::withHeaders([
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY', null),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withoutVerifying();

        switch ($this->validatedData['method'])
        {
            case 'POST' : {
                $this->response = $builder->post($url, $this->validatedData['data'])->json();
                break;
            }
            case 'GET' : {
                $this->response = $builder->get($url)->json();
                break;
            }
            case 'PATCH' : {
                $this->response = $builder->patch($url, $this->validatedData['data'])->json();
            }
            case 'PUT' : {
                $this->response = $builder->put($url, $this->validatedData['data'])->json();
            }
            default : {
                abort(403, 'method not allowed');
            }
        }

        return $this;
    }

    private function buildResponse()
    {
        if ($this->response['status']) {
            if (array_key_exists('data', $this->response)) {
                return $this->sendSuccess($this->response['data'], $this->response['message']);
            }
            return $this->sendSuccess(null, $this->response['message']);
        }
        return $this->sendError('error in paystack api call');
    }

    private function generateReference()
    {
        $this->validatedData['data']['reference'] = 'transave-'.Carbon::now()->format('YmdHi').'-'.strtolower(Str::random(9));
        return $this;
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'url' => 'required|string',
            'data' => 'nullable|array',
            'method' => 'required|string|in:POST,GET,PUT,PATCH,DELETE',
        ]);

        return $this;
    }
}