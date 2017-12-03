# Wall / Серверная часть

### Как запустить
#### устанавливаем зависимость в виде php расширения
```
sudo dnf install php-zmq-1.0.8-10.fc24.x86_64 // fedora 24
```
#### Устанавливаем зависимости
```
composer install
```
#### Создаем таблицы
```
php artisan migrate
```
#### Настраиваем конфигурацию сокетов в папке 
 ```
 config/zmq_socket.php
 ```
#### Запускаем zmq сокет
```
php artisan zmq:run
```
