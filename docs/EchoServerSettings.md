## Настройка echo server для работы с back-end в реальном времени

Laravel echo server в проекте используется для организации доставки событий на клиента (фронтенд часть) в реальном времени. 


Работа клиентов c back-end в реальном времени  организована с помощью пакета [Laravel echo server](https://github.com/tlaverdure/laravel-echo-server).

Для работы Laravel echo server необходимо:

- Laravel 5.3
- Node 6.0+
- Redis 3+

Laravel echo server устанавливается глобально:

`npm install -g laravel-echo-server`

### Особенности настроек конфигурационного файла:

Для запуска Laravel echo server необходимо перейти в директорию с конфигурационным файлом и выполнить команду:

`laravel-echo-server start`

### Настройка работы supervisor:

Запуск echo server выполняется с помощью утилиты [supervisor](https://www.digitalocean.com/community/tutorials/how-to-install-and-manage-supervisor-on-ubuntu-and-debian-vps)
