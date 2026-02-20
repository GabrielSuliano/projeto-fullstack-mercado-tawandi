(function () {
  const BASE_API = 'api/index.php';

  async function requisicaoApi(rota, payload) {
    const resposta = await fetch(`${BASE_API}?rota=${encodeURIComponent(rota)}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload || {}),
      credentials: 'same-origin',
    });

    const json = await resposta.json();
    if (!resposta.ok) {
      throw new Error(json.mensagem || 'Erro inesperado da API.');
    }

    return json;
  }

  async function iniciarLogin() {
    const form = document.getElementById('formLogin');
    const mensagem = document.getElementById('mensagem');
    if (!form) return;

    form.addEventListener('submit', async function (evento) {
      evento.preventDefault();
      mensagem.textContent = 'Entrando...';

      const dados = Object.fromEntries(new FormData(form).entries());

      try {
        await requisicaoApi('auth.login', dados);
        mensagem.textContent = 'Login realizado. Redirecionando...';
        setTimeout(() => {
          window.location.href = 'index.php';
        }, 700);
      } catch (erro) {
        mensagem.textContent = erro.message;
      }
    });
  }

  async function iniciarRegistro() {
    const form = document.getElementById('formRegistro');
    const mensagem = document.getElementById('mensagem');
    if (!form) return;

    form.addEventListener('submit', async function (evento) {
      evento.preventDefault();
      mensagem.textContent = 'Criando conta...';

      const dados = Object.fromEntries(new FormData(form).entries());

      try {
        await requisicaoApi('auth.registrar', dados);
        mensagem.textContent = 'Conta criada. Redirecionando...';
        setTimeout(() => {
          window.location.href = 'index.php';
        }, 700);
      } catch (erro) {
        mensagem.textContent = erro.message;
      }
    });
  }

  async function sair() {
    try {
      await requisicaoApi('auth.logout', {});
      window.location.reload();
    } catch (erro) {
      alert(erro.message);
    }
  }

  document.addEventListener('click', function (evento) {
    if (evento.target && evento.target.id === 'botaoSair') {
      sair();
    }
  });

  window.tawandiAuth = {
    iniciarLogin,
    iniciarRegistro,
  };
})();
