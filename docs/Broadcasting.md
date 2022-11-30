## Широковещательная рассылка (broadcasting) 

 [Документация](https://laravel.com/docs/8.x/broadcasting)
 
### Модуль тикетов

Канал для прослушивания `tickets.{ticket}`

- `{ticket}` - id тикета;
- к каналу может подключиться пользователь, который имеет доступ к тикету.


События которые отправляются в канал:
- [.ticket_comment.created](../Modules/Tickets/Events/TicketCommentCreated.php) - создание комментария к тикету;
