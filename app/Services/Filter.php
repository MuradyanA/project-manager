<?php


namespace App\Services;

use App\Exceptions\FilterException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Livewire\Attributes\Url;
use DateTime;

class Filter
{
    public array $fields = [['', '']];

    public array $headers = [];

    public int $step = 1;

    public array $currentField = [
        'name' => '',
        'type' => '',
        'operators' => [],
    ];

    public array $conditionsFormFields = [
        'operator' => '',
        'value' => '',
        'andOr' => ''
    ];

    public array $filterConditions = [];

    public array $operators = [
        'common' => [
            'Equal' => '=', 'Not Equal' => '!=', 'Greater Than' => '>', 'Less Than' => '<',
            'Greater Than or Equal' =>  '=>', 'Less Than or Equal' => '=<'
        ],
        'text' => ['Contains' => 'like', 'Starts With' => 'like', 'Ends With' => 'like', 'Exact Match' => '=']
    ];

    public function __construct(array $stringFields = [],   array $numericFields = [],  array $dateFields = [],  array $dateTimeFields = [],  array $timeFields = [], public string $f = "f")
    {
        foreach ($stringFields as $strF) {
            $this->fields[] = [$strF, $this->splitCamelCase($strF), 'text'];
        }

        foreach ($numericFields as $numF) {
            $this->fields[] = [$numF, $this->splitCamelCase($numF), 'number'];
        }

        foreach ($dateFields as $dateF) {
            $this->fields[] = [$dateF, $this->splitCamelCase($dateF), 'date'];
        }

        foreach ($dateTimeFields as $dateTF) {
            $this->fields[] = [$dateTF, $this->splitCamelCase($dateTF), 'datetime-local'];
        }

        foreach ($timeFields as $timeF) {
            $this->fields[] = [$timeF, $this->splitCamelCase($timeF), 'time'];
        }
    }

    public function splitCamelCase($input)
    {
        $input =  preg_replace_callback('/([a-zA-Z])(?=[A-Z])/', function ($matches) {
            return $matches[1] . ' ';
        }, ucfirst($input));

        $arrInp = explode('_', $input);
        $str = '';
        foreach ($arrInp as $substr) {
            $str .= ucfirst($substr) . ' ';
        }
        return $str;
    }

    public function addConditions()
    {
        if (empty($this->conditionsFormFields['value'])) throw FilterException::FilterError('Please provide value for the condition.');
        
        $andOr = count($this->filterConditions) == 0 ? 'and' : $this->conditionsFormFields['andOr'];
        $this->conditionsFormFields['andOr'] = $andOr;

        switch ($this->conditionsFormFields['operator']) {
            case 'Contains':
                $value = $this->conditionsFormFields['value'];
                $operator = 'Contains';
                break;
            case 'Starts With':
                $value = $this->conditionsFormFields['value'];
                $operator = 'Starts With';
                break;
            case 'Ends With':
                $value = $this->conditionsFormFields['value'];
                $operator = 'Ends With';
                break;
            case 'Exact Match':
                $value = $this->conditionsFormFields['value'];
                $operator = 'Exact Match';
                break;
            default:
                $value = $this->conditionsFormFields['value'];
                $operator = $this->conditionsFormFields['operator'];
                break;
        }
        $this->filterConditions[] = [$this->currentField['name'], $operator, $value, $andOr];
    }

    public function deleteCondition($index)
    {
        unset($this->filterConditions[$index]);
    }

    public function getFieldData()
    {
        if ($this->currentField != '') {
            $field = Arr::flatten(array_filter($this->fields, fn ($elem) => $elem[0] == $this->currentField['name']));
            if (count($field) == 3) $fieldType =  $field[2];
        }
        if (!isset($fieldType)) return [];

        $this->currentField['type'] = $fieldType;
        switch ($fieldType) {
            case 'date':
            case 'datetime-local':
            case 'time':
            case 'number':
                $this->currentField['operators'] =  array_keys($this->operators['common']);
                break;
            case 'text':
                $this->currentField['operators'] = array_keys($this->operators['text']);
                break;
        }
        $this->conditionsFormFields['operator'] = $this->currentField['operators'][0];
        $this->step = 2;
    }


    public function applyFilter(Builder $builder)
    {
        foreach ($this->filterConditions as $condition) {
            // dd($condition);
            $operator = match ($condition[1]) {
                'Contains', 'Starts With', 'Ends With' => 'like',
                'Exact Match', 'Equal' => '=',
                'Greater Than' => '>',
                'Less Than' => '<',
                'Greater Than or Equal' =>  '>=',
                'Less Than or Equal' => '<=',
                'Not Equal' => "!="
            };
            $value = '';
            switch ($condition[1]) {
                case 'Contains':
                    $value = '%' . $condition[2] . '%';
                    break;
                case 'Starts With':
                    $value = $condition[2] . '%';
                    break;
                case 'Ends With':
                    $value = '%' . $condition[2];
                    break;
                default:
                    $value = $condition[2];
                    break;
            }

            if ($condition[3] == 'and') {
                $builder->where($condition[0], $operator, $value);
            } else {
                $builder->orWhere($condition[0], $operator, $value);
            }
        }
        // dump($this->filterConditions);
    }

    public function hydrate($target)
    {
        $this->fields = $target['fields'];
        $this->filterConditions = $target['filterConditions'];
        $this->step = $target['step'];
        $this->currentField = $target['currentField'];
        $this->conditionsFormFields = $target['conditionsFormFields'];
    }
    public function dehydrate()
    {
        return [
            'fields' => $this->fields,
            'filterConditions' => $this->filterConditions,
            'step' => $this->step,
            'currentField' => $this->currentField,
            'conditionsFormFields' => $this->conditionsFormFields,
        ];
    }
}
