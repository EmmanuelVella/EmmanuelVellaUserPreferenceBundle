<?php

namespace EmmanuelVella\UserPreferenceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use EmmanuelVella\UserPreferenceBundle\User\Preference\UserPreference;

class UserPreferenceTest extends WebTestCase
{
    static protected $userPreference;

    static public function setUpBeforeClass()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::$userPreference = new UserPreference($client->getRequest());
    }

    public function testHas()
    {
        $this->assertFalse(self::$userPreference->has('test'));

        self::$userPreference->set('test', 1);

        $this->assertTrue(self::$userPreference->has('test'));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAccessors($value)
    {
        self::$userPreference->set('test', $value);
        $returnedValue = self::$userPreference->get('test');

        $this->assertEquals($value, $returnedValue);
    }

    public function dataProvider()
    {
        return array(
            array(123),
            array(false),
            array('test'),
            array(array('test')),
            array(new \StdClass),
        );
    }
}
