<?php
require 'GetImage.php';

use CumInYourAss\Helper\GetImage;

$Image = new GetImage();
$Result = $Image->Run($_GET['config']);

if (
    $Result['Type'] === 'Chemical'
) {
    header('Content-type:text/json');
    echo json_encode($Result, JSON_PRETTY_PRINT);
} else if ($Result['Type'] === 'History') {
    $File = json_decode(GetImage::uploadFilesToWeb($Result['Value']));
    header('Content-type:text/json');
    echo json_encode(['ID'=> $Result['ID'], 'Type' => 'History', 'Value' => $File->{'data'}->{'links'}->{'url'}, 'Question' => $Result['Question'], 'Note' => $Result['Note'], 'Answer' => $Result['Answer']], JSON_PRETTY_PRINT);
}