## Документация по модуль работы с опросами

Модуль предназначен для гибкого создания опросов для студентов.

### Опросник курса 

Опросник курса состоит из 2 частей.

- `course_part` - Опрос со списком общих вопросов по курсу;
- `teachers_part` - Опрос со списком вопросов по преподавателям. Опрос по преподавателям итеративный и проводится по всем преподавателям курса.

В конфигурационном файле [Modules/Courses/Config/config.php](../Modules/Courses/Config/config.php) прописано какой опрос за какую часть отвечает. В качестве ключа используется название опроса `course_part` в качестве значения используется `Survey slug` пример `course_survey`. Используем `slug` для того чтобы не привязываться к `id` а привязаться к уникальному имени.

```php
    "survey" => [
        "course_part"   => "course_survey",
        "teachers_part" => "teacher_survey"
    ],
```

### Управление доступами

- Опрос может проходить пользователь с ролью `student`, который принадлежит к курсу, опрос которого он проходит.
- Статистику опроса
  - пользователь с ролью `administrator` может просматривать статистику всех пользователей;
  - пользователь с ролью `teacher`, который принадлежит к курсу, опрос которого он проходит, может просматривать статистику общую по курсу и только свою по преподавателям;

### Заполнение данными опросника
- Опросник загружается с файла [Modules/Surveys/Resources/storage/test_surveys.json](../Modules/Surveys/Resources/storage/test_surveys.json)
- За загрузку тестовых данных по опроснику отвечает класс  [Modules/Surveys/Database/Seeders/SurveysDatabaseSeeder](../Modules/Surveys/Database/Seeders/SurveysDatabaseSeeder.php)
  -Запустить сидер для наполненения данными - php artisan db:seed
