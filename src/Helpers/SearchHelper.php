<?php


namespace RaadaaPartners\RaadaaBase\Helpers;


use Carbon\Carbon;

trait SearchHelper
{
    use ResponseHelper;
    private $output;
    private $queryBuilder;
    private array $relationshipArray;
    private string $searchParam;
    private ?int $perPage;
    private ?string $startAt;
    private ?string $endAt;
    private $id;
    private ?Request $httpRequest;
    private ?int $page;
    private $model;
    private ?string $table;

    public function __construct($model, array $relationshipArray=[], $id=null)
    {
        $this->relationshipArray = $relationshipArray;
        $this->model = $model;
        $this->httpRequest = request();
        $this->searchParam = request()->query("search");
        $this->perPage = request()->query("per_page");
        $this->endAt = request()->query("end");
        $this->startAt = request()->query("start");
        $this->page = request()->query('page');
        $this->id = $id;
    }

    public function execute()
    {
        try {
            $this->initQueryBuilder();
            $this->modelHasRelationship();
            $this->handleTimeStampQuery();
            $this->searchTerms();
            $this->groupedBy();
            $this->handlePagination();
            $this->querySingleResource();
            return $this->sendSuccess($this->output, 'query returned Ok');

        }catch (\Exception $ex) {
            return $this->sendServerError($ex);
        }
    }

    protected function initQueryBuilder()
    {
        $this->queryBuilder = $this->model::query();
    }

    private function handleTimeStampQuery()
    {
        if (is_null($this->id) && !isset($this->id)) {
            if ($this->startAt!="null" || $this->endAt!="null" || $this->startAt!=null || $this->endAt!=null) {
                if (isset($this->startAt) && isset($this->endAt)) {
                    $start = Carbon::parse($this->startAt);
                    $end = Carbon::parse($this->endAt);
                    $this->queryBuilder = $this->queryBuilder
                        ->whereBetween('created_at', [$start, $end]);
                }
            }
        }
        return $this;
    }

    private function modelHasRelationship()
    {
        if (count($this->relationshipArray)) {
            $this->queryBuilder = $this->queryBuilder->with($this->relationshipArray);
        }
        return $this;
    }

    private function querySingleResource()
    {
        if (!is_null($this->id) || isset($this->id)) {
            $this->output = $this->queryBuilder->find($this->id);
        }
        return $this;
    }

    protected function handlePagination()
    {
        if (is_null($this->id) && !isset($this->id)) {
            if (isset($this->page)) {
                $perPage = isset($this->perPage)? $this->perPage : 10;
                $this->output = $this->queryBuilder->paginate($perPage);
            }else
                $this->output = $this->queryBuilder->get();
        }
        return $this;
    }

    protected function searchTerms() : self
    {
        //
        return $this;
    }

    protected function groupedBy() : self
    {
        if (is_null($this->id) && !isset($this->id)) {
            $this->queryBuilder = $this->queryBuilder->orderBy("created_at", "DESC");
        }
        return $this;
    }
}