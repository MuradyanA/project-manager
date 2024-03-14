<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Customer;
use App\Models\Project;

class CustomerService extends BaseService
{
    public const VALIDATION_RULES = [
        'fullName' => ['required', 'string'],
        'email' => ['required','email'],
        'phoneNumber' => ['required', 'regex:/^(\+\d{11}|0\d{8})$/'],
    ];

    protected const ENTITY_MODEL_CLASS = Customer::class;

    protected function getAdditionalValidationCallbacks($actionName): array
    {
        if ($actionName == 'delete') {
            return [
                function ($validator) {
                    if (Project::where('customerId', $this->fields['id'])->exists()) {
                        $validator->errors()->add('additionalValidationError', "The selected customer(s) has associated projects and can't be deleted.");
                    }
                }
            ];
        }
        return [];
    }

    // #Override
    // public static function getValidationRules($actionName)
    // {
    //     $validationRules = parent::getValidationRules($actionName);
    //     if ($actionName == 'update') {
    //         $validationRules['fullName'] = ['sometimes', 'string'];
    //         $validationRules['email'] = ['sometimes', 'email'];
    //         $validationRules['phoneNumber'] = ['sometimes', 'regex:/^(\+\d{11}|0\d{8})$/'];
    //     }
    //     return $validationRules;
    // }
}
