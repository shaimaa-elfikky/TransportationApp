## TransportationApp Installation Guide

Follow these steps to set up and run the TransportationApp:

### Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/shaimaa-elfikky/TransportationApp.git
   cd TransportationApp
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**:
   ```bash
   npm install
   ```
4. **Set Up Environment File**:
   ```bash
   cp .env.example .env
   ```
5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
6. **Run Database Migrations**:
   ```bash
   php artisan migrate
   ```
7. **Build Frontend Assets**:
   ```bash
   npm run dev
   ```
###  Frontend
   This project uses **Vite** with **Tailwind CSS v3** for styling.  
   You don’t need to install Tailwind globally — it’s already included in `package.json`.  

   If you need to re-install or update Tailwind, run:
   ```bash
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init -p
   ```




