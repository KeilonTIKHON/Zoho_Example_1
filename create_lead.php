<?php
require_once __DIR__ . '/init.php';

use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\Leads;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\crm\api\util\Choice;

use Dotenv\Dotenv;

//.env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$bodyWrapper = new BodyWrapper();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request');
}

try {

    
    $lead = new Record();

    $lead->addFieldValue(Leads::FirstName(), $_POST['First_Name'] ?? '');
    $lead->addFieldValue(Leads::LastName(), $_POST['Last_Name'] ?? '');
    $lead->addFieldValue(Leads::EMAIL(), $_POST['Email'] ?? '');
    $lead->addFieldValue(Leads::COMPANY(), $_POST['Company'] ?? 'Individual');
    $lead->addFieldValue(Leads::LeadStatus(), new Choice("New"));
    $lead->addKeyValue("Webhook_URL", $_ENV['WEBHOOK_RETURN_URL']);

    
    $records = [$lead];
    $recordOps = new RecordOperations();
    $bodyWrapper->setData($records);
    $response = $recordOps->createRecords("Leads", $bodyWrapper);

    if ($response !== null) {

        if ($response->isExpected()) {

            $handler = $response->getObject();

            if ($handler instanceof ActionWrapper) {

                $data = $handler->getData();
                $result = $data[0];

                echo "<h3>Lead created successfully!</h3>";
                echo "<pre>";
                print_r([
                    "status" => $result->getStatus()->getValue(),
                    "code" => $result->getCode()->getValue(),
                    "details" => $result->getDetails(),
                ]);
                echo "</pre>";
                exit;
            }

        } elseif ($response->getObject() instanceof APIException) {

            $exception = $response->getObject();

            echo "<h3>Zoho API Error</h3>";
            echo "<pre>";
            print_r([
                "status"     => $exception->getStatus()->getValue(),
                "code"       => $exception->getCode()->getValue(),
                "details"    => $exception->getDetails(),
                "message"    => $exception->getMessage(),
            ]);
            echo "</pre>";
            exit;
        }
    }

} catch (Exception $e) {

    echo "<h3>Fatal Error</h3>";
    echo "<pre>";
    print_r($e->getMessage());
    echo $e;
    echo "</pre>";
}