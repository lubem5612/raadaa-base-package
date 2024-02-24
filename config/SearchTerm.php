<?php


use Illuminate\Database\Eloquent\Builder;

trait SearchTerm
{
    private function searchTerms()
    {
        switch ($this->request['endpoint'])
        {
            case "users": {
                //
                break;
            }

            default:
                return $this;
        }
        return $this;
    }
}