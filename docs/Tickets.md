## Документация по модуль работы с тикетами

### Функционал прочтения комментариев к тикетам

За определение прочтение тикетов отвечают следующие поля таблицы `tickets` 

- `is_user_read` - флаг прочтения комментов тикета пользователем который отправил тикет;
- `is_assignee_read` - флаг прочтения комментов тикета пользователем который ответственный за решение тикета;

Комментарии к тикету считаются прочитанными в следующей ситуации:

- когда пользователь отправляет комментарий к тикету, в этом случае мы считаем что все предыдущие комментарии прочитаны;
- когда пользователь получает тикет, в этом случае мы также считаем что все предыдущие комментарии тикета прочитаны.

**События** [TicketCommentCreated](../Modules/Tickets/Events/TicketCommentCreated.php), [TicketShowed](../Modules/Tickets/Events/TicketShowed.php)

**Обработчик событий** [TicketCommentEventSubscriber](../Modules/Tickets/Listeners/TicketCommentEventSubscriber.php)