<?php

if (!function_exists("get_user_allowed_roles")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_user_allowed_roles($implode = false)
    {
        $allowed_roles = config("users.allowed_roles", []);
        return $implode ? implode(",", $allowed_roles) : $allowed_roles;
    }
}

if (!function_exists("get_user_allowed_order_by_fields")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_user_allowed_order_by_fields($implode = false)
    {
        $allowed_order_fields = config("users.order_by.users", []);
        return $implode ? implode(",", $allowed_order_fields) : $allowed_order_fields;
    }
}

if (!function_exists("get_user_allowed_filter_fields")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_user_allowed_filter_fields(bool $implode = false)
    {
        $allowed_filter_fields = config("users.filters.users", []);
        return $implode ? implode(",", $allowed_filter_fields) : $allowed_filter_fields;
    }
}

if (!function_exists("get_user_allowed_statuses")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_user_allowed_statuses($implode = false)
    {
        $allowed_statuses = config("users.allowed_statuses", []);
        return $implode ? implode(",", $allowed_statuses) : $allowed_statuses;
    }
}

if (!function_exists("get_user_allowed_sex")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_user_allowed_sex($implode = false)
    {
        $allowed_sex = config("users.allowed_sex", []);
        return $implode ? implode(",", $allowed_sex) : $allowed_sex;
    }
}

if (!function_exists("get_religions")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_religions($implode = false)
    {
        $allowed_religions = config("users.religions", []);
        return $implode ? implode(",", $allowed_religions) : $allowed_religions;
    }
}

if (!function_exists("get_religion_types")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_religion_types($implode = false)
    {
        $allowed_religion_types = config("users.religion_types", []);
        return $implode ? implode(",", $allowed_religion_types) : $allowed_religion_types;
    }
}

if (!function_exists("get_allowed_file_format")) {
    /**
     * @param false $implode
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    function get_allowed_file_format($implode = false)
    {
        $allowed_file_format = config("users.file_format", []);
        return $implode ? implode(",", $allowed_file_format) : $allowed_file_format;
    }
}
