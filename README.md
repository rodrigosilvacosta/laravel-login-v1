# 🔐 Laravel Login v1 - Projeto de Estudos

Sistema de autenticação e gerenciamento de usuários desenvolvido com Laravel, aplicando princípios de **Clean Architecture**, **DDD** e **SOLID**.

[![Tests](https://img.shields.io/badge/tests-149%20passing-success)](https://github.com/rodrigosilvacosta/laravel-login-v1)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?logo=laravel)](https://laravel.com)

---

## 📋 Sobre o Projeto

Este é um projeto de estudos focado em **boas práticas de desenvolvimento backend**. Apesar da simplicidade funcional (sistema de login e CRUD de usuários), o projeto demonstra aplicação rigorosa de padrões arquiteturais modernos.

### 🎯 Objetivos de Aprendizado

- ✅ Aplicar **Clean Architecture** com separação clara de camadas
- ✅ Implementar **Domain-Driven Design (DDD)** com Value Objects e Entities
- ✅ Seguir princípios **SOLID** em todo o código
- ✅ Escrever **testes automatizados** (unitários e de integração)
- ✅ Configurar ambiente **Docker** customizado
- ✅ Utilizar **Xdebug** para depuração no VS Code

---

## 🏗️ Arquitetura

### Estrutura de Camadas

```
app/
├── Application/          # Camada de Aplicação
│   ├── User/
│   │   ├── UseCases/    # Casos de uso (lógica de aplicação)
│   │   ├── Dtos/        # Data Transfer Objects
│   │   ├── Mappers/     # Mapeadores de dados
│   │   └── Query/       # Critérios de consulta
│   └── ValueObjects/    # VOs da camada de aplicação
│
├── Domain/              # Camada de Domínio
│   ├── User/
│   │   ├── Entities/    # Entidades de domínio
│   │   ├── ValueObjects/# Value Objects
│   │   └── Repositories/# Interfaces de repositório
│   └── Shared/          # Código compartilhado
│       ├── Exceptions/  # Exceções de domínio
│       ├── Security/    # Segurança (Password, Token)
│       └── ValueObjects/# VOs compartilhados
│
├── Infrastructure/      # Camada de Infraestrutura
│   ├── Persistence/
│   │   └── Eloquent/   # Implementações de repositórios
│   ├── Service/        # Serviços de infraestrutura
│   └── Validation/     # Regras de validação
│
└── Http/               # Camada de Apresentação
    ├── Controllers/    # Controllers (orquestração)
    └── Requests/       # Form Requests (validação HTTP)
```

### Padrões Arquiteturais Aplicados

#### 🎨 Clean Architecture
- **Separação de responsabilidades** em camadas bem definidas
- **Inversão de dependência**: Domain não depende de nada
- **Fluxo unidirecional**: HTTP → Application → Domain ← Infrastructure

#### 🏛️ Domain-Driven Design (DDD)
- **Value Objects**: Email, FirstName, LastName, UserUuid, PlainPassword, etc.
- **Entities**: UserEntity com comportamento rico
- **Repositories**: Interfaces no Domain, implementações na Infrastructure
- **Use Cases**: Orquestração de lógica de aplicação

#### 🔧 SOLID
- **SRP**: Cada classe tem uma única responsabilidade
- **OCP**: Extensível via interfaces
- **LSP**: Substituição correta de implementações
- **ISP**: Interfaces segregadas por contexto
- **DIP**: Dependência de abstrações, não de implementações concretas

---

## 🚀 Tecnologias

### Requisitos

- **Docker** 20.10+
- **Docker Compose** 2.0+
- **Git**

### Stack

| Tecnologia | Versão | Descrição |
|------------|--------|-----------|
| **PHP** | 8.2 | Linguagem de programação |
| **Laravel** | 12.0 | Framework PHP |
| **MySQL** | 8.0 | Banco de dados |
| **Nginx** | Alpine | Servidor web |
| **Laravel Sanctum** | 4.0 | Autenticação via tokens |
| **PHPUnit** | 11.5 | Framework de testes |
| **Xdebug** | 3.x | Depurador PHP |

### Pacotes Adicionais

- `ramsey/uuid` - Geração de UUIDs
- `laravel-lang/lang` - Traduções PT-BR
- `barryvdh/laravel-ide-helper` - Autocomplete para IDEs

---

## ⚙️ Instalação e Configuração

### 1. Clone o Repositório

```bash
git clone https://github.com/rodrigosilvacosta/laravel-login-v1.git
cd laravel-login-v1
```

### 2. Configure o Ambiente

```bash
# Copie o arquivo de ambiente
cp .env.example .env

# Ajuste as variáveis se necessário (valores padrão já configurados)
```

### 3. Suba os Containers Docker

```bash
docker-compose up -d
```

**Containers criados:**
- `laravel-app-login-v1` - Aplicação PHP (porta 9000)
- `nginx-laravel-app-login-v1` - Servidor web (porta 8000)
- `mysql-laravel-app-login-v1` - Banco de dados (porta 3308)

### 4. Instale as Dependências

```bash
docker exec -it laravel-app-login-v1 composer install
```

### 5. Configure a Aplicação

```bash
# Entre no container
docker exec -it laravel-app-login-v1 bash

# Gere a chave da aplicação
php artisan key:generate

# Execute as migrations
php artisan migrate

# Execute as seeds (cria usuário inicial)
php artisan db:seed
```

### 6. Acesse a Aplicação

- **API**: http://localhost:8000/api
- **MySQL**: localhost:3308

---

## 📡 API - Endpoints

### Autenticação

#### Login
```http
POST /api/admin/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password",
  "device_name": "web-browser"
}
```

**Resposta:**
```json
{
  "access_token": "1|abc123..."
}
```

#### Logout
```http
GET /api/admin/logout
Authorization: Bearer {token}
```

---

### Gerenciamento de Usuários

> **Nota**: Todos os endpoints abaixo requerem autenticação via Bearer Token

#### Criar Usuário
```http
POST /api/admin/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "first_name": "João",
  "last_name": "Silva",
  "email": "joao@example.com",
  "password": "senha123"
}
```

#### Listar Usuários (com paginação e filtros)
```http
GET /api/admin/users?page=1&per_page=10&first_name=João&email=joao
Authorization: Bearer {token}
```

**Parâmetros de Query:**
- `page` (opcional): Número da página (padrão: 1)
- `per_page` (opcional): Itens por página (padrão: 10, máx: 100)
- `first_name` (opcional): Filtro por nome
- `last_name` (opcional): Filtro por sobrenome
- `email` (opcional): Filtro por email

**Resposta:**
```json
{
  "data": [...],
  "total": 50,
  "per_page": 10,
  "current_page": 1,
  "last_page": 5
}
```

#### Buscar Usuário por UUID
```http
GET /api/admin/users/uuid/{uuid}
Authorization: Bearer {token}
```

#### Obter Perfil do Usuário Autenticado
```http
GET /api/admin/users/profile
Authorization: Bearer {token}
```

#### Atualizar Usuário
```http
PUT /api/admin/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "first_name": "João",
  "last_name": "Santos"
}
```

---

## 🧪 Testes

O projeto possui testes unitários e de integração, cobrindo:
- ✅ Testes unitários de Use Cases
- ✅ Testes unitários de Value Objects
- ✅ Testes unitários de Entities
- ✅ Testes de integração de Controllers

### Executar Todos os Testes

```bash
docker exec -it laravel-app-login-v1 php artisan test
```

### Executar Testes Específicos

```bash
# Testes de um Use Case específico
docker exec -it laravel-app-login-v1 php artisan test --filter=UserRegisterUseCaseTest

# Testes unitários apenas
docker exec -it laravel-app-login-v1 php artisan test tests/Unit

# Testes de feature apenas
docker exec -it laravel-app-login-v1 php artisan test tests/Feature
```

### Cobertura de Código

```bash
docker exec -it laravel-app-login-v1 php artisan test --coverage
```

---

## 🐛 Debug com Xdebug

O projeto está configurado para usar Xdebug com VS Code.

### Configuração do VS Code

Adicione ao `.vscode/launch.json`:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003,
      "pathMappings": {
        "/var/www": "${workspaceFolder}"
      }
    }
  ]
}
```

### Usar o Debugger

1. Coloque breakpoints no código
2. Inicie o debug no VS Code (F5)
3. Faça requisições à API
4. O debugger pausará nos breakpoints

---

## 📚 Casos de Uso Implementados

### User Module

1. **UserRegisterUseCase** - Cadastro de usuários
2. **UserLoginUseCase** - Autenticação com timing attack mitigation
3. **UserUpdateUseCase** - Atualização de dados pessoais
4. **UserListUseCase** - Listagem com paginação e filtros
5. **UserFindByUuidUseCase** - Busca por UUID
6. **UserGetCurrentProfileUseCase** - Perfil do usuário autenticado

---

## 🔒 Segurança

### Implementações de Segurança

- ✅ **Autenticação via Laravel Sanctum** (tokens)
- ✅ **Hashing de senhas** com bcrypt
- ✅ **Timing attack mitigation** no login
- ✅ **Validação de email único** na camada de domínio

---

## 🗂️ Banco de Dados

### Migrations

```bash
# Executar migrations
docker exec -it laravel-app-login-v1 php artisan migrate

