<?php
require_once 'php/authentification.php';
deconnecter_utilisateur();
header('Location: connexion.php');
exit;
