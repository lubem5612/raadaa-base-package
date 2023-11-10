<?php


namespace RaadaaPartners\RaadaaBase\Actions;



use RaadaaPartners\RaadaaBase\Helpers\ResponseHelper;
use RaadaaPartners\RaadaaBase\Helpers\ValidationHelper;

class Action
{
    use ValidationHelper, ResponseHelper;

    public function execute()
    {
        try {
            return $this->handle();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    public function handle()
    {
        //override with content here
        return $this;
    }
}