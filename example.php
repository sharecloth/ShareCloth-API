<?php

require 'ShareClothApi.php';

$api = new ShareClothApi();
$api->setClient(1); //ID пользователя
$api->setSecret('somekey'); //секретный ключ, берется в профиле пользователя
$api->setApiPath('api.sharecloth.com'); //домен к которому коннектится, по умолчанию это api.sharecloth.com

$api->addPatternFile('/somepath/pattern.dxf'); //полный путь к файлу лекала

$api->addSketchFile('/somepath/1_1.JPG'); //файлы скетчей,
$api->addSketchFile('/somepath/058_1.jpg'); //можно несколько добавить

// параметры для загрузки
$params = array(
    'name' => 'Model name', //название
    'description' => 'Это описание', //описание
    //'collection_id' => 10, //ID коллекции, можно получить методом $api->getCollections()
    //'collection_size_id' => 16, //ID размера коллекции, можно получить методом $api->getCollectionSizes($collectionId)
    'size_id' => 16, //ID размера одежды, тот что в админке, можно получить  методом $api->getSizes($typeId)
    'size_type_id' => 1, //ID типа одежды, можно получить методом $api->getTypes()
);
$api->productUpload($params);
