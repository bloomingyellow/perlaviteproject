<?php
require_once 'php/authentification.php';
require_once 'php/produits.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'validate_coupon') {
  $code  = $_POST['code'] ?? '';
  $total = (float)($_POST['total'] ?? 0);
  echo json_encode(valider_coupon($code, $total));
  exit;
}

if ($action === 'save_order' && est_connecte()) {
  $body = json_decode(file_get_contents('php://input'), true);
  if ($body) {
    $body['userId'] = utilisateur_connecte()['id'];
    sauvegarder_commande($body);
    echo json_encode(['ok'=>true]);
  } else {
    echo json_encode(['ok'=>false,'msg'=>'Invalid data']);
  }
  exit;
}

echo json_encode(['ok'=>false,'msg'=>'Unknown action']);
