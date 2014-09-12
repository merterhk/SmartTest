SmartTest
=========

PHP Service Health Class

PHP ile oluşturduğunuz sitenizin özelliklerini test etmenizi kolaylaştıracak bir sınıf. Gerekli fonksiyonları tanımlayarak, fonksiyonun döndürdüğü True/False değerine göre çalışmayan özellikler rapor edilir.

# Nasıl kullanırım?
### Kurulum
```
  require 'SmartTest.php';
  $test = SmartTest::getInstance();
```
### Fonksiyon varlığını kontrol etmek:
```
  $test->test_function("gzip fonksiyonu", "gzcompress");
```
### Modül varlığını kontrol etmek:
```
  $test->test_extension("php-gd modülü", "gd");
```
### Dosya yazılabilirliğini kontrol etmek:
```
  $test->test_writable("tmp/cookie.txt dosyası yazılabilir", "tmp/cookie.txt");
```
### Dosyanın bulunduğunu kontrol etmek
```
  $test->test_file_exist("bootstrap-theme.css", "css/bootstrap-theme.css");
```
### Bir fonksiyon ile veritabanı bağlantısını kontrol etmek
Bu özellik sadece Mysqli ile bağlantı kurulurken kullanılabilir. 
```
  $test->test("Veritabanı bağlantısı", function() use ($test) {
              $con = new mysqli("localhost", "username", "password", "database");
              if (mysqli_connect_errno()) {
                  $test->stop();
                  return false;
              }
              return true;
          });
```
### Ajax ile JSON dönderen bir sayfanın verisini kontrol etmek:
```
  $test->test("Kitapçı ajax", function() use ($test) {
      $response = $test->get("http://www.kitabyte.com/ajax/bookstore");
      return count($response->bookstore) > 0;
          });
```
### GET metodu ile bir adresi kontrol etmek:
```     
  $test->test("Kullanıcı arama", function() use ($test) {
      $response = $test->get("http://www.kitabyte.com/");
      return count($response) > 0;
  });
```

### POST metodu ile bir adresi kontrol etmek: 
```
$test->test("Post ile erişmek", function () use ($test) {
            $fields = array("field1" => "data", "field2" => 123);
            $response = $test->post("http://www.kitabyte.com/test", $fields);

            if ($response->success) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        });
```

### Tüm testleri tanımladıktan sonra çalıştırmak
```
    $test->run();
```

### Rapor ekranı
```
<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kitabyte Sistem Sağlığı</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.css" rel="stylesheet"/>
    <link href="/css/bootstrap-theme.css" rel="stylesheet"/>
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <?php
    $test->run();
    ?>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
```

![Ekran görüntüsü 1](http://i.imgur.com/epKG1IX)

![Ekran görüntüsü 2](http://i.imgur.com/31Vl913)

Lisans
======
MIT Public altında lisanslanmıştır.
