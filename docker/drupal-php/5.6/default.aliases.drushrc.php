<?php

$aliases[isset($_SERVER['PHP_SITE_NAME_FT']) ? $_SERVER['PHP_SITE_NAME_FT'] : 'ft'] = array(
  'root' => '/var/www/html/' . (isset($_SERVER['PHP_DOCROOT']) ? $_SERVER['PHP_DOCROOT'] : ''),
  'uri' => isset($_SERVER['PHP_HOST_NAME_FT']) ? $_SERVER['PHP_HOST_NAME_FT'] : 'ft.home:7000',
);
$aliases[isset($_SERVER['PHP_SITE_NAME_TAR']) ? $_SERVER['PHP_SITE_NAME_TAR'] : 'tar'] = array(
  'root' => '/var/www/html/' . (isset($_SERVER['PHP_DOCROOT']) ? $_SERVER['PHP_DOCROOT'] : ''),
  'uri' => isset($_SERVER['PHP_HOST_NAME_TAR']) ? $_SERVER['PHP_HOST_NAME_TAR'] : 'tar.home:7000',
);
