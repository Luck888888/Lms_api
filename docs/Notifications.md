## Документация по нотификациям модулей

### Lessons

- Уведомление о **старте урока** отправляется в **18:00 за день до начала урока**, уведомление получает **учитель** и **студент** [StartLessonNotificationsJob](../Modules/Lessons/Jobs/StartLessonNotificationsJob.php)

### Homeworks

- Уведомление о **старте домашнего задания** отправляется в **09:00 в день начала домашнего задания**, уведомление получает и **учитель** и
  **студент** [StartHomeworkNotificationsJob](../Modules/Homeworks/Jobs/StartHomeworkNotificationsJob.php)
-Уведомление об **окончании домашнего задания** отправляется в **18:00 в день завершения домашнего задания**, уведомление получает и **учитель** и
    **студент**. [EndHomeworkNotificationsJob](../Modules/Homeworks/Jobs/EndHomeworkNotificationsJob.php)
- Уведомление  **напоминание об отправке отчёта по домашнему заданию** отправляется в **09:00 каждый 
    день в интервале от даты начала приёма домашних заданий до завершения домашних заданий, не включая сами даты начала и завершения приёма заявок**, 
    уведомление получает **студент** который не сдал отчёт о выполнении домашнего задания 
    [HomeworkReportNotificationsJob](../Modules/Homeworks/Jobs/HomeworkReportNotificationsJob.php)
- Уведомление о **разрешении дополнительной отправки отчета по домашнему заданию** отправляется **сразу после создания дополнительного доступа**,уведомление получает **студент**
  [HomeworkNotificationEventSubscriber](../Modules/Homeworks/Listeners/HomeworkNotificationEventSubscriber.php)
- Уведомление о **загрузке домашнего задания студентом** отправляется **сразу после загрузки домашнего задания**, уведомление получает **преподаватель**
  [HomeworkUploadStudentEventSubscriber](../Modules/Homeworks/Listeners/HomeworkReportUploadedEventSubscriber.php)
- Уведомление о **новом комментарии  по отчету домашнего задания от преподавателя** отправляется **сразу после создания нового комментария преподавателем**, уведомление получает **студент**
  [HomeworkReportCommentEventSubscriber](../Modules/Homeworks/Listeners/HomeworkReportCommentEventSubscriber.php)

### Courses

- Уведомление о **старте курса** отправляется в **18:00 за день до начала курса**, уведомление получает **учитель** и **студент** [StartCoursesNotificationsJob](../Modules/Courses/Jobs/StartCoursesNotificationsJob.php)
- Уведомление о **завершении курса** отправляется в **09:00 за 2 дня до завершения курса** (если курс заканчивается 18-го числа то уведомление отправляем 16-го), уведомление получает **студент**.[EndCoursesNotificationsJob](../Modules/Courses/Jobs/EndCoursesNotificationsJob.php)
- Уведомление об **изменении статуса пройденного или не пройденного курса студентом** отправляется **сразу при изменении статуса курса**, уведомление получает **студент**
  [CourseStudentStatusEventSubscriber](../Modules/Courses/Listeners/CourseStudentStatusEventSubscriber.php)
- Уведомление о **добавлении студента к курсу, о том, что студент может подписать контракт на курс** отправляется **сразу после добавления студента к курсу**, уведомление получает **студент**
  [CourseAddStudentEventSubscriber ](../Modules/Courses/Listeners/CourseAddStudentEventSubscriber.php)
- Уведомление о **подписании студентом контракта на курс** отправляется **сразу после подписания контракта студентом**, уведомление получает **студент** и **администратор**
  [CourseSignContractEventSubscriber](../Modules/Courses/Listeners/CourseSignContractEventSubscriber.php)

### Tickets

- Уведомление о **создании нового тикета** отправляется  **сразу при создании нового тикета**, уведомление получает **суперадмин**
  [TicketNotificationEventSubscriber](../Modules/Tickets/Listeners/TicketNotificationEventSubscriber.php)
- Уведомление об **открытом тикете**, на которые не реагируют больше 12 часов,отправляется ежедневно в **18:00**, уведомление получает суперадмин
  [TicketExpiredNotificationsJob](../Modules/Tickets/Jobs/TicketExpiredNotificationsJob.php)

### Users

- Уведомление 0 **создании аккаунта** отправляется **сразу при создании нового аккаунта**,уведомление получает **учитель** и **студент**
  [UserNotificationEventSubscriber](../Modules/Users/Listeners/UserNotificationEventSubscriber.php)
- Уведомление о **смене пароля** отправляется **по требованию**,уведомление получает **пользователь,которому обновили пароль**
  [UserChangePasswordEventSubscriber](../Modules/Users/Listeners/UserChangePasswordEventSubscriber.php)
- Уведомление о **сбросе пароля по email** отправляется **по запросу о сбросе пароля**, уведомление получает **пользователь, который запросил изменение пароля**
  [UserResetPasswordNotification ](../Modules/Users/Notifications/UserResetPasswordNotification.php)

### Curriculums

-Уведомление о **добавлении студента к учебной программе, о том, что студент может подписать контракт на данную учебную программу** 
 отправляется **сразу после добавления студента к учебной программе**, уведомление получает **студент**
 [CurriculumAddStudentNotification ](../Modules/Curriculums/Notifications/CurriculumAddStudentNotification.php)
-Уведомление о **подписании студентом контракта на учебную программу** отправляется **сразу после подписания контракта студентом**, уведомление получает **студент** и **администратор**
[CurriculumSingContractEventSubscriber](../Modules/Curriculums/Listeners/CurriculumSingContractEventSubscriber.php)

