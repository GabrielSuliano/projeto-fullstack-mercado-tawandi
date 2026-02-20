<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;

if (!$usuario) {
  header('Location: login.php');
  exit;
}

if ((int)($usuario['eh_admin'] ?? 0) !== 1) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Painel Admin | Mercado Tawandi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/tawandi.css" />
  <link rel="stylesheet" href="assets/css/app.css" />
</head>
<body>
  <header class="topo vidro">
    <div class="container linha entre centro-v">
      <div class="marca">
        <span class="marca-chip">ADMIN</span>
        <h1>Painel Mercado Tawandi</h1>
      </div>
      <nav class="menu linha centro-v gap-sm">
        <a href="index.php">Voltar para loja</a>
        <span class="usuario-logado"><?php echo htmlspecialchars($usuario['nome']); ?></span>
        <button class="btn" id="botaoSair">Sair</button>
      </nav>
    </div>
  </header>

  <main class="sessao container">
    <section class="admin-grid">
      <article class="card-fino">
        <h3>Novo produto</h3>
        <p>Preencha os dados e salve para publicar no catalogo.</p>
        <form id="formProduto" class="form-grid">
          <input type="hidden" name="id" />
          <label>Nome
            <input type="text" name="nome" required />
          </label>
          <label>Categoria
            <input type="text" name="categoria" required />
          </label>
          <label>Descricao
            <input type="text" name="descricao" required />
          </label>
          <label>Preco
            <input type="number" name="preco" min="0.01" step="0.01" required />
          </label>
          <label>URL da imagem
            <input type="url" name="imagem" required />
          </label>
          <label>Status
            <select name="ativo" class="input-select">
              <option value="1">Ativo</option>
              <option value="0">Inativo</option>
            </select>
          </label>
          <div class="linha gap-sm">
            <button type="submit" class="btn">Salvar produto</button>
            <button type="button" class="btn btn-secundario" id="botaoCancelarEdicao">Cancelar</button>
          </div>
        </form>
        <p class="mensagem" id="mensagemAdmin"></p>
      </article>

      <article class="card-fino">
        <h3>Produtos cadastrados</h3>
        <div class="tabela-wrap">
          <table class="tabela-admin" id="tabelaProdutos">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Preco</th>
                <th>Status</th>
                <th>Acoes</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </article>
    </section>
  </main>

  <script>
    window.APP_USUARIO = <?php echo json_encode($usuario, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
  </script>
  <script src="assets/js/app.js"></script>
  <script src="assets/js/admin.js"></script>
</body>
</html>
