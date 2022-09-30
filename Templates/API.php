<?php
// 仅为简单的实现

declare(strict_types=1);

namespace SgguoDevelopmentTeam\App\Verification\Templates\API;

require '../Core.php';

use SgguoDevelopmentTeam\App\Verification\Core\Verification;

$Image = new Verification('config.ini');
$result = $Image->run($_GET['config']);

if ($result['Type'] === 'Chemical') {
    header('Content-type:text/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
} else if ($result['Type'] === 'History') {
    if (!empty($result)) {
        $File = json_decode($Image->uploadImage($result['Value']));
    } else die('执行遇到致命性错误');
    header('Content-type:text/json');
    echo json_encode(['ID' => $result['ID'], 'Type' => 'History', 'Value' => $File['data']['links']['url'], 'Question' => $result['Question'], 'Note' => $result['Note'], 'Answer' => $result['Answer']], JSON_PRETTY_PRINT);
} else die('未知错误');