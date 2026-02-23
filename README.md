# Elrond Portal API

API RESTful para gerenciamento de reinos, conselhos, expedições e usuários no mundo de Elrond.

## 📋 Visão Geral

O Elrond Portal é uma API que permite gerenciar:
- **Reinos (Kingdoms)**: Estruturas políticas organizacionais
- **Conselhos (Councils)**: Órgãos deliberativos
- **Expedições**: Missões ou jornadas
- **Usuários**: Membros do sistema com diferentes funções e permissões
- **Protocolos de Expedição**: Documentação estruturada de expedições
- **Status de Expedição**: Estados possíveis das expedições

## 🛠️ Requisitos

- PHP 8.2 ou superior
- Composer
- Laravel 12.x
- SQLite/MySQL

## 🚀 Instalação e Configuração

### 1. Clonar o repositório
```bash
git clone https://github.com/constpereiradev/elrond-portal.git
cd elrond-portal
```

### 2. Instalar dependências
```bash
composer install
npm install
```

### 3. Configuração do ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrar banco de dados
```bash
php artisan migrate
php artisan db:seed
```


## 🔐 Autenticação

A API utiliza **Laravel Sanctum** para autenticação baseada em tokens.

### Fluxo de Autenticação

1. **Login**: Envie credenciais para obter um token
2. **Token Bearer**: Use o token no header `Authorization: Bearer {token}`
3. **Logout**: Revogue o token quando necessário

### Endpoints de Autenticação

#### Login
```http
POST /api/auth
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Resposta (200 OK)**:
```json
{
  "data": {
    "access_token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz",
    "token_type": "Bearer",
    "type": "admin|reino|conselho"
  }
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

**Resposta (200 OK)**:
```json
{
  "data": {}
}
```

## 📚 Endpoints da API

### 🔓 Endpoints Públicos

#### Roles (Funções)

**Listar todas as funções**
```http
GET /api/role
```

**Resposta (200 OK)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Administrador",
      "slug": "admin",
      "status": "a"
    }
  ]
}
```

**Criar função (Admin)**
```http
POST /api/role
Content-Type: application/json

{
  "name": "Nova Função",
  "slug": "nova_funcao",
  "status": "a"
}
```

#### Usuários (Registro)

**Criar novo usuário (Público)**
```http
POST /api/user
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "senha_segura",
  "kingdom_id": 1,
  "council_id": null,
  "role_id": 2
}
```

**Resposta (201 Created)**:
```json
{
  "data": {
    "id": 5
  }
}
```

---

### 🔐 Endpoints Protegidos (Requer Autenticação)

#### Usuários Autenticados

**Obter usuário logado**
```http
GET /api/auth/user
Authorization: Bearer {token}
```

**Resposta (200 OK)**:
```json
{
  "data": {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com",
      "role_id": 1,
      "kingdom_id": 1,
      "council_id": null,
      "created_at": "2026-02-21T10:00:00Z",
      "role": {
        "id": 1,
        "name": "Administrador",
        "slug": "admin"
      },
      "kingdom": { ... },
      "council": null
    }
  }
}
```

**Atualizar usuário logado**
```http
PUT /api/auth/user
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "João Silva Atualizado",
  "email": "novo_email@example.com",
  "password": "nova_senha"
}
```

**Deletar usuário logado**
```http
DELETE /api/auth/user
Authorization: Bearer {token}
```

#### Gerenciamento de Usuários (Admin)

**Obter usuário por ID**
```http
GET /api/user/{id}
Authorization: Bearer {token}
```

**Atualizar usuário**
```http
PUT /api/user/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Novo Nome",
  "email": "novo@example.com",
  "role_id": 2
}
```

**Deletar usuário**
```http
DELETE /api/user/{id}
Authorization: Bearer {token}
```

---

#### Reinos (Kingdoms)

**Listar todos os reinos**
```http
GET /api/kingdom
Authorization: Bearer {token}
```

**Resposta (200 OK)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Reino de Rivendell",
      "description": "Reino dos Elfos",
      "status": "a",
      "created_at": "2026-02-21T10:00:00Z"
    }
  ]
}
```

**Criar reino**
```http
POST /api/kingdom
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Novo Reino",
  "description": "Descrição do reino",
  "status": "a"
}
```

**Atualizar reino**
```http
PUT /api/kingdom/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nome Atualizado",
  "description": "Descrição atualizada",
  "status": "a"
}
```

---

#### Conselhos (Councils)

