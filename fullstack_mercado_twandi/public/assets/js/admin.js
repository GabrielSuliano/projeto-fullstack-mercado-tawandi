(function () {
  const BASE_API = 'api/index.php';
  const form = document.getElementById('formProduto');
  const tabelaBody = document.querySelector('#tabelaProdutos tbody');
  const mensagem = document.getElementById('mensagemAdmin');
  const botaoCancelar = document.getElementById('botaoCancelarEdicao');
  let produtosCache = [];

  if (!form || !tabelaBody) return;

  function campo(nome) {
    return form.elements.namedItem(nome);
  }

  async function carregarProdutos() {
    const resposta = await fetch(`${BASE_API}?rota=admin.produtos.listar`, {
      credentials: 'same-origin',
    });
    const json = await resposta.json();

    if (!resposta.ok) {
      throw new Error(json.mensagem || 'Falha ao carregar produtos.');
    }

    produtosCache = json.dados || [];
    renderizarTabela();
  }

  function renderizarTabela() {
    tabelaBody.innerHTML = '';

    for (const produto of produtosCache) {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${produto.id}</td>
        <td>${escapeHtml(produto.nome)}</td>
        <td>${escapeHtml(produto.categoria)}</td>
        <td>${formatarMoeda(produto.preco)}</td>
        <td>${Number(produto.ativo) === 1 ? 'Ativo' : 'Inativo'}</td>
        <td class="acoes-admin">
          <button type="button" class="btn btn-secundario" data-acao="editar" data-id="${produto.id}">Editar</button>
          <button type="button" class="btn btn-secundario" data-acao="excluir" data-id="${produto.id}">Excluir</button>
        </td>
      `;
      tabelaBody.appendChild(tr);
    }
  }

  function preencherFormulario(produto) {
    campo('id').value = produto.id;
    campo('nome').value = produto.nome;
    campo('categoria').value = produto.categoria;
    campo('descricao').value = produto.descricao;
    campo('preco').value = produto.preco;
    campo('imagem').value = produto.imagem;
    campo('ativo').value = String(produto.ativo);
    mensagem.textContent = `Editando produto #${produto.id}.`;
  }

  function limparFormulario() {
    form.reset();
    campo('id').value = '';
    campo('ativo').value = '1';
  }

  async function salvarProduto(evento) {
    evento.preventDefault();
    mensagem.textContent = 'Salvando...';

    const dados = Object.fromEntries(new FormData(form).entries());
    const emEdicao = String(dados.id || '').trim() !== '';
    const rota = emEdicao ? 'admin.produtos.atualizar' : 'admin.produtos.criar';

    const payload = {
      id: emEdicao ? Number(dados.id) : undefined,
      nome: dados.nome,
      categoria: dados.categoria,
      descricao: dados.descricao,
      preco: Number(dados.preco),
      imagem: dados.imagem,
      ativo: Number(dados.ativo),
    };

    try {
      const resposta = await fetch(`${BASE_API}?rota=${rota}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify(payload),
      });
      const json = await resposta.json();

      if (!resposta.ok) {
        throw new Error(json.mensagem || 'Falha ao salvar produto.');
      }

      mensagem.textContent = json.mensagem;
      limparFormulario();
      await carregarProdutos();
    } catch (erro) {
      mensagem.textContent = erro.message;
    }
  }

  async function excluirProduto(id) {
    if (!confirm('Deseja realmente excluir este produto?')) return;
    mensagem.textContent = 'Excluindo...';

    try {
      const resposta = await fetch(`${BASE_API}?rota=admin.produtos.excluir`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({ id: Number(id) }),
      });
      const json = await resposta.json();

      if (!resposta.ok) {
        throw new Error(json.mensagem || 'Falha ao excluir produto.');
      }

      mensagem.textContent = json.mensagem;
      await carregarProdutos();
    } catch (erro) {
      mensagem.textContent = erro.message;
    }
  }

  function tratarCliqueTabela(evento) {
    const alvo = evento.target;
    if (!alvo || !alvo.dataset || !alvo.dataset.acao) return;

    const id = Number(alvo.dataset.id);
    const produto = produtosCache.find((item) => Number(item.id) === id);
    if (!produto) return;

    if (alvo.dataset.acao === 'editar') {
      preencherFormulario(produto);
    }

    if (alvo.dataset.acao === 'excluir') {
      excluirProduto(id);
    }
  }

  function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(valor) || 0);
  }

  function escapeHtml(texto) {
    return String(texto)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  form.addEventListener('submit', salvarProduto);
  botaoCancelar.addEventListener('click', function () {
    limparFormulario();
    mensagem.textContent = 'Edicao cancelada.';
  });
  tabelaBody.addEventListener('click', tratarCliqueTabela);

  carregarProdutos().catch((erro) => {
    mensagem.textContent = erro.message;
  });
})();
