1. Для установки базы данных выполните в SQL-менеджере код, в файле db.sql
2. Для установки DbManager выполните в консоли, в папке проекта, команду: yii migrate --migrationPath=@yii/rbac/migrations
3. Для настройки ролей RBAC в браузере один раз пройдите по ссылке: buysell/init 
4. Примените имеющиеся миграции, выполните в консоли, в папке проекта, команду: yii migrate
5. Для работы с Firebase Realtime Database установите библиотеку Firebase, выполнив в консоли, в папке проекта, команду: composer require kreait/firebase-php
6. Зарегистрируйтесь на сайте Firebase (https://console.firebase.google.com/) и создайте свой проект
7. Добавьте Firebase в сове приложение, для этого выберите 3ий значок - web приложение
8. Зарегистрируйте его, дав название. Во вкладке Build выберите Realtime Database -> Create database в тестовом режиме
9. В ProjectSettings->General копируем firebaseConfig и вставляем данные в js/firebase.js
10. В ProjectSettings->Service accounts генерируем новый Private Key, и подгружаем его в app/handlers. Название этого файла вписываем в app/handlers/FirebaseHandler в переменную $namePrivateKeyFile
11. В ProjectSettings->Service accounts находим databaseURL. Этот url вписываем в app/handlers/FirebaseHandler в переменную $nameDatabaseUri

