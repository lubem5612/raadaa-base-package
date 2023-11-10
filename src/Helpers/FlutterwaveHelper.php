<?php


namespace RaadaaPartners\RaadaaBase\Helpers;


use Illuminate\Support\Facades\Http;
use RaadaaPartners\RaadaaBase\Actions\Action;

class FlutterwaveHelper extends Action
{
    private $request, $validatedData, $response;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validateRequest();
        $this->initiateTransfer();
        return $this->buildResponse();
    }

    private function initiateTransfer()
    {
        $url = config('raadaa.flutterwave.base_url').$this->validatedData['url'];

        $builder = Http::withHeaders([
            'Authorization' => 'Bearer '.config('raadaa.flutterwave.secret_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withoutVerifying();

        switch ($this->validatedData['method']) {
            case 'GET' : {
                $this->response = $builder->get($url)->json();
                break;
            }
            case 'POST' : {
                $this->response = $builder->post($url, $this->validatedData['data'])->json();
                break;
            }
            case 'PUT' : {
                $this->response = $builder->put($url, $this->validatedData['data'])->json();
                break;
            }
            case 'PATCH' : {
                $this->response = $builder->patch($url, $this->validatedData['data'])->json();
                break;
            }
            default :
                abort(403, 'method not allowed');

        }
        return $this;
    }

    private function buildResponse()
    {
        if ($this->response['status'] == "success") {
            return [
                "success" => true,
                "message" => $this->response['message'],
                "data" => $this->response['data'],
            ];
        }else {
            return [
                "success" => false,
                "message" => isset($this->response['message'])? isset($this->response['message']) : 'error in making api call',
                "data" => isset($this->response['data'])? isset($this->response['data']) : [],
            ];
        }
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'method' => 'required|string|in:POST,GET,PUT,PATCH,DELETE',
            'url' => 'required|string',
            'data' => 'required_unless:method,GET|array',
        ]);
    }
}