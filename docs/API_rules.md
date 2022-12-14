## Правила по созданию API

### Версионность API

- При создании новых контроллеров следует соблюдать следующую структуру директорий
```{module_name}/Http/Controllers/Api/{version}/{ResourceController}```
- При создании URI следует соблюдать следующий шаблон
``` {site_url}/api/{version}/{resourse_route} ```


### Правила использования методов HTTP

- **GET** Возвращает представление ресурса по указанному универсальному коду ресурса (URI). Текст ответного сообщения содержит сведения о запрашиваемом ресурсе.
- **POST** Создает новый ресурс по указанному URI. Текст запроса содержит сведения о новом ресурсе. Обратите внимание, что метод POST также можно использовать для запуска операций, не относящихся непосредственно к созданию ресурсов.
  > Важно! Метод **POST** всегда используется для обновления ресурса в случае загрузки файла когда **content-type="multipart/form-data"** так как при использовании методов **PUT** и **PATCH** будет отсутствовать тело запроса.
- **PUT** Создает или заменяет ресурсы по указанному URI. В тексте сообщения запроса указан создаваемый или обновляемый ресурс.
- **PATCH** Выполняет частичное обновление ресурса. Текст запроса определяет набор изменений, применяемых к ресурсу.
- **DELETE** Удаляет ресурс по указанному URI.

### Формат ответа АПИ

- Общий формат ответа от сервера
```json
{
    "success": true|false, //Успешность выполнения операции
    "message": "Response message", //Сообщение о проделанной операции
    "data": {} //Возвращаемые данные запроса
}
```
- При создании контроллера для API его следует унаследовать от базового API контроллера [`\App\Http\Controllers\Base\BaseApiController`](../app/Http/Controllers/Base/BaseApiController.php);
- Для успешного ответа следует использовать метод контроллера `sendSuccessResponse`;
- Для проваленого ответа следует использовать метод контроллера `sendFailedResponse`;

### Обработка ошибок при выполнении API

Обработку и перехватывание ошибок следует осуществлять в классе [`\App\Exceptions\Handler`](../app/Exceptions/Handler.php);

### Коды ответов API

- **200** - Запрос выполнен успешно;
- **201** - Ресурс успешно создан;
- **400** - Неправильный, некорректный запрос, ошибки валидации;
- **401** - Пользователь не авторизирован (не залогиненный пользователь или не корректный токен);
- **403** - Доступ к указанному ресурсу или действию запрещён;
- **404** - Запрашиваемый ресурс не найден;
- **429** - Превышен лимит на количество обращений к URI от клиента;
- **500** - Ошибка на стороне сервера.

### Cтандарты и дополнительные материалы

- [Проектирование API Microsoft](https://docs.microsoft.com/ru-ru/azure/architecture/best-practices/api-design)
- 
