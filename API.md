# API Sharecloth

Обращение к API просиходит с помощью GET и POST запросы на соответствующие адреса методов.

Основной адрес API сервера: http://api.sharecloth.com/v1/


## Авторизация

API могут пользоваться только пользователи, которые оформили подписку на app.sharecloth.com.

Авторизация производится с помощью токена, который можно посмотреть на
странице профиля пользовалеля - http://app.sharecloth.com/en/account в разделе **Api Access**.

В каждом запросе следует передавать token в параметре c именем `api_secret`.


## Описание методов

### Список коллекций размеров

```
[GET] /options/collections
```

Параметры - отсутствуют

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/options/collections",
    "data": {
        "items": {
            "30": "Scaned",
            "47": "жен",
            "69": "муж",
            "73": "1/5"
        }
    }
}
```


### Создание коллекции

```
[POST] /options/add-collection
```

Параметры
- name - Название коллекции
- gender - Пол. 1 - мужская, 2 - женская
- cloth_type - тип одежды. 0 - Плечевые, 1 - Поясные



Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/options/add-collection",
    "data": {
        "id": 27,
        "date_create": "2017-07-10 08:56:21.952881",
        "user_id": 170,
        "name": "Collection Name",
        "gender": 1,
        "cloth_type": 0,
        "active": true
    }
}
```




### Список размеров коллекции

```
[GET] /options/collection_sizes
```

Параметры
- collection_id - id коллекции из которой нужно достать размеры.


Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/options/collection_sizes",
    "data": {
        "items": []
    }
}
```

### Список типов одежды

```
[GET] /options/types
```

Параметры - отсутствуют

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/options/types",
    "data": {
        "items": {
            "1": "Shirt",
            "2": "Trousers",
            "3": "Dress",
            "4": "Skirt"
        }
    }
}
```



### Список размеров

```
[GET] /options/sizes
```

Параметры:
- type_id - тип одежды

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/options/sizes",
    "data": {
        "items": {
            "9": "S",
            "10": "M",
            "11": "L",
            "12": "XL",
            "13": "XXL"
        }
    }
}
```


### Создание нового размера

```
[POST] /options/add-sizing-collection
```

Параметры:
- collection_id - ID коллекции пользователя
- name - Название размера
- type - Тип. Доступные значения - generated (для размеров, аватары которых генерятся воркером) и 3d_scan (для 3D сканов)
- avatar_id - ID уже загруженного аватара
- notify - если параметр равен 1, то будет отправляться письмо пользователю о новом размере

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/options/add-sizing-collection",
    "data": {
        "date_create": "2017-07-10 12:09:27",
        "name": "Test size 1",
        "collection_id": 27,
        "active": true,
        "avatar_id": 1,
        "type": "3d_scan"
    }
}
```

### Загрузка одежды

```
[POST] /product/upload
```


Параметры:

- model_name - название изделия
- description - описание изделия
- comment - комментарий к вещи
- cloth_type - тип одежды, 0 - плечевые, 1 - поясные
- without_size - равен 1 если параметры размеров не обязательные

Либо параметры:

- collection_id - id коллекции размеров
- collection_size_id - id размера из коллекции пользователя

либо

- size_type_id - id типа изделия
- size_id - id размера

Дополнительный параметр:

- subscriptions - список email для открытия доступа другим пользователям с отправкой их уведомлений. Может быть массивом subscriptions[] или строкой со списком email пользователей разделенной запятой или точной с запятой.

Файлы:

- pattern_file - 1 файл лекала пользователя в формате zip либо dxf.
- sketch_files - массив изображений изделия, если изображение 1 - то нужно передавать в виде массива 1 элемент sketch_files[]. Формат png, jpg, gif
- curve - файл для сшивки
- texture - файл текстуры
- gdcb - файл GDCB редактора

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/product/upload",
    "data": {
        "id": 370,
        "ident": "b6cd2114e85d38ae01199d5d0a83e3f8",
        "url": "http://local.sharecloth.com/en/product/view/b6cd2114e85d38ae01199d5d0a83e3f8",
        "curve_id": 103,
        "texture_id": 155
    }
}
```


### Обновление лекала одежды

```
[POST] /product/update-pattern
```

Параметры:

- id - id вещи в share_cloth
- comment - комментарий к вещи
- with_texture - если 1 - в запросе будет ожидаться файл текстуры, если 0 или пустое - файл текстуры не будет обновлен

Файлы:

- pattern_file - 1 файл лекала пользователя в формате zip либо dxf.
- sketch_files - массив изображений изделия, если изображение 1 - то нужно передавать в виде массива 1 элемент sketch_files[]. Формат png, jpg, gif
- curve - файл для сшивки
- texture - файл текстуры
- gdcb - файл GDCB редактора

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/product/update-pattern",
    "data": {
        "id": 350,
        "user_id": 170,
        "updated_pattern": 1,
        "updated_texture": 0
    }
}
```


