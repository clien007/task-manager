<?php

namespace App\Services;

use App\Models\Status;

class StatusService
{

    public function getAllStatus(){
        return Status::all();
    }

    public function findStatusById($statusId){
        return Status::findOrFail($statusId);
    }

}
