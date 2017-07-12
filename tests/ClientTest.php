<?php
use ShareCloth\Api\Client;

/**
 * Class ClientTest
 */
class ClientTest extends PHPUnit_Framework_TestCase
{

    /** @var  Client */
    protected $_client;

    /**
     *
     */
    public function setUp()
    {
        $this->_client = new Client(API_ACCESS_TOKEN, ['base_uri' => API_URI]);
        $this->assertNotEmpty($this->_client->getApiSecret(), 'Api secret is empty');
        parent::setUp();
    }

    /**
     *
     */
    public function testAvatarList()
    {
        $avatars = $this->_client->avatarList([]);
        $this->assertInternalType('array', $avatars);

        $this->assertArrayHasKey('avatar_id', $avatars[0]);
        $this->assertArrayHasKey('description', $avatars[0]);
    }


    /**
     *
     */
    public function testItemList()
    {
        $items = $this->_client->itemsList(['list' => 'all']);
        $this->assertInternalType('array', $items);

        $this->assertArrayHasKey('id', $items[0]);
        $this->assertArrayHasKey('ident', $items[0]);
        $this->assertArrayHasKey('name', $items[0]);
        $this->assertArrayHasKey('link', $items[0]);
    }

    /**
     *
     */
    public function testOptionsCollections()
    {
        $items = $this->_client->optionsCollection();
        $this->assertInternalType('array', $items['items']);
    }

    /**
     *
     */
    public function testOptionsCollectionSizes()
    {
        $items = $this->_client->optionsCollectionSizes([
            'collection_id' => 27,
        ]);
        $this->assertInternalType('array', $items['items']);
    }

    
    public function testOptionsAddSizingCollection()
    {
        $result = $this->_client->optionsAddSizingCollection([
            'collection_id' => 27,
            'name' => 'Test size ' . time(),
            'type' => '3d_scan',
            'avatar_id' => 1,
            'notify' => 0,
        ]);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('date_create', $result);
        $this->assertArrayHasKey('collection_id', $result);
        $this->assertArrayHasKey('active', $result);
        $this->assertArrayHasKey('avatar_id', $result);
    }


    public function testOptionTypes()
    {
        $items = $this->_client->optionTypes();
        $this->assertInternalType('array', $items['items']);
    }

    public function testAddCollection()
    {
        $result = $this->_client->optionsAddCollection([
            'name' => 'Unit ' . time(),
            'gender' => 1,
            'cloth_type' => 0,
        ]);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date_create', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('gender', $result);
        $this->assertArrayHasKey('cloth_type', $result);
        $this->assertArrayHasKey('active', $result);
    }


    public function testOptionsSizes()
    {
        $items = $this->_client->optionsSizes([
            'type_id' => 1
        ]);
        $this->assertInternalType('array', $items['items']);
    }

//    public function testAvatarCreateUpdate()
//    {
//        $options = [
//            'gender' => 'male',
//            'HIPS' => '60',
//            'avatar_name' => 'Avatar Name',
//            'WAIST' => '60',
//            'HEIGHT' => '160',
//            'NECK_CIRCLE' => '30',
//            'hair_id' => 0,
//            'hair_style' => 'PerlHair',
//            'eye_id' => 1,
//        ];
//
//        $result = $this->_client->avatarCreate($options);
//
//        $this->assertInternalType('array', $result);
//        $this->assertArrayHasKey('avatar_id', $result);
//
//        $options['id'] = $result['avatar_id'];
//
//        $result = $this->_client->avatarUpdate($options);
//        $this->assertInternalType('array', $result);
//        $this->assertArrayHasKey('avatar_id', $result);
//    }

}
