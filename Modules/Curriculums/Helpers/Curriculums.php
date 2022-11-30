<?php

if (!function_exists("get_curriculum_allowed_order_by_fields")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_curriculum_allowed_order_by_fields(bool $implode = false)
    {
        $allowed_order_fields = config("curriculums.order_by.curriculums", []);
        return $implode ? implode(",", $allowed_order_fields) : $allowed_order_fields;
    }
}

if (!function_exists("get_curriculum_allowed_filter_fields")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_curriculum_allowed_filter_fields(bool $implode = false)
    {
        $allowed_filter_fields = config("curriculums.filters.curriculums", []);
        return $implode ? implode(",", $allowed_filter_fields) : $allowed_filter_fields;
    }
}
