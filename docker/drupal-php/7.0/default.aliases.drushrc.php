<?php

$aliases[isset($_SERVER['PHP_SITE_NAME_RN']) ? $_SERVER['PHP_SITE_NAME_RN'] : 'rn'] = array(
  'root' => '/var/www/html/' . (isset($_SERVER['PHP_DOCROOT']) ? $_SERVER['PHP_DOCROOT'] : ''),
  'uri' => isset($_SERVER['PHP_HOST_NAME_RN']) ? $_SERVER['PHP_HOST_NAME_RN'] : 'rn.home:8000',
);
$aliases[isset($_SERVER['PHP_SITE_NAME_TT']) ? $_SERVER['PHP_SITE_NAME_TT'] : 'tt'] = array(
  'root' => '/var/www/html/' . (isset($_SERVER['PHP_DOCROOT']) ? $_SERVER['PHP_DOCROOT'] : ''),
  'uri' => isset($_SERVER['PHP_HOST_NAME_TT']) ? $_SERVER['PHP_HOST_NAME_TT'] : 'tt.home:8000',
);
$aliases[isset($_SERVER['PHP_SITE_NAME_RT']) ? $_SERVER['PHP_SITE_NAME_RT'] : 'rt'] = array(
  'root' => '/var/www/html/' . (isset($_SERVER['PHP_DOCROOT']) ? $_SERVER['PHP_DOCROOT'] : ''),
  'uri' => isset($_SERVER['PHP_HOST_NAME_RT']) ? $_SERVER['PHP_HOST_NAME_RT'] : 'rt.home:8000',
);
