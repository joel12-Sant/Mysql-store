<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $usuarioValido = 'admin';
    $hashGuardado = '$2y$10$uq/g.lnNidYM92Wg/tCMJuFOvramsZhfXUis3cj5xxawVhq5RXqwC';
    if ($usuario === $usuarioValido && password_verify($password, $hashGuardado)) {
        $_SESSION['usuario'] = $usuario;
        header("Location: find.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Iniciar Sesión</h2>
  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="POST">
    <div class="mb-3">
      <label>Usuario</label>
      <input type="text" name="usuario" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
  </form>
</body>
</html>
