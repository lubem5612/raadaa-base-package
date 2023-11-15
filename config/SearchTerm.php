<?php


use Illuminate\Database\Eloquent\Builder;

trait SearchTerm
{
    private function searchTerms()
    {
        switch ($this->request['endpoint'])
        {
            case "countries": {
                $this->queryBuilder->where('name', 'like', "%$this->searchParam%")
                    ->orWhere('code', 'like', "%$this->searchParam%")
                    ->orWhere('continent', 'like', "%$this->searchParam%");
                break;
            }

            case "states": {
                $country = request()->query('country_id');
                if (isset($country)) {
                    $this->queryBuilder->where('country_id', $country);
                }
                $this->queryBuilder->where('name', 'like', "%$this->searchParam%")
                    ->orWhere('capital', 'like', "%$this->searchParam%");
                break;
            }

            case "lgas": {
                $search = $this->searchParam;
                $state = request()->query('state_id');
                if (isset($state)) {
                    $this->queryBuilder->where('state_id', $state);
                }
                $this->queryBuilder->where(function(Builder $q) use($search) {
                    $q->where("name", "like", "%$search%")
                        ->orWhereHas("state", function ($q2) use($search) {
                            $q2->where("name", "like", "%$search%")
                                ->orWhere("capital", "like", "%$search%");
                        });
                });
                break;
            }

            case "failed-transactions": {
                $search = $this->searchParam;
                $user = request()->query('user_id');
                if (isset($user)) {
                    $this->queryBuilder->where('user_id', $user);
                }
                $this->queryBuilder->where(function (Builder $query) use($search) {
                    $query->whereHas('user', function (Builder $builder) use($search) {
                        $builder->where('first_name', "%$search%")
                            ->orWhere('last_name', "%$search%")
                            ->orWhere('email', "%$search%")
                            ->orWhere('phone', "%$search%");
                    });
                });

            }

            default:
                return $this;
        }
        return $this;
    }
}