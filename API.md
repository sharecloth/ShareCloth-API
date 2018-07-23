# API Sharecloth

- [Authorization and authentication](#Authorization-and-authentication)
- [Methods description](#Methods-description)
  - [Products list](#Products-list)
  - [Avatar list](#Avatar-list)


## Authorization and authentication

Main server address: [http://api.sharecloth.com/v1/](http://api.sharecloth.com/v1/)

API can be used by users, who purcase subscription at [app.sharecloth.com](https://app.sharecloth.com).

Access token can be found at [users profile page](http://app.sharecloth.com/en/account)

Each request must be accompanied by `api_secret` as request param.


## Methods description

### Products list

```
[GET] /items/list
```

Параметры:

- list - use `all` as value for this parameter


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

### Avatar list

```
[GET] /avatar/list
```

Answer example:

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






