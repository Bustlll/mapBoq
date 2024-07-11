Run on localhost requirements:

-cacert-2024-07-02 from https://curl.se/docs/caextract.html

-main: PHP 8.3.9

-Wampserver https://wampserver.aviatechno.net w/PHP 8.3.6

-composer from https://getcomposer.org/


-edit the php.ini file from the wampserver: 

curl.cainfo = "C:\php-8.3.9\cacert-2024-07-02.pem" and untick the ;
