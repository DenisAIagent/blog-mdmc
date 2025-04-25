<?php

// ** Configuration MySQL - via Railway ** //
define( 'DB_NAME', getenv('MYSQLDATABASE') );
define( 'DB_USER', getenv('MYSQLUSER') );
define( 'DB_PASSWORD', getenv('MYSQLPASSWORD') );
define( 'DB_HOST', getenv('MYSQLHOST') . ':' . getenv('MYSQLPORT') );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ** Clés de sécurité - générées via wordpress.org ** //
define('AUTH_KEY',         'c}8JDK)iS^hS+sw-#VZ;Y;4$+.BO,Xz#N-YIk:$kuNnWDRX-`KMyIdl|=Se,|Wvv');
define('SECURE_AUTH_KEY',  'UA_KcY7UyrvF{61F|5~rinmqW|sr *FD2.Ol~dV-YibLv(&:KLc*+uI6pUR+#+Lu');
define('LOGGED_IN_KEY',    ',f4yAIN;Gv^^0s-Hx1#(@>[(/IA#DZoTE[$uVLJ)`|+z& +1]kJe!3R|{{_sM{zj');
define('NONCE_KEY',        '!LwNl=Q*Y?-j7vl+ytQ~cVi:46J1/}Z-B9WsnAs]7{B4g9v0r]?1N,z:UUKP/?vP');
define('AUTH_SALT',        '_+rYOPiI)jjA]TphK%&.z(OaOA,F[:=8ldB.41mscw0}-}>i6`x_i}(WHqa3YSx%');
define('SECURE_AUTH_SALT', 'Mmz;,nnt-i3,K=x4MRSHi2+))Ds}/CF7(lXsjy|+5Z%aKw>A[MTl=c(W*[v^srh1');
define('LOGGED_IN_SALT',   'QhVcKW|Duyx n/>-U;=?U+s=eJ8rO%dT-}!a3>DN*$plPPzTfhg|GGt@*(!$u#J3');
define('NONCE_SALT',       '^:mZFv]so/m,Z{=7uK9L%x.~V_?=|L1]OY1&o[2X0_OoQD;o%Jj?hx-O@J)piM3}');

// ** Préfixe des tables WordPress ** //
$table_prefix = 'wp_';

// ** Mode debug WordPress ** //
define( 'WP_DEBUG', false );

// ** Forcer les URLs si besoin (décommenter si tu veux forcer l’URL Railway) **
// define( 'WP_HOME', 'https://blog-mdmc.up.railway.app' );
// define( 'WP_SITEURL', 'https://blog-mdmc.up.railway.app' );

// ** Chemin absolu vers le dossier WordPress ** //
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

// ** Charge les fichiers de configuration WordPress ** //
require_once ABSPATH . 'wp-settings.php';
