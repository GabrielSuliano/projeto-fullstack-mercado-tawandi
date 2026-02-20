const { useEffect, useMemo, useState } = React;

function moeda(valor) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
}

function AppLoja() {
  const [produtos, setProdutos] = useState([]);
  const [carrinho, setCarrinho] = useState([]);
  const [mensagem, setMensagem] = useState('');
  const usuario = window.APP_USUARIO;

  useEffect(() => {
    carregarProdutos();
  }, []);

  async function carregarProdutos() {
    const resposta = await fetch('api/index.php?rota=produtos', { credentials: 'same-origin' });
    const json = await resposta.json();
    if (json.ok) {
      setProdutos(json.dados || []);
    }
  }

  function adicionarItem(produto) {
    setCarrinho((atual) => {
      const existente = atual.find((item) => item.id === produto.id);
      if (existente) {
        return atual.map((item) =>
          item.id === produto.id ? { ...item, quantidade: item.quantidade + 1 } : item
        );
      }
      return [...atual, { ...produto, quantidade: 1 }];
    });
  }

  function removerItem(produtoId) {
    setCarrinho((atual) => atual.filter((item) => item.id !== produtoId));
  }

  const total = useMemo(() => {
    return carrinho.reduce((acc, item) => acc + item.preco * item.quantidade, 0);
  }, [carrinho]);

  async function finalizarPedido() {
    if (!usuario) {
      setMensagem('Faca login para finalizar o pedido.');
      return;
    }

    if (carrinho.length === 0) {
      setMensagem('Seu carrinho esta vazio.');
      return;
    }

    const payload = {
      itens: carrinho.map((item) => ({
        produto_id: item.id,
        quantidade: item.quantidade,
      })),
    };

    try {
      const resposta = await fetch('api/index.php?rota=pedidos.criar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify(payload),
      });

      const json = await resposta.json();
      if (!resposta.ok) {
        throw new Error(json.mensagem || 'Nao foi possivel concluir o pedido.');
      }

      setMensagem(`Pedido #${json.pedido_id} confirmado com total de ${moeda(json.total)}.`);
      setCarrinho([]);
    } catch (erro) {
      setMensagem(erro.message);
    }
  }

  return (
    <div className="catalogo-grid">
      <div className="produtos-grid">
        {produtos.map((produto) => (
          <article className="produto-card" key={produto.id}>
            <img src={produto.imagem} alt={produto.nome} />
            <div className="produto-conteudo">
              <div className="produto-topo">
                <h4>{produto.nome}</h4>
                <span className="pill">{produto.categoria}</span>
              </div>
              <p>{produto.descricao}</p>
              <div className="produto-topo">
                <strong className="preco">{moeda(produto.preco)}</strong>
                <button className="btn" onClick={() => adicionarItem(produto)}>Adicionar</button>
              </div>
            </div>
          </article>
        ))}
      </div>

      <aside className="carrinho">
        <h4>Seu carrinho</h4>
        {carrinho.length === 0 && <p>Sem itens por enquanto.</p>}
        {carrinho.map((item) => (
          <div className="item-carrinho" key={item.id}>
            <div>
              <strong>{item.nome}</strong>
              <small> x{item.quantidade}</small>
            </div>
            <button className="btn btn-secundario" onClick={() => removerItem(item.id)}>Remover</button>
          </div>
        ))}
        <p className="total">Total: {moeda(total)}</p>
        <button className="btn" onClick={finalizarPedido}>Finalizar pedido</button>
        {mensagem && <div className="alerta">{mensagem}</div>}
      </aside>
    </div>
  );
}

const raiz = ReactDOM.createRoot(document.getElementById('app-react'));
raiz.render(<AppLoja />);
