<?php

namespace Modules\Users\Entities;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExportCollection implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function __construct($users_collection)
    {
        $this->users_collection = $users_collection;
    }

    public function collection()
    {
        return $this->users_collection;
    }

    public function headings(): array
    {
        return [
            __('users::users_export.heading.full_name'),
            __('users::users_export.heading.email'),
            __('users::users_export.heading.phone'),
            __('users::users_export.heading.passport'),
            __('users::users_export.heading.birth_date'),
            __('users::users_export.heading.address'),
            __('users::users_export.heading.sex'),
            __('users::users_export.heading.religion'),
            __('users::users_export.heading.religion_type'),
            __('users::users_export.heading.created_at'),
            __('users::users_export.heading.syllabuses'),
            __('users::users_export.heading.courses'),
        ];
    }

    public function map($user): array
    {
        $curriculum_string = $user->curriculums->map(function ($item) {
            return $item->name . " - " . optional($item->start_at)->format('Y-m-d')
                . " - "  . optional($item->end_at)->format('Y-m-d');
        })->implode(', ');


        return [
            $user->full_name,
            $user->email,
            $user->phone,
            $user->passport,
            $user->birth_date,
            $user->address,
            $user->sex,
            $user->religion,
            $user->religion_type,
            optional($user->created_at)->format('Y-m-d H:i'),
            $curriculum_string,
            $user->courses->implode('name', ', '),
        ];
    }
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            }
        ];
    }
}
