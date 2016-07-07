Установка на локальный сервер.
==============================

* Создать `config/autoload/local.php` из `config/autoload/local.dist.php`
* Если Apache настроен на директорию `public`, то `base_path` = `/`, в остальных случаях в `base_path` следует указать путь от `DocumentRoot` в настройках apache до папки `public`
* В ОС Windows нужно прописать **ПУТЬ К ПАПКЕ** в которой хнарится `mysql.exe` (прим: `C:\xampp\mysql\bin`) в глобальные переменные ОС в переменную PATH  (добавить после символа ';' без кавычек) Или раскомментировать параметр `mysql` и прописать в значение **ПУТЬ К ФАЙЛУ** `mysql.exe`
* Создать базу данных и прописать доступ к ней в `config/autoload/local.php`
* Сгенерировать схему бд или накатить последний backup базы из `data/SQL`
* Прописать `base_path` в `config/autoload/local.php`

Установка.
----------

* Папка public - основная директория сервера. Остальные на уровень выше.
* Создать `.htaccess` из `development.htaccess` для локального сервера или из `production.htaccess` для рабочего


Обновление зависимостей
-----------------------
Скачать менеджер зависимостей

Для Linux

    curl -s https://getcomposer.org/installer | php --
    sudo mv composer.phar /usr/local/bin/composer (опционально: установить composer глобально)

Для Windows https://getcomposer.org/download/

Обновлениее менеджера зависимостей

    php composer.phar self-update

Обновить зависимости (на сервере обновлять с параметром `--no-dev`)

    php composer.phar update
    php composer.phar update --no-dev

Генерация/обновление схемы базы данных
--------------------------------------

В файле `data/Readme.md` описаны основные команды для doctrine 2

Настройка Веб сервера
---------------------

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/zf2-tutorial/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/zf2-tutorial/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

### Console

* Выполните команду `php ./public/index.php` для вывода информации по консольным командам