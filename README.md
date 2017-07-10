# ShareCloth-API PHP Client

## Устновка

Прописать в composer.json:

```json
"repositories": [
{
    "type": "vcs",
    "url": "https://github.com/sharecloth/ShareCloth-API.git"
}
],
"require": {
    "sharecloth/api": "dev-master"
}
```

Далее выполнить

`composer update`


## Пример работы с API

```php
$accessToken = ''; // insert your access token here

$client = new Client($accessToken);

$avatarList = $client->avatarList();

print_r($avatarList);
```
