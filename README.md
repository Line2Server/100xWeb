# 🎮 Aden Eternal L2 - Site + Docker + Traefik + MariaDB

Stack completa Dockerizada para o servidor Lineage 2 Interlude PvP.

---

## 📁 Estrutura

```
l2server_docker/
├── docker-compose.yml      # Stack completa
├── .env                    # Variáveis de ambiente (NÃO versionar!)
├── .gitignore              # Ignorar .env e volumes
├── README.md               # Este arquivo
├── deploy.sh               # Script de deploy automático
│
├── nginx/
│   └── default.conf        # Config nginx + PHP-FPM
│
├── php/
│   └── Dockerfile          # PHP 8.1-FPM + extensões
│
├── db-init/
│   └── 01-init.sql         # Cria tabelas auxiliares no primeiro boot
│
└── app/                    # CÓDIGO DO SITE PHP
    ├── index.php
    ├── register.php
    ├── forgot_password.php
    ├── reset_password.php
    ├── includes/
    ├── pages/
    ├── admin/
    ├── api/
    └── assets/
```

---

## 🚀 DEPLOY RÁPIDO

### 1. Clone/Extraia e entre na pasta
```bash
cd l2server_docker
```

### 2. Configure o `.env`
```bash
nano .env
```

```env
DOMAIN=seuservidor.com.br
DB_NAME=l2jbr
DB_USER=l2jbr
DB_PASS=SuaSenhaForteAqui!
MYSQL_ROOT_PASS=RootSenhaForte123!
SERVER_IP=seu.ip.publico
SERVER_PORT=7777
LOGIN_PORT=2106
SMTP_HOST=smtp.gmail.com
SMTP_USER=seu_email@gmail.com
SMTP_PASS=sua_senha_app
```

### 3. Crie a rede do Traefik (se ainda não existir)
```bash
docker network create traefik-net
```

### 4. Suba a stack
```bash
docker compose up -d --build
```

### 5. Verifique
```bash
docker compose ps
docker compose logs -f
```

---

## 🔧 COMANDOS ÚTEIS

```bash
# Ver logs
docker compose logs -f nginx
docker compose logs -f php
docker compose logs -f db

# Acessar banco
docker compose exec db mysql -u l2jbr -p

# Rebuild após alterações
docker compose up -d --build

# Parar tudo
docker compose down

# Parar e remover volumes (CUIDADO!)
docker compose down -v
```

---

## 🔑 PRIMEIRO ACESSO

| Área | URL | Credenciais |
|------|-----|-------------|
| **Site** | `https://seuservidor.com.br` | Público |
| **Registro** | `/register.php` | Qualquer e-mail |
| **Recuperar Senha** | `/forgot_password.php` | Login + e-mail |
| **Admin** | `/admin/login.php` | `admin` / `admin123` |

> ⚠️ **ALTERE A SENHA DO ADMIN IMEDIATAMENTE!**

---

## 🛡️ SEGURANÇA

- `.env` está no `.gitignore` — nunca versione senhas
- Traefik gerencia SSL automaticamente (Let's Encrypt)
- Nginx nega acesso a `includes/` e `admin/api/`
- PHP OPcache ativado para performance
- Tokens de senha expiram em 1 hora

---

## 📞 SUPORTE

Discord: https://discord.gg/seulink
