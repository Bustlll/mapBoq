![image](https://github.com/Bustlll/mapBoq/assets/57551687/c9e0bc7c-d923-4c8f-8003-8416f1e86951)


# Localhost Requirements

-cacert-2024-07-02 from https://curl.se/docs/caextract.html

-main: PHP 8.3.9

-Wampserver https://wampserver.aviatechno.net w/PHP 8.3.6

-composer from https://getcomposer.org/

-edit the php.ini file from the wampserver: 

;curl.cainfo = "C:\php-8.3.9\cacert-2024-07-02.pem" and untick the ;

# Clone the repo

1. $ git clone https://github.com/Bustlll/mapBoq.git


2. $ cd testBlowUp
3. $ cd tbu01
4. $ composer install
5. $ php artisan serve
6. Open https://localhost:8000 with browser.

