## Список artisan команд CRM

### Очистка базы данных

- `php artisan crm:clean:tickets` - удаляет неправильно функционирующие тикеты;
- `php artisan crm:clean:reset_tokens` - удаляет все просроченные password_resets токены;

### Отправка уведомлений

- `php artisan crm:notification:test {email}` - отправка тестового уведомления на необходимый _{email}_ адрес;
- `php artisan crm:notification:start_course` - отправка уведомлений о старте курса;
- `php artisan crm:notification:end_course` - отправка уведомлений об окончании курса;
- `php artisan crm:notification:start_homework` - отправка уведомлений о старте выполнений домашних заданий;
- `php artisan crm:notification:end_homework` - отправка уведомлений об окончании выполнений домашних заданий;
- `php artisan crm:notification:homework_report` - отправка уведомлений с напоминанием, что студент должен отправить отчёт о прочтении домашних заданий;
- `php artisan crm:notification:start_lesson` - отправка уведомлений о старте урока;

### Импорт настроек

- `php artisan modules:permissions:import {--single : Choose single module for import}` - импорт разрешений с _permissions.json_ файла модуля(ей);
