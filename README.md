## Lms

### Краткое описание проекта
Проект _**Lms**_ представляет собой образовательныю платформу основными целями которой является предоставить пользователям (студентам) доступ к онлайн курсам заказчика. Дополнительная цель - предоставление консультаций;

### Структура проекта

Проект структурно состоит из 2 сущностей:

1. **Пользовательский сайт** - предоставляет доступ к платформе студентам и преподавателям через API CRM платформы. Стек технологий: **Quasar (Vue.js)**
2. **CRM платформа _(текущий проект)_** - предоставляет API для доступа к платформе с клиентских приложений (Пользовательский сайт, моб приложение и т.д.). Стек технологий: **Laravel (php)**

### Документация к проекту 

- [Модули проекта](docs/Modules.md)
- [Настройка очередей](docs/QueueSettings.md)
- [Настройка laravel echo server](docs/EchoServerSettings.md)
- [Широковещательная рассылка (broadcasting) ](docs/Broadcasting.md)
- [Нотификации](docs/Notifications.md)
- [Artisan команды](docs/ArtisanCommands.md)

### Управление проектом

- [**Доска проекта Jira**](https://www.atlassian.com/ru/software/jira);

## Техническое описание CRM платформы

Ядро проекта [**Laravel framework (php)**](https://laravel.com/docs/8.x)

### Основные репозитории

- Модульности проекта реализуется с помощью [**Laravel-modules**](https://nwidart.com/laravel-modules/v6/introduction)
- АПИ реализуется с помощью [**Laravel Sanctum**](https://laravel.com/docs/8.x/sanctum);
- АПИ документация реализуется с помощью [**DarkaOnLine/L5-Swagger**](https://github.com/DarkaOnLine/L5-Swagger/wiki/Installation-&-Configuration);
- Роли и разрешения на проекте реализуются с помощью [**Laravel-permission**](https://spatie.be/docs/laravel-permission/v4/introduction);
- Реализация взаимодействия CRM платформы с клиентами (пользовательский сайт)  [_broadcasting_](https://laravel.com/docs/8.x/broadcasting) с помощью [**Laravel Echo Server**](https://github.com/tlaverdure/laravel-echo-server);

### Технические требования по работе с платформой

- PHP стандарт [**PSR-12**](https://www.php-fig.org/psr/psr-12/) и  [**практики чистого кода**](https://github.com/jupeter/clean-code-php);
- [правила работы с модулями проекта](docs/Module_rules.md);
- [правила по созданию API](docs/API_rules.md);
- [правила по документированию API](docs/API_documentation.md);

#### Локальный сервер

Заполняем настройки в файле .env
Запускаем bash скрипт
```bash
bin/run_local.sh
```
