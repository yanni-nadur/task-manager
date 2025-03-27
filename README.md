
# Task Management App

Este projeto é uma aplicação de gerenciamento de tarefas, onde você pode criar, editar, excluir e atualizar o status das tarefas. A aplicação é composta por um backend em PHP com autenticação via JWT (simulada com credenciais fixas) e um frontend simples utilizando HTML, CSS e JavaScript.

## Tecnologias

- **Backend**:
  - PHP (com Firebase JWT para autenticação)
  - Banco de dados MySQL/PostgreSQL (não especificado no código)
  - Docker (para containerização)

- **Frontend**:
  - HTML
  - CSS (com Bootstrap)
  - JavaScript (com funções assíncronas para interação com a API)

## Funcionalidades

- **Login**: O login é simulado com um usuário fixo (`admin` / `senha123`) no backend. O token JWT gerado é usado para autenticação em chamadas de API subsequentes.
- **Gerenciamento de Tarefas**:
  - Criar, editar, excluir e alternar status de tarefas.
  - As tarefas podem ter 3 status: **Pendente**, **Em Andamento** e **Concluída**.

## Como Rodar o Projeto

### 1. Clonando o Repositório

```bash
git clone https://github.com/yanni-nadur/task-manager.git
cd task-manager
```

### 2. Configuração do Backend

1. **Variáveis de Ambiente**: Crie um arquivo `.env` na raiz do backend e defina a chave secreta para o JWT. Exemplo:

```env
SECRET_KEY=sua_chave_secreta_aqui
```

2. **Docker**:
   - O projeto utiliza Docker para rodar o backend. Certifique-se de ter o Docker instalado em sua máquina.
   - Para rodar o backend com Docker, use o comando:

```bash
docker compose up --build -d
```

Isso vai levantar o container do PHP, que servirá a API de tarefas.

### 3. Configuração do Frontend

1. Acesse a URL `http://localhost:8080`.
2. O frontend irá fazer requisições ao backend para interagir com as tarefas. Para que isso funcione corretamente, certifique-se de que o backend esteja rodando e acessível na URL `http://localhost:9000`.

### 4. Realizando Login

A autenticação é feita automaticamente ao realizar o login. Não há tela de login no frontend, o backend retorna o token JWT se o login for bem-sucedido (usuário: `admin`, senha: `senha123`). O token gerado deve ser incluído nas requisições subsequentes.

### 5. Manipulação de Tarefas

- **Criar**: Preencha o formulário e envie para criar uma nova tarefa.
- **Editar**: Clique em "Editar" ao lado de uma tarefa para atualizar suas informações.
- **Excluir**: Clique em "Excluir" para remover uma tarefa.
- **Alternar Status**: Clique em "Concluir" ou "Reabrir" para mudar o status da tarefa.

## Estrutura do Projeto

- **/backend**: Contém o código PHP para o backend, incluindo o controlador de autenticação e a lógica das tarefas.
- **/frontend**: Contém os arquivos HTML, CSS e JavaScript para o frontend.
- **docker-compose.yml**: Configuração do Docker para rodar o backend.

