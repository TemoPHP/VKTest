## Тестовое задание парсинга данных ВК

### Задача
Делаем приложение на laravel 5.6 которое должно выполнять следующую работу:

- Принять запрос вида:

POST {
token: string,
params: array,
method: string
}

- Отправить запрос в ВК 6 раз

- Вернуть в ответ массив состоящий из 6 ответов на запрос из ВК

- все 6 запросов в ВК должны отправляться параллельно (используем jobs)

- Сделать исходящий тротлинг запросов в сторону ВК с лимитом в 1 запрос в 0.7 секунд (Если ВК возвращает ошибку  9 (flood control), ждем пока она не уйдет, повторяем неудавшийся запрос)

ЦЕЛЬ: в любом случае получить массив из 6 ответов на запрос

используем либу:
https://github.com/VKCOM/vk-php-sdk

Тестовый запрос:

POST {
token: "5cfb37890df078945330948ff246188b397a69f250951b256b44fb9af97d96fa0ab53b538bceae512dcc6",
method: "ads.getClients",
params:  [
"account_id" : 1900013439
]
}

Должен в ответ из получить

[
[{
"id": 1604562986,
"name": "TB таргетбонус",
"day_limit": "0",
"all_limit": "912920"
}],
[{
"id": 1604562986,
"name": "TB таргетбонус",
"day_limit": "0",
"all_limit": "912920"
}],
[{
"id": 1604562986,
"name": "TB таргетбонус",
"day_limit": "0",
"all_limit": "912920"
}],
[{
"id": 1604562986,
"name": "TB таргетбонус",
"day_limit": "0",
"all_limit": "912920"
}],
[{
"id": 1604562986,
"name": "TB таргетбонус",
"day_limit": "0",
"all_limit": "912920"
}],
[{
"id": 1604562986,
"name": "TB таргетбонус",
"day_limit": "0",
"all_limit": "912920"
}]
]


### Локальное окружение
Для запуска локальной среды разработки необходимо выполнить команду:

```bash
docker-compose up --build
```

После запуска проект доступен по адресу [http://localhost:800](http://localhost:800)

Чтобы попасть внутрь контейнера с php необходимо выполнить команду:

```bash
docker-compose exec php /bin/bash
```

Находясь внутри контейнера выполняем:

```bash
composer install
```

Маршрут для тестирования результатов - POST [http://localhost:800/api/getClients](http://localhost:800/api/getClients)