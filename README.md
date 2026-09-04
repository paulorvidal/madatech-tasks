# Sistema de Gerenciamento de Tarefas - Madatech

Este é um projeto de teste técnico para a vaga de desenvolvedor na Madatech. O sistema consiste em um CRUD completo de tarefas, focado em código limpo, separação de responsabilidades (MVC) e integridade de dados.

##  Tecnologias Utilizadas

* **Framework:** CodeIgniter 4 (PHP 8+)
* **Banco de Dados:** PostgreSQL
* **Padrões e Arquitetura:** MVC, Active Record, Migrations, Validação nativa de Models, Clean Code.

##  Pré-requisitos

Para rodar este projeto localmente, você precisará ter instalado na sua máquina:
* PHP 8.1 ou superior (
    * `extension=intl`
    * `extension=mbstring`
    * `extension=pgsql`
    * `extension=pdo_pgsql`
    * `extension=sqlite3`
).
* Composer.
* PostgreSQL.

##  Como Executar o Projeto

Siga o passo a passo abaixo para configurar o ambiente de desenvolvimento:

### 1. Clone o repositório

* git clone [https://github.com/paulorvidal/madatech-tasks.git]
`cd madatech-tasks`

### 2. Instale as dependências

`composer install`

### 3. Configuração do Banco de Dados

* Crie um banco de dados vazio no seu servidor PostgreSQL chamado madatech_db.
* `createdb -U postgres madatech_db`

### 4. Configuração do Ambiente (.env)
* Na raiz do projeto, renomeie o arquivo env para .env (ou faça uma cópia). Abra o arquivo .env e configure as credenciais do banco de dados na seção DATABASE:

* `CI_ENVIRONMENT = development`

* `database.default.hostname = localhost`
* `database.default.database = madatech_db`
* `database.default.username = postgres`
* `database.default.password = sua_senha_aqui`
* `database.default.DBDriver = Postgre`
* `database.default.port     = 5432`

### 5. Executar as Migrations
* Com o banco configurado, rode o comando abaixo para criar a tabela de tarefas automaticamente.
(Nota arquitetural: Foi implementada uma constraint CHECK direto na migration para garantir a integridade do status diretamente no SGBD PostgreSQL, impedindo inserções inválidas mesmo fora da aplicação).

`php spark migrate`

### 6. Iniciar o Servidor
* Inicie o servidor de desenvolvimento embutido do CodeIgniter:

* `php spark serve`

## Testes Unitários

O projeto utiliza o **PHPUnit** nativamente integrado ao CodeIgniter 4.

Para executar
1. Certifique-se de que a extensão `sqlite3` está habilitada no seu `php.ini` (o CodeIgniter utiliza um banco SQLite em memória para testes, sem afetar o banco principal).
2. Na raiz do projeto, execute o comando:

* `composer test`

## API REST (Bônus)

O sistema disponibiliza rotas RESTful para consumo externo. Você pode testá-las em ferramentas como Postman ou Insomnia  
* `http://localhost:8080`

* **GET `/api/tasks`** - Retorna a lista de todas as tarefas.
* **GET `/api/tasks/{id}`** - Retorna os detalhes de uma tarefa específica.
* **POST `/api/tasks`** - Cria uma nova tarefa.
  * JSON: `{"title": "...", "description": "...", "status": "pendente"}`
* **PUT `/api/tasks/{id}`** - Atualiza os dados de uma tarefa.
  * JSON: `{"title": "...", "description": "...", "status": "concluída"}`
* **DELETE `/api/tasks/{id}`** - Remove a tarefa do banco de dados.
