<?php
use App\Utils\HttpCode;

/**
 * Class HttpCodeTest
 */
class HttpCodeTest extends \Codeception\Test\Unit
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
    public function testHttpCodeConstants()
    {
        $this->assertEquals(HttpCode::OK, 200);
        $this->assertEquals(HttpCode::CREATED, 201);
        $this->assertEquals(HttpCode::NO_CONTENT, 204);

        $this->assertEquals(HttpCode::BAD_REQUEST, 400);
        $this->assertEquals(HttpCode::UNAUTHORIZED, 401);
        $this->assertEquals(HttpCode::FORBIDDEN, 403);
        $this->assertEquals(HttpCode::NOT_FOUND, 404);
        $this->assertEquals(HttpCode::METHOD_NOT_ALLOWED, 405);

        $this->assertEquals(HttpCode::INTERNAL_SERVER_ERROR, 500);

    }
}