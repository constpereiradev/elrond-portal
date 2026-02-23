A documentação fornecida para o teste técnico parece estar bem clara e objetiva, com uma explicação concisa sobre como utilizar os endpoints e as regras de autorização. Ela cobre os aspectos principais de autenticação, autorização e fluxo de dados da API, o que facilita a implementação dos endpoints. Além disso, fornece informações essenciais, como os caminhos dos endpoints, os métodos HTTP e as regras de acesso.

A seguir, preparei um **README** com base nas informações fornecidas para o teste técnico da WebMania. O README será organizado de forma clara, destacando as seções principais: **Introdução**, **Requisitos**, **Uso da API**, **Endpoints Disponíveis**, e **Suporte**.

### README para o Projeto de Teste Técnico da WebMania

---

# **Teste Técnico - Portal Elrond**

Este repositório contém a API do Portal Elrond, desenvolvida para o teste técnico da WebMania. A API oferece funcionalidades para gerenciar usuários, papéis, conselhos, reinos e expedições, com diferentes níveis de acesso e autorização.

## **Requisitos**

1. **Tecnologias Necessárias**:

   * PHP 8.x ou superior
   * Composer
   * Laravel 8.x ou superior
   * Banco de dados (MySQL, SQLite, etc.)

2. **Passos para Iniciar**:

   * Clone o repositório do projeto:

     ```bash
     git clone https://github.com/constpereiradev/elrond-portal.git
     ```
   * Acesse o diretório do projeto:

     ```bash
     cd elrond-portal
     ```
   * Instale as dependências do projeto:

     ```bash
     composer install
     ```
   * Rode as migrações e seeders para popular o banco de dados:

     ```bash
     php artisan migrate --seed
     ```
   * Certifique-se de que as tecnologias necessárias estão configuradas corretamente.

3. **Autenticação**:

   * A API usa autenticação via **token JWT**. Para autenticar um usuário, basta acessar o endpoint `/auth` e obter o token, que será utilizado em todas as requisições subsequentes.

## **Como Utilizar os Endpoints**

### **Autenticação e Token**

* **POST** `/auth`: Autentica um usuário e retorna um token JWT para autenticação nas requisições subsequentes.
* **POST** `/logout`: Desloga o usuário autenticado.

### **Autorização**

* Apenas **administradores** podem cadastrar outros **administradores**.
* Apenas **administradores** podem registrar **Conselhos** e **Reinos**.
* Apenas **administradores** e **Conselhos** podem registrar **Reinos**.
* Apenas **Reinos ativos** podem cadastrar uma nova **Expedição**.
* Apenas **Conselhos ativos** podem atualizar uma **Expedição**.
* O **fluxo de aprovação de expedição** é realizado pelo Conselho, que pode aprovar ou rejeitar a expedição com um motivo caso seja rejeitada.

---

## **Endpoints da API**

Todos os endpoints da API estão disponíveis com o prefixo `/api/v1`. Abaixo está a lista de endpoints disponíveis.

### **Autenticação**

* **POST** `/auth`: Autentica um usuário e retorna um token de autenticação.
* **POST** `/logout`: Desloga um usuário.

### **Usuário**

* **GET** `/auth/user`: Retorna os dados do usuário logado.
* **POST** `/user`: Cadastra um novo usuário.

### **Papel**

* **GET** `/role`: Retorna os papéis disponíveis.
* **POST** `/role`: Cadastra um novo papel.

### **Conselho**

* **GET** `/council`: Retorna os conselhos disponíveis.
* **POST** `/council`: Cadastra um novo conselho.

### **Reino**

* **GET** `/kingdom`: Retorna os reinos disponíveis.
* **POST** `/kingdom`: Cadastra um novo reino.

### **Expedição**

* **GET** `/expedition`: Retorna as expedições disponíveis.
* **POST** `/expedition`: Cadastra uma nova expedição.
* **PUT** `/expedition`: Atualiza uma expedição existente.

### **Status de Expedição**

* **GET** `/expedition-status`: Retorna os status disponíveis.
* **POST** `/expedition-status`: Cadastra um novo status.

---

## **Fluxo de Análise de uma Expedição**

* Quando uma expedição é cadastrada, seu status inicial será **EM ANÁLISE**.
* Um **Conselho** será responsável por decidir se a expedição será **aprovada** ou **rejeitada**.
* Caso a expedição seja **rejeitada**, um motivo deve ser informado.
* Se a expedição for **aprovada**, não é necessário fornecer um motivo.

---

## **Formato de Resposta**

Todas as respostas da API são retornadas no formato **JSON**. Caso ocorra uma exceção, o erro será retornado também em formato JSON com a descrição do erro.

---

## **Suporte**

Se tiver dúvidas ou encontrar problemas, entre em contato com o suporte técnico:

* **Email**: [amandapereiradevcontact@gmail.com](mailto:amandapereiradevcontact@gmail.com)
* **WhatsApp**: +55 71 9 8476-7953
* **GitHub**: [GitHub - constpereiradev](https://github.com/constpereiradev)

---

### Conclusão:

Este **README** cobre os pontos principais sobre como rodar o projeto, usar os endpoints e o fluxo de trabalho da API. A documentação está simples e direta, permitindo que qualquer desenvolvedor consiga utilizar a API para realizar as tarefas solicitadas sem dificuldades.

Se precisar de mais alguma alteração ou algum detalhe adicional no README, é só avisar!
