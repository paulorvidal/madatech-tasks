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