# Rollback
docker exec -it laravel-app-login-v1 php artisan migrate:rollback

# Reset e re-executar
docker exec -it laravel-app-login-v1 php artisan migrate:fresh --seed
```

### Seeders

O projeto inclui um seeder que cria um usuário inicial:

- **Email**: admin@example.com
- **Senha**: password

```bash
docker exec -it laravel-app-login-v1 php artisan db:seed
```

---

## 🛠️ Comandos Úteis

### Docker

```bash
# Subir containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Reconstruir containers
docker-compose up -d --build
```

### Laravel

```bash
# Entrar no container
docker exec -it laravel-app-login-v1 bash

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Listar rotas
php artisan route:list

# Gerar IDE helpers
php artisan ide-helper:generate
php artisan ide-helper:models
```

---

## 📖 Aprendizados e Conceitos Aplicados

### Clean Architecture
- Separação de camadas com dependências unidirecionais
- Domain independente de frameworks
- Use Cases orquestrando lógica de aplicação

### DDD
- Value Objects imutáveis com validação
- Entities com comportamento rico
- Repository Pattern com interfaces no Domain
- Linguagem ubíqua em todo o código

### SOLID
- **SRP**: Cada classe com responsabilidade única
- **OCP**: Extensível sem modificação
- **LSP**: Implementações substituíveis
- **ISP**: Interfaces específicas por contexto
- **DIP**: Inversão de dependência perfeita

### Testes
- Testes unitários isolados com mocks
- Testes de integração com banco de dados
- Cobertura de cenários de sucesso e falha

---

## 🚧 Roadmap

### Próximas Implementações

- [ ] Confirmação de email por link
- [ ] Recuperação de senha
- [ ] Soft delete de usuários
- [ ] Auditoria de ações
- [ ] Rate limiting
- [ ] Logs estruturados

---

## 📝 Licença

Este projeto é de código aberto para fins educacionais.

---

## 👤 Autor

**Rodrigo Silva Costa**

- GitHub: [@rodrigosilvacosta](https://github.com/rodrigosilvacosta)

---

## 🙏 Considerações Finais

Este projeto faz parte do meu processo de **aprendizado contínuo** em desenvolvimento backend. O foco está na **qualidade do código** e na aplicação correta de **padrões arquiteturais**, não na complexidade funcional.

Feedback e sugestões são sempre bem-vindos! 🚀