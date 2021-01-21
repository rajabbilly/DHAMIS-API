<?php
session_start();
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/app/models/quarters.php';
require '../src/config/database.php';
require '../src/app/models/supervisionMainReport.php';
require '../src/app/models/htcProviders.php';
require '../src/app/models/cohortSurvivalAnalysis.php';
require '../src/app/models/clinicStaff.php';
require '../src/app/models/actionPoints.php';
require '../src/app/models/stockReport.php';
require '../src/app/models/createSetVisit.php';
require '../src/app/models/getSetVisit.php';
require '../src/app/models/updateSetVisit.php';
require '../src/app/models/organizationUnits.php';
require '../src/app/models/supervisionSection.php';
require '../src/app/models/reportingPeriod.php';
require '../src/app/models/obsDimensionsObs.php';
require '../src/app/models/facilityServices.php';
require '../src/app/models/dhaARTSection.php';
require '../src/app/models/supervisorsCode.php';
require '../src/app/models/historicalDataTrends.php';
require '../src/app/models/obsSignatures.php';
require '../src/app/models/supervisionCadres.php';
require '../src/app/models/artSupervisor.php';
require '../src/app/models/staffQualification.php';
require '../src/app/models/dataSetGroupings.php';
require '../src/app/models/formReportingTemplate.php';

$app = new \Slim\App;


$app->get('/hello', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

//Main Route
require '../src/routes/routes.php';

$app->run();