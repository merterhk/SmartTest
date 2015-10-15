SmartTest
=========

PHP Service Health Class

EN:
A class to check or test web site's properties. Results will listing via true or false results of given functions.

TR:
PHP ile oluşturduğunuz sitenizin özelliklerini test etmenizi kolaylaştıracak bir sınıf. Gerekli fonksiyonları tanımlayarak, fonksiyonun döndürdüğü True/False değerine göre çalışmayan özellikler rapor edilir.

# How to use? / Nasıl kullanırım?
### Installation / Kurulum
```
  require 'SmartTest.php';
  $test = SmartTest::getInstance();
```
### Check function is exist / Fonksiyon varlığını kontrol etmek:
```
  $test->test_function("gzip function is avaliable.", "gzcompress");
```
### Check module is exist /  Modül varlığını kontrol etmek:
```
  $test->test_extension("php-gd module is exist.", "gd");
```
### Check file is writable /  Dosya yazılabilirliğini kontrol etmek:
```
  $test->test_writable("tmp/cookie.txt file is writable.", "tmp/cookie.txt");
```
### Check file is exist /  Dosyanın bulunduğunu kontrol etmek
```
  $test->test_file_exist("bootstrap-theme.css is exist.", "css/bootstrap-theme.css");
```
###  Check database connection is exist / Bir fonksiyon ile veritabanı bağlantısını kontrol etmek
This function is only for "Mysqli".
Bu özellik sadece "Mysqli" ile bağlantı kurulurken kullanılabilir. 
```
  $test->test("Veritabanı bağlantısı", function() use ($test) {
              $con = new mysqli("localhost", "username", "password", "database");
              if (mysqli_connect_errno()) {
                  $test->stop(); // Stop all test functions. Because other functions will return false.
                  return false;
              }
              return true;
          });
```
### Check response of ajax result / Ajax ile JSON dönderen bir sayfanın verisini kontrol etmek:
```
  $test->test("Kitapçı ajax", function() use ($test) {
      $response = $test->get("http://www.kitabyte.com/ajax/bookstore");
      return count($response->bookstore) > 0;
          });
```
### Check an url wia GET method / GET metodu ile bir adresi kontrol etmek:
```     
  $test->test("Kullanıcı arama", function() use ($test) {
      $response = $test->get("http://www.kitabyte.com/");
      return count($response) > 0;
  });
```

### Check an url wia POST method / POST metodu ile bir adresi kontrol etmek: 
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

### To run all test functions. / Tüm testleri tanımladıktan sonra çalıştırmak için.
```
    $test->run();
```

### Report Screen / Rapor ekranı
```
<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kitabyte System Health</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

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

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
```

<br>
<a href="http://i.imgur.com/epKG1IX"><img src="http://i.imgur.com/epKG1IX.jpg?1" title="Ekran görüntüsü 1"/></a>
<br>
<a href="http://i.imgur.com/31Vl913"><img src="http://i.imgur.com/31Vl913.jpg?1" title="Ekran görüntüsü 2"/></a>


License / Lisans
======
Bu kaynak kod MIT lisansı ile lisanslanmıştır.
This source code is licensed under MIT License.
