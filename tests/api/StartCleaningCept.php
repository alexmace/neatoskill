<?php
$I = new ApiTester($scenario);
$I->wantTo('Tell Stephen to Start Cleaning');
// $I->amHttpAuthenticated('service_user', '123456');
// $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$dt = new DateTime(null, new DateTimeZone("UTC"));
$requestData = [
  "version" => "1.0",
  "session" => [
    "new" => true,
    "sessionId" => "amzn1.echo-api.session.44f97423-c395-4091-98fb-7439c617a512",
    "application" => [
      "applicationId" => "amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"
    ],
    "user" => [
      "userId" => "amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"
    ],
  ],
  "context" => [
    "AudioPlayer" => [
      "playerActivity" => "STOPPED"
    ],
    "System" => [
      "application" => [
        "applicationId" => "amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"
      ],
      "user" => [
        "userId" => "amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"
      ],
      "device" => [
        "supportedInterfaces" => [
          "AudioPlayer" => []
        ]
      ]
    ]
  ],
  "request" => [
    "type" => "IntentRequest",
    "requestId" => "amzn1.echo-api.request.6f7edf8c-2002-47e4-af2e-1ab5b0334ab0",
    "timestamp" => $dt->format("Y-m-d\TH:i:s\Z"),
    "locale" => "en-GB",
    "intent" => [
      "name" => "StartCleaning"
    ]
  ]
];

$responseData = [
        'version' => '1.0',
        'response' => [
                'outputSpeech' => ['type' => 'PlainText', 'text' => 'Just coming!'],
                'shouldEndSession' => true,
        ],
];

/*
Example heasders for an Amazon skill request
POST / HTTP/1.1
Content-Type : application/json;charset=UTF-8
Host : your.application.endpoint
Content-Length :
Accept : application/json
Accept-Charset : utf-8
Signature:
SignatureCertChainUrl: https://s3.amazonaws.com/echo.api/echo-api-cert.pem
*/

$I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
$I->haveHttpHeader('Accept', 'application/json');
$I->haveHttpHeader('Accept-Charset', 'utf-8');
$I->sendPOST('/stephen', json_encode($requestData));
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$I->seeResponseIsJson();
$I->seeResponseContainsJson($responseData);

//{"version":"1.0","session":{"new":true,"sessionId":"amzn1.echo-api.session.44f97423-c395-4091-98fb-7439c617a512","application":{"applicationId":"amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"},"user":{"userId":"amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"}},"context":{"AudioPlayer":{"playerActivity":"STOPPED"},"System":{"application":{"applicationId":"amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"},"user":{"userId":"amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"},"device":{"supportedInterfaces":{"AudioPlayer":[]}}}},"request":{"type":"IntentRequest","requestId":"amzn1.echo-api.request.6f7edf8c-2002-47e4-af2e-1ab5b0334ab0","timestamp":"2016-10-22T19:00:43Z","locale":"en-GB","intent":{"name":"StopCleaning"}}}
