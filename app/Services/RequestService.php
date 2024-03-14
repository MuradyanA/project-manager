<?php

namespace App\Services;

use App\Models\Request;

class RequestService extends BaseService
{

    protected const VALIDATION_RULES = [
        'projectId' => 'required|exists:projects,id',
        'title' => 'required',
        'request' => 'required',
    ];
    // protected $likesDislikes = [];

    protected const ENTITY_MODEL_CLASS = Request::class;


    public function beforeAction($actionName)
    {
        switch ($actionName) {
            case 'create':
                $this->fields['requesterId'] = auth()->id();
                break;
        }
    }
}