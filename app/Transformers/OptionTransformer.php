<?php

namespace App\Transformers;

use App\Models\Option;
use League\Fractal\TransformerAbstract;

class OptionTransformer extends TransformerAbstract
{
    /**
     * @param \App\Option $option
     * @return array
     */
    public function transform(Option $option)
    {
        return [
            'id' => (int) $option->id,
            'name' => $option->name,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at,
        ];
    }
}