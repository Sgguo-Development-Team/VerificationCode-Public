<?php
// 每天射8000L, 这才是好的 Helper 类!
namespace CumInYourAss\Helper;

use CURLFile;
use GDText\Box;
use GDText\Color;
use mysqli;

// 我们都喜欢 Composer, 简直太好用了！
require 'vendor/autoload.php';

class GetImage
{
    private $DbConfig = 'something you should not known';
    private $Cache = ['t' => '默认值'];

    public static function uploadFilesToWeb($file)
    {
        $ch = curl_init('https://service-picbed.sgguo.com/api/v1/upload');
        curl_setopt_array($ch,
            [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Authorization: Bearer 1|fduYqgOHnR6aC77ZEtTyaaWKEGolnXO4q9yTSNkT', 'Content-Type: multipart/form-data'], CURLOPT_POST => true, CURLOPT_POSTFIELDS => ['file' => new CURLFile($file)], CURLOPT_TIMEOUT => 20, CURLOPT_SSL_VERIFYPEER => false]);
        $result = curl_exec($ch);
        curl_close($ch);
        unlink($file);
        return $result;
    }

    public function Run($config)
    {
        switch ($config) {
            case 'Chemical':
                $results = $this->SqlQuery('SELECT * FROM `sd_vcode` ORDER BY rand() LIMIT 1');
                ob_end_clean();
                return ['ID'=> $results['id'], 'Type' => 'Chemical', 'Value' => "https://www.chemicalbook.com/CAS/GIF/" . $results["cas"] . ".gif", 'Question' => '请输入上图物质的分子式', 'Answer' => md5($results["answer"])];
            case 'History':
                $results = $this->SqlQuery('SELECT * FROM `event` ORDER BY rand() LIMIT 1');
                // 获取事件类型
                switch ($results['y']) {
                    case '0':
                        $this->Cache['t'] = "大事件发生";
                        break;
                    case '1':
                        $this->Cache['t'] = "人物出生";
                        break;
                    case '2':
                        $this->Cache['t'] = "人物逝世";
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
                $box->setFontFace(__DIR__ . "/Fonts.otf");
                $box->setFontColor(new Color(0, 0, 0));
                $box->setTextShadow(new Color(0, 0, 0, 50), 0, 0);
                $box->setFontSize(28);
                $box->setLineHeight(1.5);
                $box->setBox(20, 20, 460, 460);
                $box->draw($this->Cache['t'] . ":\n" . $results['p']);
                $key = time();
                $this->writeFiles($im,'caches/' . $key . '.png');
                return ['ID'=> $results['id'], 'Type' => 'History', 'Value' => 'caches/' . $key . '.png', 'Question' => '请输入上图事件发生日期', 'Note' => "格式例如：11451400，公元前请加 '-'表示", 'Answer' => md5($year . $m . $d)];
            default:
                die("里奶奶的, 为什么不传实参！");
        }
    }

    private function SqlQuery($sql)
    {
        $Db = new mysqli($this->DbConfig['servername'], $this->DbConfig['username'], $this->DbConfig['password'], $this->DbConfig['database'], $this->DbConfig['port']);
        if ($Db->connect_error) die("致命性错误" . $Db->connect_error);
        $result = $Db->query($sql);
        // 输出数据
        while ($row = $result->fetch_assoc()) {
            $arr = $row;
        }
        $Db->close();
        return $arr;
    }

    private function writeFiles($file, $path)
    {
        imagepng($file, $path, 9);
        // GC 库写 Image
    }
}