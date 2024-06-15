<?php


namespace RaadaaPartners\RaadaaBase\Actions\Resource;


use RaadaaPartners\RaadaaBase\Actions\Action;

class DeleteResource extends Action
{
    private $request, $validatedInput;
    private $routeConfig, $route, $model;
    public function __construct(array $request)
    {
        $this->request = $request;
        $this->routeConfig = config('raadaa.endpoints.routes');
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setModel();
            $this->deleteResource();
            $this->sendSuccess(null, 'resource deleted successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setModel()
    {
        abort_if(!array_key_exists('model', $this->route), 401, 'model not configured');
        $this->model = $this->route['model'];
    }

    private function deleteResource()
    {
        $resource = $this->model::query()->find($this->validatedInput['id']);
        if (empty($resource)) abort(404, 'resource not found');
        $resource->delete();
    }

    private function validateRequest()
    {
        abort_unless(array_key_exists($this->request['endpoint'], $this->routeConfig), 401, 'endpoint not found');
        $this->route = $this->routeConfig[$this->request['endpoint']];

        $this->validatedInput = $this->validate($this->request, [
            "id" => "required",
            "endpoint" => "required|string",
        ]);
    }
}
