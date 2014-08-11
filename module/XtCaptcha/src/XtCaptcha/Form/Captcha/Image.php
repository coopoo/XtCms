<?php
/**
 * @Created by PhpStorm.
 * @Project: XtCms
 * @User: Coopoo
 * @Copy Right: 2014
 * @Date: 2014-08-08
 * @Time: 10:34
 * @QQ: 259522
 * @FileName: Image.php
 */

namespace XtCaptcha\Form\Captcha;


use Zend\Cache\Exception\InvalidArgumentException;
use Zend\Captcha\AbstractWord;
use Zend\Captcha\Exception\ExtensionNotLoadedException;
use Zend\Captcha\Exception\ImageNotLoadableException;
use Zend\Stdlib\ErrorHandler;

/**
 * Class Image
 * @package XtCaptcha\Form
 */
class Image extends AbstractWord
{
    /**
     * @var string
     */
    protected $imgUrl = 'Xt_Captcha';
    /**
     * Image width
     *
     * @var int
     */
    protected $width = 200;

    /**
     * @var int
     */
    protected $wordlen = 5;

    /**
     * Image height
     *
     * @var int
     */
    protected $height = 50;

    /**
     * Font size
     *
     * @var int
     */
    protected $fontSize = 16;

    /**
     * Image font file
     *
     * @var string
     */
    protected $font;

    /**
     * @var string
     */
    private $suffix = '.png'; // 图片类型
    /**
     * @var bool
     */
    private $pixLine = true; // 是否加干扰弧线
    /**
     * Image to use as starting point
     * Default is blank image. If provided, should be PNG image.
     *
     * @var string
     */
    protected $startImage;

    /**
     * How long to keep generated images
     *
     * @var int
     */
    protected $expiration = 300;

    /**
     * Number of noise dots on image
     * Used twice - before and after transform
     *
     * @var int
     */
    protected $dotNoiseLevel = 100;

    /**
     * Number of noise lines on image
     * Used twice - before and after transform
     *
     * @var int
     */
    protected $lineNoiseLevel = 5;

    /**
     * @var
     */
    private $imgAlt;
    /**
     * @是否添加边框
     *
     * @var bool
     */
    protected $flag = true;

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        if (!extension_loaded("gd")) {
            throw new ExtensionNotLoadedException("Image CAPTCHA requires GD extension");
        }

        if (!function_exists("imagepng")) {
            throw new ExtensionNotLoadedException("Image CAPTCHA requires PNG support");
        }

