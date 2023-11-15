<?php


namespace RaadaaPartners\RaadaaBase\Actions\Resource;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use RaadaaPartners\RaadaaBase\Helpers\ResponseHelper;

class SearchResource
{
    use ResponseHelper, \SearchTerm;
    
    private $request;
    private $route;
    private $relationships = [];
    private $model;
    private $routeConfig = [];
    private $queryBuilder;
    private $searchParam;
    private $perPage = 10;
    private $startAt = '';
    private $endAt = '';
    private $resources = [];

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->routeConfig = config('raadaa.endpoints.routes');
    }

    public function execute()
    {
        try {
            return $this
                ->validateAndSetDefaults()
                ->setModel()
                ->setModelRelationship()
                ->searchTerms()
                ->filterWithTimeStamps()
                ->filterWithOrder()
                ->getResources()
                ->sendSuccess($this->resources, 'resources returned');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setModel()
    {
        abort_if(!array_key_exists('model', $this->route), 401, 'model not configured');
        $this->model = $this->route['model'];
        $this->queryBuilder = $this->model::query();
        return $this;
    }

    private function filterWithTimeStamps()
    {
        if ($this->startAt!="null" || $this->endAt!="null" || $this->startAt!=null || $this->endAt!=null) {
            if (isset($this->startAt) && isset($this->endAt)) {
                $start = Carbon::parse($this->startAt);
                $end = Carbon::parse($this->endAt);
                $this->queryBuilder = $this->queryBuilder
                    ->whereBetween('created_at', [$start, $end]);
            }
        }
        return $this;
    }

    private function getResources()
    {
        if (isset($this->perPage)) {
            $this->resources = $this->queryBuilder->paginate($this->perPage);
        }else
            $this->resources = $this->queryBuilder->get();
        return $this;
    }

    private function filterWithOrder()
    {
        if (array_key_exists('order', $this->route)) {
            if (array_key_exists('column', $this->route['order']) && array_key_exists('pattern', $this->route['order'])) {
                $this->queryBuilder = $this->queryBuilder->orderBy($this->route['order']['column'], $this->route['order']['pattern']);
            }
        }else {
            $this->queryBuilder = $this->queryBuilder->orderBy('created_at', 'desc');
        }
        return $this;
    }

    private function setModelRelationship()
    {
        if (array_key_exists('relationships', $this->route) && count($this->route['relationships']) > 0) {
            $this->relationships = $this->route['relationships'];
            $this->queryBuilder = $this->queryBuilder->with($this->relationships);
        }
        return $this;
    }

    private function validateAndSetDefaults()
    {
        abort_if(!array_key_exists($this->request['endpoint'], $this->routeConfig), 401, 'endpoint not found');
        $this->route = $this->routeConfig[$this->request['endpoint']];
        $this->startAt = request()->query('start');
        $this->endAt = request()->query('end');
        $this->searchParam = request()->query("search");
        $this->perPage = request()->query("per_page");
        return $this;
    }
}