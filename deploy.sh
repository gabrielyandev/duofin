#!/bin/bash
set -e

echo "=========================================="
echo "🚀 Iniciando Deploy - DuoFin"
echo "=========================================="

# 1. Configurar Git para ignorar alterações de permissões feitas pelo Docker
git config core.filemode false

# 2. Puxar alterações do repositório
echo "📥 Puxando código mais recente do GitHub..."
git pull origin main

# 3. Recompilar a imagem Docker e reiniciar o container
echo "🐳 Reconstruindo imagem Docker e reiniciando container..."
docker compose up -d --build

# 4. Ajustar permissões da pasta storage e bootstrap/cache no container
echo "🔐 Ajustando permissões..."
docker exec duofin_app chown -R www-data:www-data storage bootstrap/cache
docker exec duofin_app chmod -R 775 storage bootstrap/cache

# 5. Executar migrações pendentes no banco de dados
echo "🗄️ Executando migrações do banco de dados..."
docker exec duofin_app php artisan migrate --force

# 6. Limpar e recriar os caches de produção do Laravel para máxima performance
echo "⚡ Otimizando caches do Laravel..."
docker exec duofin_app php artisan optimize:clear
docker exec duofin_app php artisan optimize

# 7. Garantir link simbólico de storage público
docker exec duofin_app php artisan storage:link || true

echo "=========================================="
echo "✅ Deploy finalizado com sucesso!"
echo "=========================================="
