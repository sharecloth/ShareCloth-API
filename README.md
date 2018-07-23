# ShareCloth-API PHP Client

[API Documentation](API.md) 

[Get access token](http://app.sharecloth.com/en/account/api-page)

## Install

### With composer. 

Add this lines to your `composer.json` file:

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

And then

`composer update`


## Work with api

```php
$accessToken = ''; // insert your access token here. can by found at this page: http://app.sharecloth.com/en/account/api-page

$client = new Client($accessToken);

$avatarList = $client->avatarList();

print_r($avatarList);
```
