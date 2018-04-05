<?php

/**
 * Class ValidatorTest
 */
class ValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var \Symfony\Component\DependencyInjection\Container    $container
     */
    private $container;
    
    protected function _before()
    {
        $module = $this->getModule('Symfony');
        $this->container=$module->_getContainer();
    }

    protected function _after()
    {
    }

    // tests
    public function testToArray()
    {
        $validator = $this->container->get('doctrine');
        /**
         * @var \App\Utils\Validator $validator
         */

        $validator = $this->container->get('app_validator');
        $entity = new \App\Entity\User();
        $entity->setEmail('worngemail@qwe.ru');
        $entity->setUsername('user44');
        $entity->setPassword('123123434234');
        $error = $validator->toArray($entity);
        $this->assertNull($error);

        $entity = new \App\Entity\User();
        $entity->setEmail('worngemail');
        $entity->setUsername('user');
        $entity->setPassword('1234234');
        $error = $validator->toArray($entity);
        $this->assertNotNull($error);
    }

}