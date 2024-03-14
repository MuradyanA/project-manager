<?php

namespace App\Services;


use App\Services\Exceptions\ServiceException;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Type\VoidType;

class BaseService
{

    protected const ENTITY_MODEL_CLASS = '';

    protected const VALIDATION_RULES = [];

    protected array $fields;

    // protected callable $additionalValidationHook; 

    public function loadFieldValues(array $fields): static
    {
        $this->fields = $fields;
        return $this;
    }

    protected function validate($actionName)
    {
        // dd($this->fields, static::getValidationRules($actionName));
        $validator = Validator::make($this->fields, static::getValidationRules($actionName));
        $additionalValidationCallbacks = $this->getAdditionalValidationCallbacks($actionName);
        if (count($additionalValidationCallbacks) > 0) {
            $validator->after($additionalValidationCallbacks);
        }
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw ServiceException::ValidationError(is_array($errors) ? implode(' ', $errors) : $errors);
        }
        $this->fields = $validator->validated();
    }

    public static function getValidationRules($actionName)
    {
        $validationRules = static::VALIDATION_RULES;
        if ($actionName == 'update') {
            $validationRules['id'] = ['required'];
            foreach ($validationRules as $key => $value) {
                array_unshift($value, 'sometimes');
                $validationRules[$key] = $value;
            }
        } elseif ($actionName == 'delete') {
            $validationRules = ['id' => 'required'];
        }

        return $validationRules;
    }

    protected function beforeAction($actionName)
    {
    }

    protected function action($actionName): mixed
    {
        switch ($actionName) {
            case 'create':
                $model = (static::ENTITY_MODEL_CLASS)::create($this->fields);
                return $model;
            case 'update':
                $model = (static::ENTITY_MODEL_CLASS)::findOrFail($this->fields['id']);
                foreach ($this->fields as $key => $value) {
                    $model->{$key} = $value;
                }
                $model->save();
                return $model;
            case 'delete':
                $model = (static::ENTITY_MODEL_CLASS)::findOrFail($this->fields['id']);
                $model->delete();
                break;
        }
        return null;
    }

    protected function afterAction($actionName, $model = null): void
    {
    }

    protected function getAdditionalValidationCallbacks($actionName): array
    {
        return [];
    }

    public function create()
    {
        $this->validate('create');
        $this->beforeAction('create');
        $model = $this->action('create');
        $this->afterAction('create', $model);
        return $model;
    }
    public function update()
    {
        $this->validate('update');
        $this->beforeAction('update');
        $model = $this->action('update');
        $this->afterAction('update', $model);
        return $model;
    }
    public function delete()
    {
        $this->validate('delete');
        $this->beforeAction('delete');
        $this->action('delete');
        $this->afterAction('delete');
    }
}
