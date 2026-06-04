<?php
// Gestion des sessions et de l'authentification

session_start();
require_once __DIR__ . '/base_de_donnees.php';

creer_admin_si_vide();

// Vérifier si l'utilisateur est connecté
function est_connecte() {
    return !empty($_SESSION['utilisateur']);
}

// Récupérer l'utilisateur connecté
function utilisateur_connecte() {
    return $_SESSION['utilisateur'] ?? null;
}

// Vérifier si l'utilisateur est admin
function est_admin() {
    return isset($_SESSION['utilisateur']['role']) && $_SESSION['utilisateur']['role'] === 'admin';
}

// Rediriger si non connecté
function exiger_connexion($redirection = 'connexion.php') {
    if (!est_connecte()) {
        header('Location: ' . $redirection);
        exit;
    }
}

// Rediriger si non admin
function exiger_admin() {
    exiger_connexion();
    if (!est_admin()) {
        header('Location: index.php');
        exit;
    }
}

// Sauvegarder l'utilisateur en session
function sauvegarder_session($u) {
    $_SESSION['utilisateur'] = [
        'id'     => $u['id'],
        'prenom' => $u['firstName'],
        'nom'    => $u['lastName'],
        'email'  => $u['email'],
        'role'   => $u['role']   ?? 'user',
        'avatar' => $u['avatar'] ?? '',
    ];
}

// Connecter un utilisateur
function connecter_utilisateur($email, $mot_de_passe) {
    $email = strtolower(trim($email));
    if (!$email || !$mot_de_passe) {
        return ['ok' => false, 'msg' => 'Veuillez remplir tous les champs.'];
    }
    $u = trouver_utilisateur_par_email($email);
    if ($u && password_verify($mot_de_passe, $u['password'])) {
        sauvegarder_session($u);
        return ['ok' => true];
    }
    return ['ok' => false, 'msg' => 'Email ou mot de passe incorrect.'];
}

// Inscrire un nouvel utilisateur
function inscrire_utilisateur($prenom, $nom, $email, $mot_de_passe) {
    $prenom = trim($prenom);
    $nom    = trim($nom);
    $email  = strtolower(trim($email));
    if (!$prenom || !$nom) return ['ok' => false, 'msg' => 'Veuillez entrer votre nom complet.'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['ok' => false, 'msg' => 'Adresse email invalide.'];
    if (strlen($mot_de_passe) < 6) return ['ok' => false, 'msg' => 'Le mot de passe doit avoir au moins 6 caractères.'];
    if (trouver_utilisateur_par_email($email)) return ['ok' => false, 'msg' => 'Un compte existe déjà avec cet email.'];
    $id   = uniqid('pv_', true);
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    inserer_utilisateur($id, $prenom, $nom, $email, $hash);
    $nouvel_utilisateur = trouver_utilisateur_par_email($email);
    if ($nouvel_utilisateur) sauvegarder_session($nouvel_utilisateur);
    return ['ok' => true];
}

// Déconnecter l'utilisateur
function deconnecter_utilisateur() {
    $_SESSION = [];
    session_destroy();
}

// Traiter le formulaire de connexion ou d inscription
function traiter_formulaire_auth() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") return null;
    $action = $_POST["action"] ?? "";
    if ($action === "register") {
        return inscrire_utilisateur($_POST["prenom"] ?? "", $_POST["nom"] ?? "", $_POST["email"] ?? "", $_POST["password"] ?? "");
    }
    if ($action === "login") {
        $result = connecter_utilisateur($_POST["email"] ?? "", $_POST["password"] ?? "");
        if ($result["ok"]) {
            header("Location: " . ($_POST["redirect"] ?? "index.php"));
            exit;
        }
        return $result;
    }
    if ($action === "logout") {
        deconnecter_utilisateur();
        header("Location: connexion.php");
        exit;
    }
    return null;
}
