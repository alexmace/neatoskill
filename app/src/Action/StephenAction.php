<?php
namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Alexa\Request as AlexaRequest;
use AlexMace\NeatoBotvac\Robot;

final class StephenAction
{
    // The Amazon Application ID for this skill. Any incoming requests should be
    // checked to ensure they are for this application.
    const APPLICATION_ID = ""; // Redacted, get this from Amazon
    const INTENT_START_CLEANING = 'StartCleaning';
    const INTENT_STOP_CLEANING = 'StopCleaning';
    const INTENT_PAUSE_CLEANING = 'PauseCleaning';
    const INTENT_RETURN_TO_BASE = 'ReturnToBase';
    const INTENT_CLEAN_SPOT = 'CleanSpot';
    const INTENT_DEEP_CLEAN_SPOT = 'DeepCleanSpot';

    private $logger;
    private $stephen;

    public function __construct(Robot $stephen, LoggerInterface $logger)
    {
        $this->stephen = $stephen;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Stephen page action dispatched");

        // Load the incoming request data and create an Alexa Request object
        // from the incoming data.
        // @todo Validate the request signature, as per https://developer.amazon.com/public/solutions/alexa/alexa-skills-kit/docs/developing-an-alexa-skill-as-a-web-service#checking-the-signature-of-the-request
        // @todo Handle invalid incoming data.
        // @todo Move this to a middleware handler that parses it and sets it as
        //       a request attribute?
        $alexaRequest = new AlexaRequest($request->getParsedBody());

        // @todo Validate the timestamp of the request to prevent replay attacks
        // Timetamp should be within 150 seconds of now
        // Return 400 Bad Request if the timestamp is not valid

        // Amazon require that we verify the Application ID so that we only
        // process requests for this skill.
        if ($alexaRequest->getApplicationId() != self::APPLICATION_ID) {
            return $response->withStatus(400);
        }

        switch ($alexaRequest->getRequestType()) {
            case AlexaRequest::REQUEST_LAUNCH:
                return $this->onLaunch($response, $alexaRequest);
                break;

            case AlexaRequest::REQUEST_SESSIONENDED:
                return $this->onSessionEnded($response, $alexaRequest);
                break;

            case AlexaRequest::REQUEST_INTENT:
                return $this->onIntent($response, $alexaRequest);
                break;

            default:
                return $response->withStatus(400);
                break;
        }


        //{
        //  "version":"1.0",
        //  "session":{
        //    "new":true,
        //    "sessionId":"amzn1.echo-api.session.44f97423-c395-4091-98fb-7439c617a512",
        //    "application":{"applicationId":""},
        //    "user":{"userId":"amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"}
        //  },
        //  "context":{
        //    "AudioPlayer":{"playerActivity":"STOPPED"},
        //    "System":{
        //      "application":{"applicationId":""},
        //      "user":{"userId":"amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"},
        //      "device":{"supportedInterfaces":{"AudioPlayer":[]}}
        //    }
        //  },
        //  "request":{
        //    "type":"IntentRequest",
        //    "requestId":"amzn1.echo-api.request.6f7edf8c-2002-47e4-af2e-1ab5b0334ab0",
        //    "timestamp":"2016-10-22T19:00:43Z",
        //    "locale":"en-GB",
        //    "intent":{"name":"StopCleaning"}
        //  }
        //}


        return $response->withJson($data);
    }

    public function onLaunch(Response $response, AlexaRequest $alexaRequest)
    {
        // Launch a session
        // Open and store details of a session.
        // Return instructions etc.
        return $response;
    }

    public function onIntent(Response $response, AlexaRequest $alexaRequest)
    {
        switch ($alexaRequest->getIntent()) {
            case self::INTENT_START_CLEANING:
                return $this->onStartCleaning($response, $alexaRequest);
                break;

            case self::INTENT_CLEAN_SPOT:
                return $this->onCleanSpot($response, $alexaRequest);
                break;

            case self::INTENT_STOP_CLEANING:
                return $this->onStopCleaning($response, $alexaRequest);
                break;

            case self::INTENT_PAUSE_CLEANING:
                return $this->onPauseCleaning($response, $alexaRequest);
                break;

            case self::INTENT_RETURN_TO_BASE:
                return $this->onReturnToBase($response, $alexaRequest);
                break;

            case self::INTENT_DEEP_CLEAN_SPOT:
                return $this->onDeepCleanSpot($response, $alexaRequest);
                break;

            default:
                return $response->withStatus(400);
                break;
        }
        return $response;
    }

