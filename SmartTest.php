<?php

class SmartTest {

    /**
     * Hata durumunda rapor için e-posta gönderilecek.
     * @var type 
     */
    private $report_to = "bilgi@kitabyte.com";

    /**
     * Testi çalıştırmak için kullanıcı adı şifre ister
     * @var type 
     */
    protected $enable_auth;

    /**
     * $enable_auth geçerliyse bu değişkenler kullanılır
     * @var type 
     */
    protected $username = "admin", $password = "admin";

    /**
     * Testi sonlandırmak gerektiğini belirten değişken, flag.
     * @var type 
     */
    public $stop = false;

    /**
     * Rastgele üretilecek değişken
     * @var type 
     */
    public $rando = "abc123";

    /**
     * Test fonksiyonlarını tutan dizi.
     * @var type 
     */
    private $test_functions = array();

    /**
     * SmartTest nesnesi.
     * @var type 
     */
    private static $instance;

    /**
     * cURL nesnesi
     * @var type 
     */
    private $ch;

    function __construct() {
        
    }

    /**
     * Rastgele veri üretir
     * @param type $len Veri uzunluğu
     * @return type
     */
    private function rando($len = 18) {
        $alpha_numeric = str_shuffle(str_repeat(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 5));
        $r = rand(0, strlen($alpha_numeric) - $len);
        return substr($alpha_numeric, $r, $len);
    }

    /**
     * Testi durdurur. Bundan sonraki fonksiyonlar çalıştırılmaz.
     */
    public function stop() {
        $this->stop = true;
    }

    /**
     * cURL ile POST metodu kullanarak sayfa verisini alır
     * @param type $url
     * @param type $fields
     * @return type
     */
    public function post($url, $fields = null) {
        if (!$this->ch) {
            $this->ch = curl_init();
            curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
        }
        $fields_string = "";
        if ($fields) {
            foreach ($fields as $k => $v) {
                $fields_string .= $k . '=' . $v . '&';
            }

            rtrim($fields_string, '&');
        }
        curl_setopt($this->ch, CURLOPT_POST, count($fields));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_REFERER, "{$this->domain}/health.php");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");

