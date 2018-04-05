<?php

use App\Utils\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ResponseTest
 */
class ResponseTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testToJson()
    {
        $result = Response::toJson(200, 'test');
        $data = $result->getContent();
        $statusCode = $result->getStatusCode();
        $this->assertTrue($result instanceof JsonResponse);
        $this->assertEquals(json_decode($data, true), ['code' => 200, 'message' => 'test']);
        $this->assertEquals($statusCode, 200);
    }
}