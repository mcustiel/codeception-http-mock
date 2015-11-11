<?php
class WelcomeCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(\AcceptanceTester $I)
    {
        $I->expectRequest()->when()
                ->methodIs('GET')
                ->pathIs('/foo')
            ->then()
                ->body('mocked body')
            ->end();
        $I->doNotExpectAnyOtherRequest();
        $response = file_get_contents('http://localhost:18080/foo');
        $I->assertEquals('mocked body', $response);
    }
}
