# Sistema de Gerenciamento de Tarefas - Madatech

Este é um projeto de teste técnico para a vaga de desenvolvedor na Madatech. O sistema consiste em um CRUD completo de tarefas, focado em código limpo, separação de responsabilidades (MVC) e integridade de dados.

##  Tecnologias Utilizadas

* **Framework:** CodeIgniter 4 (PHP 8+)
* **Banco de Dados:** PostgreSQL
* **Padrões e Arquitetura:** MVC, Active Record, Migrations, Validação nativa de Models, Clean Code.

##  Pré-requisitos

Para rodar este projeto localmente, você precisará ter instalado na sua máquina:
* PHP 8.1 ou superior (com extensões `intl`, `mbstring` e `pgsql` habilitadas).
* Composer.
* PostgreSQL.

##  Como Executar o Projeto

Siga o passo a passo abaixo para configurar o ambiente de desenvolvimento:

### 1. Clone o repositório

* git clone [https://github.com/paulorvidal/madatech_tasks.git]
* cd madatech-tasks

### 2. Instale as dependências

* composer install

### 3. Configuração do Banco de Dados

* Crie um banco de dados vazio no seu servidor PostgreSQL chamado madatech_db.

### 4. Configuração do Ambiente (.env)
* Na raiz do projeto, renomeie o arquivo env para .env (ou faça uma cópia). Abra o arquivo .env e configure as credenciais do banco de dados na seção DATABASE:

CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = madatech_db
database.default.username = postgres
database.default.password = sua_senha_aqui
database.default.DBDriver = Postgre
database.default.port     = 5432

### 5. Executar as Migrations
* Com o banco configurado, rode o comando abaixo para criar a tabela de tarefas automaticamente.
(Nota arquitetural: Foi implementada uma constraint CHECK direto na migration para garantir a integridade do status diretamente no SGBD PostgreSQL, impedindo inserções inválidas mesmo fora da aplicação).

* php spark migrate

### 6. Iniciar o Servidor
* Inicie o servidor de desenvolvimento embutido do CodeIgniter:

* php spark serve

# Documentação: Camada de Dados (TaskModel)

A classe `TaskModel` gerencia a abstração do banco de dados e centraliza as regras de negócio e validação, aderindo ao padrão Active Record do CodeIgniter 4[cite: 1].

## Estrutura e Configurações Principais
* **Mapeamento:** Vinculada à tabela `tasks` com chave primária `id`.
* **Segurança (Mass Assignment):** A propriedade `$allowedFields` restringe as inserções e atualizações apenas aos campos `title`, `description` e `status`. Protege contra injeções indevidas de parâmetros na requisição[cite: 1].
* **Retorno:** Configurado como `array` (`$returnType = 'array'`) para facilitar a manipulação nas Views e futura conversão JSON para o bônus da API REST[cite: 1].
* **Timestamps:** A propriedade `$useTimestamps = true` gerencia automaticamente a gravação e atualização das colunas `created_at` e `updated_at`.

## Regras de Validação (SRP)
As validações exigidas no sistema foram abstraídas do Controller e alocadas nativamente no Model, mantendo a responsabilidade única[cite: 1]:
* **Title:** Obrigatório, mínimo de 3 e máximo de 255 caracteres.
* **Description:** Opcional (`permit_empty`), limite de 1000 caracteres.
* **Status:** Obrigatório, restrito aos valores exatos do banco via `in_list[pendente,em andamento,concluída]`[cite: 1].

# Documentação: Roteamento RESTful (Routes.php)

O sistema de rotas foi projetado para seguir rigorosamente os princípios REST, utilizando rotas amigáveis para gerenciar os recursos de tarefas[cite: 1].

## Estrutura de Rotas
Foi implementado um `$routes->group('tasks')` para agrupar todas as requisições sob o mesmo prefixo URI, garantindo organização:

* `GET /tasks` -> Aciona `TaskController::index` (Lista todas as tarefas).
* `GET /tasks/new` -> Aciona `TaskController::new` (Exibe formulário de criação).
* `POST /tasks` -> Aciona `TaskController::create` (Processa a criação).
* `GET /tasks/(:num)/edit` -> Aciona `TaskController::edit/$1` (Exibe formulário de edição).
* `PUT /tasks/(:num)` -> Aciona `TaskController::update/$1` (Processa a edição via Method Spoofing).
* `DELETE /tasks/(:num)` -> Aciona `TaskController::delete/$1` (Processa a exclusão via Method Spoofing).

O redirecionamento da raiz (`/`) para `TaskController::index` foi configurado para melhorar a usabilidade inicial.

# Documentação: Camada de Controle (TaskController)

O `TaskController` atua estritamente como orquestrador do fluxo HTTP, recebendo requisições, delegando o processamento de dados ao Model e retornando as Views apropriadas, seguindo o padrão MVC[cite: 1].

## Princípios Aplicados
* **Skinny Controller (Controlador Limpo):** Não possui lógicas de validação verbosas ou interações diretas com queries. Toda a proteção e validação de dados é garantida pelo `TaskModel`[cite: 1].
* **Fail-Fast (Falha Rápida):** Nos métodos que dependem de um ID (`edit`, `update`, `delete`), o controlador verifica imediatamente a existência do recurso via Query Builder[cite: 1]. Se não existir, lança uma `PageNotFoundException` (Erro HTTP 404).
* **Injeção de Dependência:** O `TaskModel` é instanciado no método construtor, garantindo a reutilização da instância na memória ao longo da execução da classe.

## Fluxo de Operações (CRUD)
* **index():** Recupera todas as tarefas ordenadas de forma decrescente pela data de criação (`created_at`) e as envia para a visualização.
* **create():** Captura os dados POST e tenta a inserção. Em caso de falha de validação do Model, utiliza `redirect()->back()->withInput()` para retornar à tela anterior preservando os dados digitados e exibindo os erros.
* **update():** Utiliza `$this->request->getRawInput()` para processar dados vindos de simulações HTTP PUT (Method Spoofing).
* **delete():** Remove fisicamente o registro do banco de dados via `$this->taskModel->delete($id)` após confirmar sua existência prévia.

# Documentação: View de Listagem (tasks/index.php)

A view `index.php` atua como o painel principal do sistema, responsável por listar todas as tarefas e centralizar a navegação, utilizando os componentes visuais do Bootstrap 5.

## Componentes e Decisões Técnicas
* **Tabela de Dados (Bootstrap):** Utiliza as classes `.table`, `.table-hover` e `.align-middle` para apresentar as informações do banco de dados de forma limpa e responsiva.
* **Indicadores Visuais (Badges):** O status da tarefa aplica classes semânticas do Bootstrap (`bg-success`, `bg-warning`, `bg-secondary`) via operador ternário, facilitando a identificação imediata do progresso da tarefa.
* **Feedback do Sistema:** Captura e exibe mensagens da sessão via `session()->getFlashdata('success')` quando ações de criação, edição ou exclusão são bem-sucedidas.
* **Segurança na Exclusão (CSRF):** A exclusão não é feita por um link direto (tag `<a>`), mas sim por um formulário oculto contendo `csrf_field()` e `_method="DELETE"`, garantindo proteção contra falsificação de solicitações HTTP.

# Documentação: View de Cadastro (tasks/create.php)

A view `create.php` fornece a interface para a entrada de novas tarefas, com foco em usabilidade e validação de dados.

## Componentes e Decisões Técnicas
* **Layout Estruturado:** O formulário está encapsulado em um `.card` do Bootstrap centralizado na tela, proporcionando uma experiência de usuário focada.
* **Exibição de Erros de Validação:** Intercepta as falhas de validação geradas pelo `TaskModel` e redirecionadas pelo Controller (`session()->getFlashdata('errors')`), renderizando-as em um bloco de alerta (`.alert-danger`) no topo do formulário.
* **Preservação de Estado (UX):** Utiliza a função auxiliar `old('campo')` do CodeIgniter em todos os inputs. Se a validação falhar, o usuário não perde os textos longos que já havia digitado na descrição ou no título.
* **Proteção de Submissão:** A tag `<?= csrf_field() ?>` foi injetada nativamente para blindar o endpoint POST contra ataques de origem cruzada.

# Documentação: View de Edição (tasks/edit.php)

A view `edit.php` espelha o design do formulário de criação, mas sua lógica interna é adaptada para o fluxo de atualização RESTful.

## Componentes e Decisões Técnicas
* **Method Spoofing (PUT):** Como navegadores padrão suportam apenas requisições GET e POST em formulários HTML, foi incluído o input oculto `<input type="hidden" name="_method" value="PUT">`. Isso permite que o CodeIgniter interprete a requisição corretamente para atualizar o registro.
* **Preenchimento Dinâmico (Two-Way Fallback):** Os campos do formulário combinam os dados atuais do banco de dados com a retenção de estado de erros. A função `old('title', $task['title'])` garante que o input mostre o valor original da tarefa, mas dê prioridade ao valor recém-digitado caso a tentativa de atualização falhe nas validações.
* **Padronização de Opções:** O campo `<select>` do status avalia o valor retornado do banco (`$task['status']`) para inserir dinamicamente o atributo `selected` na opção correta, respeitando a integridade das regras de negócio ("pendente", "em andamento" ou "concluída").