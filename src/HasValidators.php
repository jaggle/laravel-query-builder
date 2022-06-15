<?php

namespace Jjsty1e\LaravelQueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jjsty1e\LaravelQueryBuilder\Exceptions\ValidationFailedException;

trait HasValidators
{
    /**
     * @param Request|array $request
     * @param array $customRules
     * @return array
     * @throws ValidationFailedException
     */
    public function validate($request, array $customRules): array
    {
        $rules = array_combine(array_keys($customRules), array_column($customRules, 0));
        $attributes = array_combine(array_keys($customRules), array_column($customRules, 1));
        $validator = Validator::make(is_array($request) ? $request : $request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            throw new ValidationFailedException($validator->messages()->first());
        }

        $results = [];
        $missingValue = Str::random(10);

        foreach (array_keys($validator->getRules()) as $key) {
            $value = data_get($validator->getData(), $key, $missingValue);
            if ($value !== $missingValue) {
                Arr::set($results, $key, $value);
            }
        }

        return $results;
    }
}
