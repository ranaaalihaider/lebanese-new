# Deployment Quick Start

## What I've Prepared

✅ **Configuration Files**
- `.env.example` - Production environment template
- `Procfile` - Railway deployment configuration
- `railway.json` - Build and deploy settings

✅ **Database Setup**
- `AdminUserSeeder` - Creates admin account (admin@marketplace.com / admin123)
- `InitialCategoriesSeeder` - Creates default product & store categories
- Updated `DatabaseSeeder` to run seeders automatically

✅ **Documentation**
- Complete step-by-step deployment guide
- Environment variables reference
- Troubleshooting guide
- Post-deployment checklist

## Your Next Steps (5-10 minutes)

### 1. Push to GitHub
```bash
cd C:\xampp\htdocs\lebanese-marketplace
git add .
git commit -m "Ready for deployment"

# Create repo on GitHub, then:
git remote add origin https://github.com/YOUR_USERNAME/lebanese-marketplace.git
git push -u origin main
```

### 2. Deploy on Railway
1. Go to [railway.app](https://railway.app) → Sign up with GitHub
2. "+ New Project" → "Deploy from GitHub repo"
3. Choose `lebanese-marketplace`
4. "+ New" → "Database" → "Add MySQL"

### 3. Configure Environment
In Railway service → "Variables" tab, add:
```env
APP_NAME=Lebanese Marketplace
APP_ENV=production
APP_DEBUG=false
APP_URL=YOUR_RAILWAY_URL_HERE

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=public
```

### 4. Generate APP_KEY
1. Railway → Service → "Settings" → custom command:
   ```bash
   php artisan key:generate --show
   ```
2. Copy the key from logs
3. Add to `APP_KEY` variable
4. Remove custom command

### 5. Run Migrations
1. Custom deploy command (one-time):
   ```bash
   php artisan migrate:fresh --seed --force
   ```
2. Redeploy
3. Remove custom command

### 6. Access Your Site
- URL: Check Railway "Settings" → "Domains"
- Admin: `admin@marketplace.com` / `admin123`
- **Change password immediately!**

## Full Documentation
See `DEPLOYMENT.md` for detailed guide with screenshots and troubleshooting.

---

**Ready to deploy!** 🚀
