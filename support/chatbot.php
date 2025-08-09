<?php
require __DIR__ . '/../vendor/autoload.php'; // Đường dẫn đến autoload.php của composer

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;

$message = trim($_POST['message'] ?? '');
$reply = 'Xin lỗi, tôi chưa hiểu ý bạn. Vui lòng hỏi lại!';

if ($message) {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/dialogflow-key.json');
$projectId = 'newagent-ckqg'; // Chỉ để Project ID, không có .json
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$sessionId = session_id();
    $languageCode = 'vi';

    $sessionsClient = new SessionsClient();
    $session = $sessionsClient->sessionName($projectId, $sessionId);

    $textInput = new TextInput();
    $textInput->setText($message);
    $textInput->setLanguageCode($languageCode);

    $queryInput = new QueryInput();
    $queryInput->setText($textInput);

    $response = $sessionsClient->detectIntent($session, $queryInput);
    $result = $response->getQueryResult();
    $reply = $result->getFulfillmentText();

    $sessionsClient->close();
}

echo $reply;