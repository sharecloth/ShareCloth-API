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
        $this->_client = new Client(API_EMAIL, API_PASSWORD, ['base_uri' => API_URI]);
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
}
