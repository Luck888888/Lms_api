<?php

namespace Modules\Users\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Users\Entities\Profession;

class UsersProfessionService
{
    public function getAll()
    {
        $profession_cache = Cache::rememberForever('professions', function () {
            return Profession::orderBy('name')->get();
        });
        return $profession_cache;
    }

    /**
     * @param \Modules\Users\Dto\UserDto $user_dto
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function findOrCreate($profession)
    {
        if (is_numeric($profession)) {
            //TODO подумать нужна ли доп проверка, логично если используем сервис отдельно
            $profession = DB::table('professions')->find($profession);
            if ($profession) {
                return $profession->id;
            } else {
                return null;
            }
        }
        $profession_name = Str::lower(trim($profession));
        if (is_string($profession_name) && !empty($profession_name)) {
            $profession = Profession::where('name', $profession_name)->first();
            if ($profession) {
                return $profession->id;
            }
            $profession = Profession::create([
                'name' => $profession_name,
            ]);
            Cache::forget('professions');
            if ($profession) {
                Cache::forget('professions');
                return $profession->id;
            }
        }
    }
}
