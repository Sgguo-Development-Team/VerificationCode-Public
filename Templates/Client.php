<!doctype html>
<!-- 仅为简易的实现 -->
<?php
require '../Core.php';

use SgguoDevelopmentTeam\App\Verification\Core\Verification;

$seed = rand(0, 1);
$Verification = new Verification('config.ini');
$result = $Verification->run('History');
if ($seed) {
    $src = $result['Value'];
} else {
    $File = json_decode($Verification->uploadImage($result['Value']));
    $src = $File['data']['links']['url'];
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>客户端实现</title>
</head>
<body>
<img src="<?php echo $src ?>" alt="Verification">
</body>
</html>