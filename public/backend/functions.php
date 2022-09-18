<?php

/* Набор функций для работы */

function translit($value) {
    /* Транслитерация текста */
    $converter = array(
        'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
        'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
        'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
        'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
        'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
        'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
        'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

        'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
        'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
        'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
        'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
        'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
        'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
        'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
    );

    $value = strtr($value, $converter);
    return $value;
}

function loggingAppData($data_rus, $level, $logging='app') {
    /* Логирование работы сервиса */
    $data=translit($data_rus);
    $dateAppLog = new DateTime(); // Сохраняю текущую дату
    $dateAppLog=$dateAppLog->format('d_m_Y'); // Изменяю ее формат
    $dateTimeAppLog = new DateTime(); // Сохраняю текущую дату
    $dateTimeAppLog=$dateTimeAppLog->format('Y-m-d H:i:s'); // Изменяю ее формат
    $file = $_SERVER["DOCUMENT_ROOT"].'/logs/'.$logging.'Data_'.$dateAppLog.'.log'; // Путь к файлу с логом
    $current= '['.$dateTimeAppLog.']'.' '.'APP:'.$level.':'.' '.$data."\n"; // Строка с данными
    file_put_contents($file, $current, FILE_APPEND); // Дописать в файл
}

function saveInputData($body, $source) {
    /* Логирование полученных сырых данных */
    $dataWebhookLog = new DateTime(); // Сохраняю текущую дату
    $dataWebhookLog=$dataWebhookLog->format('d_m_Y'); // Изменяю ее формат
    $file = $_SERVER["DOCUMENT_ROOT"].'/logs/'.$source.'InputData_'.$dataWebhookLog.'.log'; // Путь к файлу с логом
    $current= "//----------NEW-INPUT----------//"."\n".$body."\n"; // Строка с данными и разделителем
    file_put_contents($file, $current, FILE_APPEND); // Дописать в файл
}
