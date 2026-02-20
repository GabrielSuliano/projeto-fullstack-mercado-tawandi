<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mercado Tawandi | Curadoria Premium</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/tawandi.css" />
  <link rel="stylesheet" href="assets/css/app.css" />
</head>
<body>
  <header class="topo vidro">
    <div class="container linha entre centro-v">
      <div class="marca">
        <span class="marca-chip">MERCADO</span>
        <h1>Mercado Tawandi</h1>
      </div>
      <nav class="menu linha centro-v gap-sm">
        <a href="#catalogo">Catalogo</a>
        <a href="#vantagens">Vantagens</a>
        <?php if ($usuario && (int)($usuario['eh_admin'] ?? 0) === 1): ?>
          <a href="admin.php">Painel Admin</a>
        <?php endif; ?>
        <?php if ($usuario): ?>
          <span class="usuario-logado">Ola, <?php echo htmlspecialchars($usuario['nome']); ?></span>
        <?php endif; ?>
        <?php if (!$usuario): ?>
          <a class="btn btn-secundario" href="login.php">Entrar</a>
          <a class="btn" href="registro.php">Criar conta</a>
        <?php else: ?>
          <button class="btn" id="botaoSair">Sair</button>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-grid">
        <div>
          <p class="tag">Curadoria de mercado premium</p>
          <h2>Seu mercado inteligente, elegante e sem atrito.</h2>
          <p class="sub">
            Produtos selecionados com padrao alto de qualidade. Compre rapido, acompanhe sua experiencia e monte pedidos com acabamento profissional.
          </p>
          <a class="btn btn-lg" href="#catalogo">Explorar produtos</a>
        </div>
        <div class="painel">
          <h3>Experiencia Tawandi</h3>
          <ul>
            <li>Entrega com janela inteligente</li>
            <li>Selecao premium por categoria</li>
            <li>Checkout direto pela API</li>
          </ul>
        </div>
      </div>
    </section>

    <section id="catalogo" class="sessao container">
      <div class="titulo-sessao linha entre centro-v">
        <h3>Catalogo do mercado</h3>
        <span class="pill">React no carrinho</span>
      </div>
      <div id="app-react"></div>
    </section>

    <section id="vantagens" class="sessao gradiente">
      <div class="container cards-3">
        <article class="card-fino">
          <h4>Qualidade Curada</h4>
          <p>Mix orientado por valor real: menos ruido, mais produto certo.</p>
        </article>
        <article class="card-fino">
          <h4>Fluxo Seguro</h4>
          <p>Login com sessao no backend e senha protegida por hash.</p>
        </article>
        <article class="card-fino">
          <h4>Arquitetura Limpa</h4>
          <p>Pasta separada por dominio, variaveis legiveis e comentarios objetivos.</p>
        </article>
      </div>
    </section>
  </main>

  <footer class="rodape">
    <div class="container linha entre centro-v">
      <p>Mercado Tawandi (c) <?php echo date('Y'); ?></p>
      <small>Projeto completo em portugues</small>
    </div>
  </footer>

  <script>
    window.APP_USUARIO = <?php echo json_encode($usuario, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
  </script>
  <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
  <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
  <script src="assets/js/app.js"></script>
  <script type="text/babel" src="assets/js/store-react.js"></script>
</body>
</html>