**Listar todos os conselhos**
```http
GET /api/council
Authorization: Bearer {token}
```

**Resposta (200 OK)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Conselho Branco",
      "description": "Conselho dos Sábios",
      "status": "a",
      "created_at": "2026-02-21T10:00:00Z"
    }
  ]
}
```

**Criar conselho**
```http
POST /api/council
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Novo Conselho",
  "description": "Descrição do conselho",
  "status": "a"
}
```

**Atualizar conselho**
```http
PUT /api/council/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nome Atualizado",
  "description": "Descrição atualizada",
  "status": "a"
}
```

---

#### Expedições (Expeditions)

**Obter expedição por Protocol ID**
```http
GET /api/expedition/{protocolId}
Authorization: Bearer {token}
```

**Resposta (200 OK)**:
```json
{
  "data": {
    "id": 1,
    "name": "Expedição para o Mirkwood",
    "description": "Missão de exploração",
    "protocol_id": 1,
    "status": "ativa",
    "created_at": "2026-02-21T10:00:00Z"
  }
}
```

**Criar expedição**
```http
POST /api/expedition
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nova Expedição",
  "description": "Descrição da expedição",
  "protocol_id": 1,
  "status": "planejamento"
}
```

**Atualizar expedição**
```http
PUT /api/expedition/{protocolId}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nome Atualizado",
  "status": "em_progresso"
}
```

---

#### Status de Expedição

**Listar todos os status**
```http
GET /api/expedition-status
Authorization: Bearer {token}
```

**Resposta (200 OK)**:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Planejamento",
      "slug": "planejamento",
      "status": "a"
    },
    {
      "id": 2,
      "name": "Em Progresso",
      "slug": "em_progresso",
      "status": "a"
    }
  ]
}
```

**Criar status**
```http
POST /api/expedition-status
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Novo Status",
  "slug": "novo_status",
  "status": "a"
}
```

---

## 📊 Estrutura de Dados

### User
- `id` (int): Identificador único
- `name` (string): Nome do usuário
- `email` (string): Email único
- `password` (string): Senha hash
- `kingdom_id` (int, nullable): ID do reino
- `council_id` (int, nullable): ID do conselho
- `role_id` (int): ID da função
- `created_at` (timestamp)

### Kingdom
- `id` (int): Identificador único
- `name` (string): Nome do reino
- `description` (text): Descrição
- `status` (enum: 'a', 'i'): Ativo/Inativo
- `created_at` (timestamp)

### Council
- `id` (int): Identificador único
- `name` (string): Nome do conselho
- `description` (text): Descrição
- `status` (enum: 'a', 'i'): Ativo/Inativo
- `created_at` (timestamp)

### Role
- `id` (int): Identificador único
- `name` (string): Nome da função
- `slug` (string): Identificador único em slug
- `status` (enum: 'a', 'i'): Ativo/Inativo

### Expedition
- `id` (int): Identificador único
- `name` (string): Nome da expedição
- `description` (text): Descrição
- `status` (string): Status atual
- `protocol_id` (int): ID do protocolo
- `created_at` (timestamp)

---

## 🔍 Códigos de Status HTTP

| Código | Significado |
|--------|-------------|
| 200 | OK - Requisição bem-sucedida |
| 201 | Created - Recurso criado com sucesso |
| 400 | Bad Request - Dados inválidos |
| 401 | Unauthorized - Autenticação necessária |
| 403 | Forbidden - Acesso negado (permissões) |
| 404 | Not Found - Recurso não encontrado |
| 422 | Unprocessable Entity - Validação falhou |
| 500 | Internal Server Error - Erro no servidor |

---

## 🛡️ Segurança e Permissões

- Todas as rotas protegidas requerem autenticação via Bearer Token
- As políticas (Policies) controlam acesso baseado em função
- Senhas são hash com bcrypt
- Tokens expiram conforme configurado no Sanctum
- Validadores Laravel garantem integridade de dados

---

## 📝 Exemplo Completo de Fluxo

### 1. Registrar novo usuário
```bash
curl -X POST http://localhost:8000/api/user \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@example.com",
    "password": "senha123",
    "role_id": 2
  }'
```

### 2. Fazer login
```bash
curl -X POST http://localhost:8000/api/auth \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@example.com",
    "password": "senha123"
  }'
```

### 3. Usar a API com token
```bash
curl -X GET http://localhost:8000/api/auth/user \
  -H "Authorization: Bearer {seu_token_aqui}"
```

### 4. Fazer logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {seu_token_aqui}"
```

---





