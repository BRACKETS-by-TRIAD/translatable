<?php namespace Brackets\Translatable;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class TranslatableFormRequest extends FormRequest {

    /**
     * Get all defined locales as Collection
     *
     * @return Collection
     */
    public function getLocales() : Collection
    {
        return collect((array) Config::get('translatable.locales'))->map(function($val, $key){
            return is_array($val) ? $key : $val;
        });

    }

    /**
     * Define what locales should be required in store/update requests
     *
     * By default all locales are required
     *
     * @return Collection
     */
    public function defineRequiredLocales() : Collection
    {
        return $this->getLocales();
    }

    private function prepareLocalesForRules()
    {
        $locales = $this->getLocales();
        $required = $this->defineRequiredLocales();

        return $locales->map(function($locale) use ($locales, $required) {
            return [
                'locale' => $locale,
                'required' => $required->contains($locale)
            ];
        });
    }

    public function rules()
    {
        $standardRules = collect($this->untranslatableRules());

        $rules = $this->prepareLocalesForRules()->flatMap(function($locale){
            return collect($this->translatableRules($locale['locale']))->mapWithKeys(function($rule, $ruleKey) use ($locale) {
                if (!$locale['required']) {
                    if (is_array($rule) && ($key = array_search('required', $rule)) !== false) {
                        unset($rule[$key]);
                        array_push($rule, 'nullable');
                    } else if(is_string($rule)) {
                        $rule = str_replace('required', 'nullable', $rule);
                    }
                }
                return [$ruleKey.'.'.$locale['locale'] => $rule];
            });
        })->merge($standardRules);

        return $rules->toArray();
    }

    public function untranslatableRules() {
        return [];
    }

    public function translatableRules($locale) {
        return [];
    }

}