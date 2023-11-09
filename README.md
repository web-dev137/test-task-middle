# test-task-middle

Структура проекта
-------------------

      assets/             пакеты ресурсов  
      commands/           консольные команды
      components/         содержит трэйты реализующие часто используемую логику (загрузка, рэндер изображений, рэндер bool аттрибутов и т.д.
      config/             конфигурация
      controllers/        контроллеры
      helpers/            хэлперы  
      mail/               view-файлы для формирования писем
      migrtions/          миграции
      models/             модели
      models/catalog      модели раздела "Справочник"
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      widgets/            содержит виджеты.



Требования
------------
Минимально требуемая версия PHP - 8.1.0

**Установка**
------------
- Скачать проект
~~~
git clone <project-path>
~~~
- Заменить все файлы /config/*.example на соответствующие конфиги
- Создать базу данных, указать настройки подключения в /config/db.php
- Подтянуть все зависимости 
~~~
composer install
~~~
- Применить миграции
~~~
php yii migrate
~~~
- Обновновить курсы валют
~~~
php yii convert/update-course