### Проверка существования вещи

```
[GET] /product/check
```

Параметры:

- id - id вещи в share_cloth


Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/product/check",
    "data": {
        "id": 350,
        "user_id": 170,
        "ident": "3936d78f696d6b17602822573e02d4be",
        "name": "Sapelle - Sleeveless top (lines1, size 12)",
        "status": 3,
        "status_name": "Выполнен"
    }
}
```


### Получение списка вещей

```
[GET] /items/list
```

Параметры:

- list - может принимать значение all или with_gdcb


Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/items/list",
    "data": [
        {
            "id": 370,
            "ident": "b6cd2114e85d38ae01199d5d0a83e3f8",
            "curve_id": null,
            "curve_ident": null,
            "name": "new-model",
            "cloth_type": 0,
            "description": "description",
            "date_create": "2017-07-10T09:12:23+03:00",
            "updated_time": "2017-07-10T09:12:23+03:00",
            "is_owner": true,
            "link": "http://local.sharecloth.com/product/view/b6cd2114e85d38ae01199d5d0a83e3f8",
            "curve_file": false,
            "texture_file": false,
            "gdbc_file": false,
            "designer_name": "petun111 ",
            "sketches": []
        },
        {
            "id": 363,
            "ident": "ec7f300cd6e3b55aa2d74720bf0a8d10",
            "curve_id": null,
            "curve_ident": null,
            "name": "TEST SKETCKES",
            "cloth_type": 0,
            "description": "dfgfdg",
            "date_create": "2016-10-16T23:25:34+03:00",
            "updated_time": "2016-10-16T23:25:34+03:00",
            "is_owner": true,
            "link": "http://local.sharecloth.com/product/view/ec7f300cd6e3b55aa2d74720bf0a8d10",
            "curve_file": false,
            "texture_file": false,
            "gdbc_file": false,
            "designer_name": "petun111 ",
            "sketches": [
                "http://local.sharecloth.com/icache/cloth_sketch/302_c30b5251ec3d32e6e043d5197a3287ef.jpeg",
                "http://local.sharecloth.com/icache/cloth_sketch/303_c82f4b60521d582fe1d5e3b47315e478.jpg"
            ]
        }
    ]
}
```


### Создание аватаров из уже подготовленных файлов

```
[POST] /avatar/upload
```

Параметры:

- avatar_name - Имя аватара (сейчас нигде не используется)
- mesh - zip архив с аватаром
- texture - zip архив с текстурой (не обязательный)
- texture_id - ID существующей текстуры (не обязательный)

Обязательным условием является наличия одного поля, либо texture либо texture_id.

Ответ содержит следующие поля:

- avatar_id - id аватара
- texture_id - id текстуры


Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/avatar/upload",
    "data": {
        "avatar_id": 350,
        "avatar_ident": 170
    }
}
```


### Получение списка аватаров

```
[GET] /avatar/list
```

Пример ответа:

```json
{
    "status": "success",
    "method": "/v1/avatar/list",
    "data": [
        {
            "avatar_id": 21,
            "description": "Standard avatar 1"
        },
        {
            "avatar_id": 22,
            "description": "Standard avatar 2"
        },
        {
            "avatar_id": 23,
            "description": "Standard avatar 3"
        },
        {
            "avatar_id": 24,
            "description": "Standard avatar 4"
        },
        {
            "avatar_id": 25,
            "description": "Standard avatar 5"
        },
        {
            "avatar_id": 26,
            "description": "Standard avatar 6"
        },
        {
            "avatar_id": 1,
            "description": "Collection Name - Test size 1"
        }
    ]
}
```






