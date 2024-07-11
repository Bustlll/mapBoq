![image](https://github.com/Bustlll/mapBoq/assets/57551687/478d396e-b054-43c3-9f4d-70d1ed1816d6)

# Localhost Requirements

-cacert-2024-07-02 from https://curl.se/docs/caextract.html

-main: PHP 8.3.9

-Wampserver https://wampserver.aviatechno.net w/PHP 8.3.6

-composer from https://getcomposer.org/

-edit the php.ini file from the wampserver: 

;curl.cainfo = "C:\php-8.3.9\cacert-2024-07-02.pem" and untick the ;

# Clone the repo
$git clone https://github.com/Bustlll/mapBoq.git

$ cd testBlowUp
$ cd tbu01
$ composer install
$ php artisan serve
Open https://localhost:8000 with browser.

