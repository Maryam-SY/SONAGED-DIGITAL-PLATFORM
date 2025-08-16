# Script d'initialisation de la base de données SONAGED
# Exécutez ce script pour créer les tables et les comptes par défaut

Write-Host "🚀 Initialisation de la base de données SONAGED..." -ForegroundColor Green

# Vérifier si Composer est installé
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer n'est pas installé. Veuillez l'installer d'abord." -ForegroundColor Red
    exit 1
}

# Vérifier si PHP est installé
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "❌ PHP n'est pas installé. Veuillez l'installer d'abord." -ForegroundColor Red
    exit 1
}

Write-Host "📦 Installation des dépendances Composer..." -ForegroundColor Yellow
composer install

Write-Host "🔧 Configuration de l'environnement..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✅ Fichier .env créé à partir de .env.example" -ForegroundColor Green
}

Write-Host "🔑 Génération de la clé d'application..." -ForegroundColor Yellow
php artisan key:generate

Write-Host "🗄️ Exécution des migrations..." -ForegroundColor Yellow
php artisan migrate:fresh

Write-Host "🌱 Exécution des seeders..." -ForegroundColor Yellow
php artisan db:seed

Write-Host "✅ Base de données initialisée avec succès !" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Comptes créés :" -ForegroundColor Cyan
Write-Host "👨‍💼 Admin: admin@sonaged.sn / admin123" -ForegroundColor White
Write-Host "🤝 Partenaire: partenaire@sonaged.sn / partenaire123" -ForegroundColor White
Write-Host "👤 Citoyen: citoyen@sonaged.sn / citoyen123" -ForegroundColor White
Write-Host ""
Write-Host "🚀 Pour démarrer le serveur API :" -ForegroundColor Cyan
Write-Host "php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Pour démarrer le frontend Angular :" -ForegroundColor Cyan
Write-Host "cd ../sonaged-frontend && npm start" -ForegroundColor White

