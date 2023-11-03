# test-task-middle
# перед применением следующих комманд необходимо создать БД 'converter'
# команда для применения миграций, после ввода команды ввести yes 
php yii migrate
# комманда для обновления курсов валют
php yii convert/update-course 