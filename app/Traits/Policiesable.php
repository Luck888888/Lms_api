<?php

/**
 * Developed Maxym Rudenko
 * Email: rudenko.programmer@gmail.com
 * Date: 29.12.2021
 */

namespace App\Traits;

use Illuminate\Support\Facades\Gate;

trait Policiesable
{

    /**
     * @param $array
     */
    public function registerPolicies($array)
    {
        foreach ($array as $key => $value) {
            Gate::policy($key, $value);
        }
    }
}
