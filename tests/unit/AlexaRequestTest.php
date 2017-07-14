<?php

use App\Alexa\Request;

class AlexaRequestTest extends \Codeception\Test\Unit
{

    const EXAMPLE_REQUEST = [
        "version" => "1.0",
        "session" => [
            "new" => true,
            "sessionId" => "amzn1.echo-api.session.44f97423-c395-4091-98fb-7439c617a512",
            "application" => ["applicationId" => "amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"],
            "user" => ["userId" => "amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"]
        ],
        "context" => [
            "AudioPlayer" => ["playerActivity" => "STOPPED"],
            "System" => [
                "application" => ["applicationId" => "amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"],
                "user" => ["userId" => "amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"],
                "device" => ["supportedInterfaces" => ["AudioPlayer" => []]]
            ]
        ],
        "request" => [
            "type" => "IntentRequest",
            "requestId" => "amzn1.echo-api.request.6f7edf8c-2002-47e4-af2e-1ab5b0334ab0",
            "timestamp" => "2016-10-22T19:00:43Z",
            "locale" => "en-GB",
            "intent" => ["name" => "StartCleaning"]
        ]
    ];

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
    public function testConstructor()
    {
        $alexaRequest = new Request(self::EXAMPLE_REQUEST);

        $this->assertTrue($alexaRequest instanceof Request);

        return $alexaRequest;
    }

    // @todo Add validation of the JSON schema to verify the Alexa service
    // delivered expected JSON.

    /**
     * @depends testConstructor
     */
    public function testGetApplicationId(Request $alexaRequest)
    {
        $this->assertEquals('amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6', $alexaRequest->getApplicationId());
    }

    /**
     * @depends testConstructor
     */
    public function testIsNewSession(Request $alexaRequest)
    {
        $this->assertTrue($alexaRequest->isNewSession());
    }

    /**
     * @depends testConstructor
     */
    public function testGetSessionId(Request $alexaRequest)
    {
        $this->assertEquals("amzn1.echo-api.session.44f97423-c395-4091-98fb-7439c617a512", $alexaRequest->getSessionId());
    }

    /**
     * @depends testConstructor
     */
    public function testGetRequestType(Request $alexaRequest)
    {
        $this->assertEquals(Request::REQUEST_INTENT, $alexaRequest->getRequestType());
    }

    /**
     * @depends testConstructor
     */
    public function testGetLocale(Request $alexaRequest)
    {
        $this->assertEquals('en-GB', $alexaRequest->getLocale());
    }

    /**
     * @depends testConstructor
     */
    public function testGetIntent(Request $alexaRequest)
    {
        $this->assertEquals('StartCleaning', $alexaRequest->getIntent());
    }
}