        $cookie_file = "tmp/cookie.txt";
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_file);

        $result = curl_exec($this->ch);
        return json_decode($result);
    }

    /**
     * cURL ile GET metodu kullanarak sayfa verisini alır.
     * @param type $url
     * @param type $fields
     * @return type
     */
    public function get($url, $fields = null) {
        if (!$this->ch) {
            $this->ch = curl_init();
            curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
        }

        $fields_string = "";
        if ($fields) {
            foreach ($fields as $k => $v) {
                $fields_string .= $k . '=' . $v . '&';
            }

            rtrim($fields_string, '&');
        }

        curl_setopt($this->ch, CURLOPT_URL, "{$url}?{$fields_string}");
        curl_setopt($this->ch, CURLOPT_REFERER, "{$this->domain}/test.php");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");

        $cookie_file = "tmp/cookie.txt";
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie_file);

        $result = curl_exec($this->ch);
        return json_decode($result);
    }

    /**
     * Verilen test fonksiyonunu çalıştırır.
     * @param String $name Testin ismi.
     * @param function $func Kontrol işlemi yapılan bir fonksiyon. True/False durumuna göre test başarılı veya değildir.
     */
    public function test($name, $func) {
        $this->test_functions[$name] = $func;
    }

    /**
     * Bir fonksiyonun varlığını kontrol eder.
     * @param String $name Testin ismi
     * @param String $funcName Kontrol edilecek fonksiyonun ismi.
     * @param type $expected Fonksiyondan dönmesi beklenen değer.
     * @return type boolean
     */
    public function test_function($name, $funcName, $expected = true) {
        $this->test_functions["Fonksiyon: $name"] = function() use ($funcName, $expected) {
                    return function_exists($funcName) === $expected;
                };
    }

    /**
     * PHP eklentisinin var olup olmadığını kontrol eder.
     * @param type $name Testin ismi.
     * @param type $extension Kontrol edilecek eklenti.
     * @param type $expected Fonksiyondan dönmesi beklenen değer.
     * @return type 
     */
    public function test_extension($name, $extension, $expected = true) {
        $this->test_functions["Eklenti: $name"] = function() use ($extension, $expected) {
                    return extension_loaded($extension) === $expected;
                };
    }

    /**
     * Bir dosyanın var olup olmadığını kontrol eder.
     * @param type $name Testin ismi.
     * @param type $file Kontrol edilecek dosya.
     * @param type $expected Fonksiyondan dönmesi beklenen değer.
     * @return type 
     */
    public function test_file_exist($name, $file, $expected = true) {
        $this->test_functions["Dosya kontrol: $name"] = function() use ($file, $expected) {
                    return file_exists($file) === $expected;
                };
    }

    /**
     * Bir dosyanın veya dizinin yazılabilir olup olmadığını kontrol eder.
     * @param type $name Testin ismi.
     * @param type $file Kontrol edilecek dosya.
     * @param type $expected Fonksiyondan dönmesi beklenen değer.
     * @return type 
     */
    public function test_writable($name, $file, $expected = true) {
        $this->test_functions["Yazma izni: $name"] = function() use ($file, $expected) {
                    return file_exists($file) === $expected;
                };
    }

    /**
     * Test fonksiyonlarını döndürür
     * @return type
     */
    public function getTestFunctions() {
        return $this->test_functions;
    }

    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new SmartTest();
            static::$instance->rando = "TEST" . static::$instance->rando();
        }
        return self::$instance;
    }

    public function print_warn($msg) {
        echo '<li class="text-warning"><i class="fa fa-hand-right-o fa-fw"></i>&nbsp; ' . $msg . '</li>';
    }

    public function print_log($msg) {
        echo '<li class="text-muted"><i class="fa fa-flag-o fa-fw"></i>&nbsp; ' . $msg . '</li>';
    }

    private function print_ok($msg) {
        echo '<li class="text-success"><i class="fa fa-check fa-fw"></i>&nbsp; ' . $msg . '</li>';
    }

    private function print_err($msg) {
        echo '<li class="text-danger"><i class="fa fa-exclamation-circle fa-fw"></i>&nbsp; ' . $msg . '</li>';
    }

    /**
     * Tanımlanan test fonksiyonlarını çalıştırıp rapor sunar.
     */
    public function run() {

        if ($enable_auth) {
            if (isset($this->username) && isset($this->password)) {
                if (!isset($_SERVER['PHP_AUTH_USER'])) {
                    header('WWW-Authenticate: Basic realm="My Realm"');
                    header('HTTP/1.0 401 Unauthorized');
                    echo 'Text to send if user hits Cancel button';
                    exit;
                } else {
                    if ($_SERVER['PHP_AUTH_USER'] != $this->username || $_SERVER['PHP_AUTH_PW'] != $this->password) {
                        header('WWW-Authenticate: Basic realm="Yetkiniz yok"');
                        header('HTTP/1.0 401 Unauthorized');
                        die("Not authorized");
                    }
                }
            }
        }

        echo "<h3>Test başladı.</h3><hr>";
        echo '<ul class="list-unstyled">';
        $start = microtime();
        $body = "<b>Aşağıdaki işlemler sırasında hata oluştu!</b>" . PHP_EOL;
        $send_report = FALSE;
        foreach ($this->test_functions as $name => $func) {
            $result = call_user_func($func);
            if ($result) {
                $this->print_ok($name);
            } else {
                $send_report = TRUE;
                $body .="&times; <span style=\"color:red;\">$name</span>" . PHP_EOL;
                $this->print_err($name);
            }
            ob_flush();
            flush();
            if ($this->stop) {
                $body .=" <span style=\"color:red;\">Test DURDURULDU</span>" . PHP_EOL;
                echo '</ul>';
                echo "<hr><h3 class=\"text-danger\">Test DURDURULDU.</h3>";
                break;
            }
        }
        curl_close($this->ch);
        $elapsed = number_format((microtime() - $start), 2);

        if (!$this->stop) {
            $body .="Test sonlandı." . PHP_EOL;
            $body .="{$elapsed} saniye sürdü" . PHP_EOL;

            echo '</ul>';
            echo "<hr><h3>Test sonlandı. <small>{$elapsed} saniye sürdü</small></h3>";
        }
        if ($send_report)
            sendMail($report_to, "Kitabyte Sistem Sağlığı Raporu", nl2br($body));
    }

    /**
     * $rando değişkenini değiştirir.
     * @param type $nr
     */
    public function setRando($nr) {
        $this->rando = $nr;
    }

}

?>
