<?php
declare(strict_types=1);

namespace SgguoDevelopmentTeam\App\Verification\Core;

use CURLFile;
use GDText\Box;
use GDText\Color;
use mysqli;

// 我们都喜欢 Composer, 简直太好用了！
require 'vendor/autoload.php';

class Verification
{
    private string $cache;
    private array $config;

    public function __construct(?string $config)
    {
        // 解构一下 config
        $this->config = parse_ini_file($config, true);
    }

    function __toString(): string
    {
        // TODO: Implement __toString() method.
        return 'SgguoDevelopmentTeam/App/Verification/Core';
    }

    /**
     * 上传图片到图床
     * @param string|null $file
     * @return bool|string
     */
    public function uploadImage(?string $file)
    {
        $ch = curl_init('https://example');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Authorization: Something', 'Content-Type: multipart/form-data'], CURLOPT_POST => true, CURLOPT_POSTFIELDS => ['file' => new CURLFile($file)], CURLOPT_TIMEOUT => 20, CURLOPT_SSL_VERIFYPEER => false]);
        $result = curl_exec($ch);
        curl_close($ch);
        unlink($file);
        return $result;
    }

    public function run(?string $config)
    {
        switch ($config) {
            case 'Chemical':
                $results = $this->SQLQuery('SELECT * FROM `sd_vcode` ORDER BY rand() LIMIT 1');
                ob_end_clean();
                return ['ID' => $results['id'], 'Type' => 'Chemical', 'Value' => "https://www.chemicalbook.com/CAS/GIF/" . $results["cas"] . ".gif", 'Question' => '请输入上图物质的分子式', 'Answer' => md5($results["answer"])];
            case 'History':
                $results = $this->SQLQuery('SELECT * FROM `event` ORDER BY rand() LIMIT 1');
                // 获取事件类型
                switch ($results['y']) {
                    case '0':
                        $this->cache = "大事件发生";
                        break;
                    case '1':
                        $this->cache = "人物出生";
                        break;
                    case '2':
                        $this->cache = "人物逝世";
                        break;
                    default:
                        break;
                }
                $year = $results['d'];
                $year = str_replace("前", "-", $year);
                $year = str_replace("年", "", $year);
                $month = explode("月", $results['i']);
                //开始绘图
                $m = sprintf("%02d", $month[0]);
                $d = sprintf("%02d", str_replace("日", "", $month[1]));
                $width = (strlen($results['p']) >= 189) ? 500 : 250;
                $im = imagecreatetruecolor(500, $width);
                $backgroundColor = imagecolorallocate($im, 255, 255, 255);
                imagefill($im, 0, 0, $backgroundColor);
                $box = new Box($im);
                $box->setFontFace("Fonts.otf");
                $box->setFontColor(new Color(0, 0, 0));
                $box->setTextShadow(new Color(0, 0, 0, 50), 0, 0);
                $box->setFontSize(28);
                $box->setLineHeight(1.5);
                $box->setBox(20, 20, 460, 460);
                $box->draw($this->cache . ":\n" . $results['p']);
                $key = time();
                $this->writeFiles($im, 'caches/' . $key . '.png');
                return ['ID' => $results['id'], 'Type' => 'History', 'Value' => 'caches/' . $key . '.png', 'Question' => '请输入上图事件发生日期', 'Note' => "格式例如：11451400，公元前请加 '-'表示", 'Answer' => md5($year . $m . $d)];
            default:
                die("缺少配置");
        }
    }

    private function SQLQuery(?string $sql)
    {
        $Db = new mysqli($this->config['database']['servername'], $this->config['database']['username'], $this->config['database']['password'], $this->config['database']['dbname'], $this->config['database']['port']);
        if ($Db->connect_error) die($Db->connect_error);

        $result = $Db->query($sql);
        if (!empty($result)) {
            // 输出数据
            while ($row = $result->fetch_assoc()) {
                $arr = $row;
            }
            $Db->close();
        } else die('数据库无反馈');
        return $arr;
    }

    public function writeFiles($file, $path)
    {
        imagepng($file, $path, 9);
        // GD 库 Image
    }

}