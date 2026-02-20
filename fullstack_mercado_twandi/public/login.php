<?php
session_start();
if (isset($_SESSION['usuario'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Entrar | Mercado Tawandi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/tawandi.css" />
  <link rel="stylesheet" href="assets/css/app.css" />
</head>
<body class="auth-body">
  <main class="auth-wrap">
    <section class="auth-card">
      <a class="link-voltar" href="index.php">&larr; Voltar ao mercado</a>
      <h1>Entrar na sua conta</h1>
      <p>Use seu email e senha para continuar.</p>
      <form id="formLogin" class="form-grid">
        <label>Email
          <input type="email" name="email" required placeholder="voce@exemplo.com" />
        </label>
        <label>Senha
          <input type="password" name="senha" required placeholder="******" />
        </label>
        <button class="btn" type="submit">Entrar</button>
      </form>
      <p class="mensagem" id="mensagem"></p>
      <p class="apoio">Ainda nao tem conta? <a href="registro.php">Criar conta</a></p>
      <p class="demo">Demo: admin@mercadotawandi.com / 123456</p>
    </section>
  </main>

  <script src="assets/js/app.js"></script>
  <script>
    window.tawandiAuth.iniciarLogin();
  </script>
</body>
</html>
