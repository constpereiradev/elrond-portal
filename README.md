Entendido! Vou adicionar as informações necessárias sobre a **inicialização da fila (queue)** e do **reverb** no README. Isso é essencial para garantir que o usuário saiba como configurar o ambiente corretamente para que as funcionalidades da API funcionem sem problemas.

Aqui está a versão atualizada do **README**, agora incluindo esses passos:

---

# **Teste Técnico - Portal Elrond**

Este repositório contém a API do Portal Elrond, desenvolvida para o teste técnico da WebMania. A API oferece funcionalidades para gerenciar usuários, papéis, conselhos, reinos e expedições, com diferentes níveis de acesso e autorização.

## **Requisitos**

1. **Tecnologias Necessárias**:

   * PHP 8.x ou superior
   * Composer
   * Laravel 8.x ou superior
   * Banco de dados MySQL

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

     * Instale o reverb para publicar suas configurações

     ```bash
     php artisan reverb:install
     ```

3. **Inicializar a Fila e Reverb**:
   A aplicação utiliza o sistema de filas (queues) para processar algumas tarefas em segundo plano, como o envio de notificações e outras operações assíncronas. Para garantir que essas funcionalidades funcionem corretamente, você precisará iniciar o **queue worker**.

   * **Iniciar o Queue**:
     Para iniciar o **queue worker**, execute o seguinte comando:

     ```bash
     php artisan queue:work
     ```

   * **Iniciar o Reverb**:
     Para iniciar o **reverb**, execute o seguinte comando:

     ```bash
     php artisan reverb:start --hostname="elrond-portal.test"
     ```

   **Observação**: Caso esteja usando um driver de fila como o `database` ou `redis`, certifique-se de ter configurado corretamente o `.env` para que o Laravel consiga processar as filas corretamente.

4. **Autenticação**:

   * A API usa autenticação via **token JWT**. Para autenticar um usuário, basta acessar o endpoint `/auth` e obter o token, que será utilizado em todas as requisições subsequentes.

5. **WebSocket**:
    * Para se conectar ao WebSocket, basta utilizar a chave REVERB_APP_KEY gerada na instalação do reverb e passar na url: 

    ```bash
    ws://elrond-portal.test:8080/app/{SUA_CHAVE}.
    ```

    Para ver eventos de uma expedição, envie a seguinte mensagem:  
     ```bash
    {
        "event":"pusher:subscribe",
        "data":{
            "channel":"expedition.1"
        }
    }
    ```
    Onde .1 é o ID da expedição.  

    Os eventos são disparados quando:
    - As expedições são visualizadas;
    - O status de uma expedição é modificado;


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

## **Documentação - Postman**
Acesse esta documentação no Postman aqui: [Elrond Portal Api Documentation - Postman](https://martian-firefly-354203.postman.co/workspace/Elrond-Portal~87b062f2-06a6-4d0e-ab0a-5b9149c062b6/folder/25684396-19c6f41a-5219-4905-9c7e-cafdf0af9f36?action=share&creator=25684396&ctx=documentation&active-environment=25684396-d16bef87-f318-4c4d-a1b4-523903842555)

---

## **Suporte**

Se tiver dúvidas ou encontrar problemas, entre em contato com o suporte técnico:

* **Email**: [amandapereiradevcontact@gmail.com](mailto:amandapereiradevcontact@gmail.com)
* **WhatsApp**: +55 71 9 8476-7953
* **GitHub**: [GitHub - constpereiradev](https://github.com/constpereiradev)

---


