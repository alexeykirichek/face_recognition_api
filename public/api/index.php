<?php

include ($_SERVER["DOCUMENT_ROOT"].'/backend/functions.php');

$content=file_get_contents('php://input');

if (!empty($content)) {
    loggingAppData('Получен новый запрос, сохраняю его в лог и начинаю обработку.', 'DEBUG', $logging='app');
    saveInputData($content, 'api');
    $data = json_decode($content, true);
    if (isset($data['image'])) {
        /* Verification */
        loggingAppData('Запрос идентифицирован как верификация (одно изображение).', 'DEBUG', $logging='app');

        $codingData = $data['image'];
        $dataStrlen = strlen($codingData);
        $pos_base64 = strpos($codingData, 'base64,');
        $codingPrefix = substr($codingData, 0, $pos_base64+7);
        $codingImage = substr($codingData, $pos_base64+7, $dataStrlen-($pos_base64+7));
        $pos_point = strpos($codingPrefix, ';');
        $imageTypeTemp = substr($codingPrefix, 0, $pos_point);
        $imageType = substr($imageTypeTemp, 11, strlen($imageTypeTemp));

        $image = file_put_contents($_SERVER["DOCUMENT_ROOT"].'/images/tempForVerification.'.$imageType, base64_decode($codingImage));
        $returnAnswer = ['image'=>$codingData, 'res'=>''];
        if ($image) {
            loggingAppData('Сохранил изображение ('.$_SERVER["DOCUMENT_ROOT"].'/images/tempForVerification.'.$imageType.'), отправляю его на проверку.', 'DEBUG', $logging='app');
            $result = exec('python3 '.$_SERVER["DOCUMENT_ROOT"].'/verification.py '.$_SERVER["DOCUMENT_ROOT"].'/images/tempForVerification.'.$imageType.' 2>&1'); // Запускаю скрипт
            $returnAnswer['res']=$result;
            $returnAnswerEncode = json_encode($returnAnswer,JSON_UNESCAPED_SLASHES);
            if ($result=='True') {
                loggingAppData('Изображение обработано корректно, результат положительный', 'DEBUG', $logging='app');
            } elseif ($result=='False') {
                loggingAppData('Изображение обработано корректно, результат отрицательный', 'DEBUG', $logging='app');
            } else {
                loggingAppData('Изображение обработать не удалось, ошибка: '.$result, 'ERROR', $logging='app');
            }
            print_r($returnAnswerEncode);
        } else {
            $returnAnswer['res']='False';
            $returnAnswerEncode = json_encode($returnAnswer,JSON_UNESCAPED_SLASHES);
            loggingAppData('Не удалось записать изображение ('.$_SERVER["DOCUMENT_ROOT"].'/images/tempForVerification.'.$imageType.').', 'ERROR', $logging='app');
            print_r($returnAnswerEncode);
        }

    } elseif (isset($data['image_1'])&&isset($data['image_2'])) {
        /* Compare */
        loggingAppData('Запрос идентифицирован как сравнение (два изображения).', 'DEBUG', $logging='app');

        $codingData_1 = $data['image_1'];
        $codingData_2 = $data['image_2'];
        $dataStrlen_1 = strlen($codingData_1);
        $dataStrlen_2 = strlen($codingData_2);
        $pos_base64_1 = strpos($codingData_1, 'base64,');
        $pos_base64_2 = strpos($codingData_2, 'base64,');
        $codingPrefix_1 = substr($codingData_1, 0, $pos_base64_1+7);
        $codingPrefix_2 = substr($codingData_2, 0, $pos_base64_2+7);
        $codingImage_1 = substr($codingData_1, $pos_base64_1+7, $dataStrlen_1-($pos_base64_1+7));
        $codingImage_2 = substr($codingData_2, $pos_base64_2+7, $dataStrlen_2-($pos_base64_2+7));
        $pos_point_1 = strpos($codingPrefix_1, ';');
        $pos_point_2 = strpos($codingPrefix_2, ';');
        $imageTypeTemp_1 = substr($codingPrefix_1, 0, $pos_point_1);
        $imageTypeTemp_2 = substr($codingPrefix_2, 0, $pos_point_2);
        $imageType_1 = substr($imageTypeTemp_1, 11, strlen($imageTypeTemp_1));
        $imageType_2 = substr($imageTypeTemp_2, 11, strlen($imageTypeTemp_2));

        $image_1 = file_put_contents($_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_1.'.$imageType_1,base64_decode($codingImage_1));
        $image_2 = file_put_contents($_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_2.'.$imageType_2,base64_decode($codingImage_2));

        if ($image_1 && $image_2) {
            loggingAppData('Сохранил изображения ('.$_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_1.'.$imageType_1.')+('.$_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_2.'.$imageType_2.'), отправляю их на сравнение.', 'DEBUG', $logging='app');
            $result = exec('python3 '.$_SERVER["DOCUMENT_ROOT"].'/compare.py '.$_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_1.'.$imageType_1.' '.$_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_2.'.$imageType_2.' 0.6 2>&1'); // Запускаю скрипт
            $returnAnswer['res']=$result;
            $returnAnswerEncode = json_encode($returnAnswer);
            if ($result=='True') {
                loggingAppData('Изображения сравнил корректно, результат положительный', 'DEBUG', $logging='app');
            } elseif ($result=='False') {
                loggingAppData('Изображения сравнил корректно, результат отрицательный', 'DEBUG', $logging='app');
            } else {
                loggingAppData('Изображения сравнить не удалось, ошибка: '.$result, 'ERROR', $logging='app');
            }
            print_r($returnAnswerEncode);
        } else {
            $returnAnswer['res']='False';
            $returnAnswerEncode = json_encode($returnAnswer);
            loggingAppData('Не удалось записать изображения ('.$_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_1.'.$imageType_1.')+('.$_SERVER["DOCUMENT_ROOT"].'/images/tempForCompare_2.'.$imageType_2.').', 'ERROR', $logging='app');
            print_r($returnAnswerEncode);
        }

    } else {
        /* Unknown */
        $returnAnswer['res']='no data';
        $returnAnswerEncode = json_encode($returnAnswer);
        loggingAppData('Запрос не идентифицирован.', 'DEBUG', $logging='app');
        print_r($returnAnswerEncode);
    }

    loggingAppData('Закончил обработку запроса.', 'DEBUG', $logging='app');
} else {
    $returnAnswer['res']='empty response';
    $returnAnswerEncode = json_encode($returnAnswer);
    loggingAppData('Получен пустой запрос, не обрабатываю его', 'DEBUG', $logging='app');
    print_r($returnAnswerEncode);
}