        if (!function_exists("imageftbbox")) {
            throw new ExtensionNotLoadedException("Image CAPTCHA requires FT fonts support");
        }
        parent::__construct($options);
    }

    /**
     * @return mixed
     */
    public function getImgAlt()
    {
        return $this->imgAlt;
    }

    /**
     * @param mixed $imgAlt
     *
     * @return $this;
     */
    public function setImgAlt($imgAlt)
    {
        $this->imgAlt = $imgAlt;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     *
     * @return $this;
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartImage()
    {
        return $this->startImage;
    }

    /**
     * @param string $startImage
     *
     * @return $this;
     */
    public function setStartImage($startImage)
    {
        $this->startImage = $startImage;
        return $this;
    }

    /**
     * @return int
     */
    public function getDotNoiseLevel()
    {
        return $this->dotNoiseLevel;
    }

    /**
     * @param int $dotNoiseLevel
     * @return $this
     */
    public function setDotNoiseLevel($dotNoiseLevel)
    {
        $this->dotNoiseLevel = $dotNoiseLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @param int $expiration
     * @return $this
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param int $fontSize
     *
     * @return $this;
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isFlag()
    {
        return $this->flag;
    }

    /**
     * @param boolean $flag
     * @return $this
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
        return $this;
    }

    /**
     * @return string
     */
    public function getFont()
    {
        if (!$this->font) {
            $this->font = __DIR__ . '/World_Colors.ttf';
        }
        return $this->font;
    }

    /**
     * @param string $font
     * @return $this
     */
    public function setFont($font)
    {
        $this->font = $font;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * @param string $imgUrl
     * @return $this
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getLineNoiseLevel()
    {
        return $this->lineNoiseLevel;
    }

    /**
     * @param int $lineNoiseLevel
     *
     * @return $this
     */
    public function setLineNoiseLevel($lineNoiseLevel)
    {
        $this->lineNoiseLevel = $lineNoiseLevel;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPixLine()
    {
        return $this->pixLine;
    }

    /**
     * @param boolean $pixLine
     *
     * @return $this;
     */
    public function setPixLine($pixLine)
    {
        $this->pixLine = $pixLine;
        return $this;
    }

    /**
     * Generate captcha
     *
     * @return string captcha ID
     */
    public function generate()
    {
        $word = $this->generateWord();
        $this->setWord(md5(strtolower($word)));
        $this->generateImage($word);
    }

    /**
     * @param $word
     * @throws mixed
     */
    protected function generateImage($word)
    {
        $font = $this->getFont();

        $w = $this->getWidth();
        $h = $this->getHeight();

        //创建内存图
        if (empty($this->startImage)) {
            $img = imagecreatetruecolor($w, $h);
            $bg = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255)); // 给画布加上背景颜色
            // 填充
            imagefill($img, 0, 0, $bg);
        } else {
            // Potential error is change to exception
            ErrorHandler::start();
            $img = imagecreatefrompng($this->startImage);
            $error = ErrorHandler::stop();
            if (!$img || $error) {
                throw new ImageNotLoadableException(
                    "Can not load start image '{$this->startImage}'", 0, $error
                );
            }
            $w = imagesx($img);
            $h = imagesy($img);
        }
        //边框
        if ($this->flag) {
            // 黑色,边框
            $flagBg = imagecolorallocate($img, 15, 142, 183);
            imagerectangle($img, 0, 0, $w - 1, $h - 1, $flagBg);
        }

        //写字
        $word = str_split($word);
        $fontSize = $this->getFontSize();
        $wordLen = $this->getWordlen();
        $c = 0 - $fontSize;
        for ($i = 1; $i <= $wordLen; $i++) {
            $fontSize = $fontSize + $this->randomSize();
            $f_color = imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100)); // 字体颜色
            $len = ($w / $wordLen) * $i - $fontSize;
            $start = $fontSize * 1.2 + $c;
            if ($start < $len) {
                $c = mt_rand($start, $len);
            } else {
                $c = mt_rand($len, $start);
            }


            imagettftext($img, $fontSize, 345 + mt_rand(0, 40), $c, mt_rand($fontSize, $h - 5), $f_color, $font, $word [$i - 1]); // 将取出的字符写在画布上
        }

        // 画点
        for ($i = 0; $i < $this->dotNoiseLevel; $i++) {
            $p_color = imagecolorallocate($img, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200)); // 像素点颜色
            imagesetpixel($img, mt_rand(0, $w), mt_rand(0, $h), $p_color); // 给画布加上点
        }

        // 画线
        for ($i = 0; $i < $this->lineNoiseLevel; $i++) {
            $l_color = imagecolorallocate($img, mt_rand(130, 170), mt_rand(130, 170), mt_rand(130, 170)); // 线颜色
            imageline($img, mt_rand(-10, $w + 10), mt_rand(-10, $h + 10), mt_rand(-10, $w + 10), mt_rand(-10, $h + 10), $l_color); // 给画面加上线条
        }

        if ($this->pixLine) {
            // 画弧线1
            $r_color = imagecolorallocate($img, mt_rand(60, 130), mt_rand(60, 130), mt_rand(60, 130)); // 像素点颜色
            $rand = mt_rand(5, 15);
            $rand1 = mt_rand(10, 20);
            $rand2 = mt_rand(5, 15);
            $y = 0;
            for ($yy = $rand; $yy <= +$rand + 1; $yy++) {
                for ($px = -$w / 2; $px <= $w / 2; $px = $px + 0.1) {
                    $x = $px / $rand1;

                    if ($x != 0) {
                        $y = sin($x);
                    }
                    $py = $y * $rand2;
                    imagesetpixel($img, $px + $w / 2, $py + $yy, $r_color);
                }
            }

            // 画弧线2
            $r_color = imagecolorallocate($img, mt_rand(30, 100), mt_rand(30, 100), mt_rand(30, 100)); // 像素点颜色
            $rand = mt_rand(25, 30);
            $rand1 = mt_rand(15, 25);
            $rand2 = mt_rand(15, 20);
            for ($yy = $rand; $yy <= +$rand + 1; $yy++) {
                for ($px = -$w / 2; $px <= $w / 2; $px = $px + 0.1) {
                    $x = $px / $rand1;
                    if ($x != 0) {
                        $y = sin($x);
                    }
                    $py = $y * $rand2;
                    imagesetpixel($img, $px + $w / 2, $py + $yy, $r_color);
                }
            }
        }

        $this->headerImg($img);
    }
    /**
     * @param $img
     */
    private function headerImg($img)
    {
        ob_clean(); // 清空缓存
        // 输出图片
        header("Cache-Control: no-cache\r\n");
        header("Pragma: no-cache\r\n");
        header("Expires:0\r\n");
        switch ($this->suffix) {
            case 'png' :
                header("Content-type: image/png"); // 告诉浏览器当前文件产生的结果以png形式进行输出
                imagepng($img); // 将图像以png形式输出
                break;
            case 'jpg' :
                header('Content-type: image/jpeg');
                imagejpeg($img);
                break;
            case 'gif' :
                header('Content-type: image/gif');
                imagegif($img);
                break;
            default :
                header("Content-type: image/png"); // 告诉浏览器当前文件产生的结果以png形式进行输出
                imagepng($img); // 将图像以png形式输出
                break;
        }
        imagedestroy($img);
    }

    /**
     * @return float
     */
    private function randomSize()
    {
        return mt_rand(100, 400) / 100;
    }

    /**
     * @return \Zend\Session\Container
     */
    public function getSession()
    {
        if (!isset($this->session) || (null === $this->session)) {
            //$id = $this->getId();
            if (!class_exists($this->sessionClass)) {
                throw new InvalidArgumentException("Session class $this->sessionClass not found");
            }
            $this->session = new $this->sessionClass('Zend_Form_Captcha_Word');
            $this->session->setExpirationHops(1, null);
            $this->session->setExpirationSeconds($this->getTimeout());
        }
        return $this->session;
    }

    /**
     * @param mixed $value
     * @param null $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $value = md5(strtolower($value));
        $this->setValue((string)$value);
        if ($value !== $this->getWord()) {
            $this->error(self::BAD_CAPTCHA);
            return false;
        }
        return true;
    }

}