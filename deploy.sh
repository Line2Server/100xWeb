#!/bin/bash
set -e

echo "🎮 Eternal War L2 - Deploy Script"
echo "=================================="

# Verificar se .env existe
if [ ! -f .env ]; then
    echo "❌ Arquivo .env não encontrado!"
    echo "   Copie .env.example para .env e configure suas variáveis."
    exit 1
fi

# Carregar variáveis
export $(grep -v '^#' .env | xargs)

# Criar rede Traefik se não existir
if ! docker network ls | grep -q "traefik-net"; then
    echo "🌐 Criando rede traefik-net..."
    docker network create traefik-net
fi

# Build e subir
echo "🏗️  Construindo containers..."
docker compose up -d --build

echo ""
echo "✅ Deploy concluído!"
echo ""
echo "📊 Status:"
docker compose ps

echo ""
echo "🌐 Acesse: https://${DOMAIN}"
echo "🔐 Admin:  https://${DOMAIN}/admin/login.php"
echo ""
echo "📋 Próximos passos:"
echo "   1. Acesse o admin e ALTERE A SENHA PADRÃO"
echo "   2. Configure o SMTP no .env para e-mails funcionarem"
echo "   3. Teste o registro de conta em /register.php"