    public function onSessionEnded(Response $response, AlexaRequest $alexaRequest)
    {
        // Close a session
        // Perform any cleanup required
        return $response;
    }

    public function onStartCleaning(Response $response, AlexaRequest $alexaRequest)
    {
        // Tell the Neato service to start cleaning
        try {
            $this->stephen->cleanHouse();
            return $this->sendResponse(
                $response,
                'Just coming!',
                true,
                'Started House Cleaning',
                'Performing a clean of the whole house'
            );
        } catch (\Exception $e) {
            return $this->sendResponse(
                $response,
                $e->getMessage(),
                true,
                'Failed to start cleaning',
                'Could not start cleaning due to: ' . $e->getMessage()
            );
        }
    }

    public function onCleanSpot(Response $response, AlexaRequest $alexaRequest)
    {
        // Tell the Neato service to do a spot clean
        try {
            $this->stephen->cleanSpot();
            return $this->sendResponse(
                $response,
                'Just coming!',
                true,
                'Started Spot Cleaning',
                'Performing a spot cleaning'
            );
        } catch (\Exception $e) {
            return $this->sendResponse(
                $response,
                $e->getMessage(),
                true,
                'Failed to spot clean',
                'Could not start cleaning due to: ' . $e->getMessage()
            );
        }
    }

    public function onStopCleaning(Response $response, AlexaRequest $alexaRequest)
    {
        // Tell the Neato service to stop cleaning
        try {
            $this->stephen->stopCleaning();
            return $this->sendResponse(
                $response,
                'Just stopping!',
                true,
                'Stopping cleaning',
                'Stopping cleaning, but not going back to base.'
            );
        } catch (\Exception $e) {
            return $this->sendResponse(
                $response,
                $e->getMessage(),
                true,
                'Failed to stop cleaning',
                'Could not stop cleaning due to: ' . $e->getMessage()
            );
        }
    }

    public function onPauseCleaning(Response $response, AlexaRequest $alexaRequest)
    {
        // Tell the Neato service to pause cleaning
        try {
            $this->stephen->stopCleaning();
            return $this->sendResponse(
                $response,
                'Just pausing!',
                true,
                'Pausing cleaning',
                'Pausing cleaning, not sure what else there is to say.'
            );
        } catch (\Exception $e) {
            return $this->sendResponse(
                $response,
                $e->getMessage(),
                true,
                'Failed to pause cleaning',
                'Could not pause cleaning due to: ' . $e->getMessage()
            );
        }
    }

    public function onReturnToBase(Response $response, AlexaRequest $alexaRequest)
    {
        // Tell the Neato service to return to base
        try {
            $this->stephen->returnToBase();
            return $this->sendResponse(
                $response,
                'Going home!',
                true,
                'Returning to base',
                'Making my way back to base... if I can, otherwise I\'ll just stop where I am.'
            );
        } catch (\Exception $e) {
            return $this->sendResponse(
                $response,
                $e->getMessage(),
                true,
                'Failed to return to base',
                'Could not return to base due to: ' . $e->getMessage()
            );
        }
    }

    public function onDeepCleanSpot(Response $response, AlexaRequest $alexaRequest)
    {
        try {
            $this->stephen->deepCleanSpot();
            return $this->sendResponse(
                $response,
                'Deep clean coming up!',
                true,
                'Starting a Deep Spot Clean',
                'Doing a spot clean with a double pass for that really deep clean.'
            );
        } catch (\Exception $e) {
            return $this->sendResponse(
                $response,
                $e->getMessage(),
                true,
                'Failed to start a deep spot clean',
                'Could not clean due to: ' . $e->getMessage()
            );
        }
    }

    private function sendResponse(
        Response $response,
        $phrase,
        $endSession,
        $cardTitle = null,
        $cardContent = null
    ) {
        $data = [
                'version' => '1.0',
                'response' => [
                        'outputSpeech' => ['type' => 'PlainText', 'text' => $phrase],
                        'shouldEndSession' => $endSession,
                ],
        ];

        if (!is_null($cardTitle) && !is_null($cardContent)) {
            $data['response']['card'] = [
                "type"      => "Simple",
                "title"     => $cardTitle,
                "content"   => $cardContent,
            ];
        }
        return $response->withJson($data);
    }
}
