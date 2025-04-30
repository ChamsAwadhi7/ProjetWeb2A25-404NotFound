<?php
require_once '../../../config.php';
require_once '../../../model/startup.php';
require_once '../../../controller/startupC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['startupName']) && isset($_POST['selectedNitroId'])) {
        $startupC = new startupC();
        $startupName = $_POST['startupName'];
        $idNitro = $_POST['selectedNitroId'];

        try {
            $startupC->affectNitro($idNitro, $startupName);
            echo "Nitro plan successfully assigned to startup: " . htmlspecialchars($startupName);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    } else {
        http_response_code(400);
        echo "Missing parameters.";
    }
}
