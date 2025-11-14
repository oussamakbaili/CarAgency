#!/bin/bash

# Script pour résoudre le conflit de git pull avec storage/logs/laravel.log
# Usage: ./fix-pull-conflict.sh

set -e

echo "🔧 Résolution du conflit de git pull..."

# Aller dans le répertoire du projet
cd public_html || { echo "❌ Erreur: Répertoire public_html introuvable"; exit 1; }

# Ignorer les modifications locales du fichier de log
echo "📝 Ignorant les modifications locales de storage/logs/laravel.log..."
git checkout -- storage/logs/laravel.log 2>/dev/null || true

# Ou supprimer le fichier s'il cause problème
if [ -f "storage/logs/laravel.log" ]; then
    echo "🗑️  Suppression du fichier de log local..."
    rm -f storage/logs/laravel.log
fi

# Maintenant faire le pull
echo "📥 Récupération des modifications depuis GitHub..."
git pull origin master

echo "✅ Problème résolu! Les modifications ont été récupérées avec succès."
echo "📊 État actuel:"
git status

