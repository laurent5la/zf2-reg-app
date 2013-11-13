zf2-reg-app
===========

Framework:
Zend Framework 2

Install through composer:
composer.json

{
    "name": "zendframework-registration-application",
    "description": "Application for ZF2",
    "license": "BSD-3-Clause",
    "keywords": [
        "framework",
        "zf2"
    ],

    "require": {
        "php": ">=5.3.3",
        "zendframework/zendframework": "2.2.*"
    }
}


------------------------------------------------------------------
create a database 'test';

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avatar` text,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `firstname` varchar(250) DEFAULT NULL,
  `lastname` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`email`)
) ENGINE=InnoDB;

INSERT INTO `users`
(
`avatar`,
`email`,
`password`,
`firstname`,
`lastname`)
VALUES

('','demo@test.com','78c72859bf3e7b1e81e869dba47a7812:u352osFQkkKlxy789hbAEEjc9ySTN5O0',
'demo',
''
);


INSERT INTO `users`
(`avatar`,
`email`,
`password`,
`firstname`,
`lastname`)
VALUES

(
'','test@test.com','7b288e4e914bcf66c3e26e9648830c85:Nwds8FQwRI9BDpsBNSuIONayRmb3XQiz',
'test',
''
);


-------------------------------------------------------------------------

configs:

modify file:
config/autoload/global.php


return array(
    'db' => array(
        'username' => '<!-- db user -->',
        'password' => '<!-- db password -->',
    ),
);
