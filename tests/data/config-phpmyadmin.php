<?php
declare(strict_types=1);
$i = 1;
$cfg['Servers'][$i]['AllowNoPassword'] = true;
$cfg['Servers'][$i]['host']          = 'mariadb';
$cfg['Servers'][$i]['user']          = 'root';
$cfg['Servers'][$i]['password']      = '';
$cfg['Servers'][$i]['auth_type']     = 'config';
$i++;
