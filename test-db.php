COPY . /var/www/html/
<?php
$host = 'mysql-production-fde2.up.railway.app';
$port = 3306;
$user = 'TON_UTILISATEUR';
$pass = 'TON_MOT_DE_PASSE';
$db   = 'railway';

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
    die('❌ Connexion échouée : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
} else {
    echo '✅ Connexion réussie à la base de données !';
}
?>
