# SGPJ (Integração Laravel)

Este repositório contém os recursos necessários para portar o dashboard SGPJ para uma aplicação Laravel existente.

## Como usar

1. Crie um novo projeto Laravel (`laravel new sgpj` ou `composer create-project laravel/laravel sgpj`).
2. Copie os diretórios/arquivos deste repositório para o projeto Laravel, preservando a estrutura:
   - `app/Http/Controllers`
   - `config/sgpj.php`
   - `public/css/app.css`
   - `public/js/dashboard.js`
   - `resources/views/...`
   - `routes/web.php`
   - `storage/app/sgpj/processos.json`
3. Execute `composer install` (caso ainda não tenha feito) e gere a chave com `php artisan key:generate`.
4. Garanta permissão de escrita em `storage/` e `bootstrap/cache/`.
5. Inicie o servidor (`php artisan serve`).

Após acessar `http://127.0.0.1:8000` você verá a nova tela de login. Credenciais válidas:

- Usuário `tarcisio`, senha `123`
- Usuário `lucas`, senha `123`

A opção "Lembrar-me" cria um cookie persistente; desative-a para manter a sessão apenas no navegador atual.

## Arquitetura

- `AuthController` valida as credenciais configuradas em `config/sgpj.php`, cria a sessão e gerencia o cookie de "lembrar".
- `DashboardController` garante que apenas usuários autenticados acessem o painel e injeta dados do usuário na view.
- `ProcessoController` devolve o JSON de processos localizado em `storage/app/sgpj/processos.json`.
- `resources/views/auth/login.blade.php` define a tela de login moderna, mantendo a estética original.
- `resources/views/dashboard/index.blade.php` renderiza o kanban/lista, menu lateral, botão de recarregar e modo escuro.
- `public/js/dashboard.js` centraliza o comportamento do front-end (filtros, alternância de visualização, tema, drawer e logout).
- `public/css/app.css` contém a paleta, layouts responsivos e estilos do modo escuro.

## Endpoints

- `GET /` — painel (requer autenticação)
- `GET /login` — formulário de login
- `POST /login` — autenticação
- `POST /logout` — encerra a sessão
- `GET /processos` — retorna o JSON consumido pelo front-end

## Observações

- Adapte `config/sgpj.php` para incluir novos usuários ou alterar o local do arquivo de dados.
- O botão de recarregar força `window.location.reload()`, garantindo dados atualizados.
- A preferência de tema escuro é salva em `localStorage` (`sgpj-dashboard-tema`).

Este pacote foi pensado para ser aplicado sobre uma base Laravel limpa; ele não inclui o framework completo por motivos de tamanho/licenciamento.
