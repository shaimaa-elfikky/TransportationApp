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

7. **Seed Database (Admin user + roles/permissions)**:
   ```bash
   php artisan db:seed
   ```
8. **Generate Filament Shield Permissions**:
   ```bash
   php artisan shield:generate
   ```

###  Frontend
   This project uses **Vite** with **Tailwind CSS v3** for styling.  
   You don’t need to install Tailwind globally — it’s already included in `package.json`.  

   If you need to re-install or update Tailwind, run:
   ```bash
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init -p
  ```

###  Default Admin & Roles

#### Admin User (Seeded)
- **Email**: `admin@admin.com`  
- **Password**: `12345678`  
- **Role**: `admin`  
- **Access**: Full access to all resources, roles, and permissions.

#### Manager Role
- **Email**: `manager@manager.com`  
- **Password**: `12345678`  
- **Role**: `manager``  
- When you create a user with the role **manager**:
  - ✅ Can access all modules and features.  
  - ❌ Cannot manage **roles & permissions**.  
  - ❌ Cannot delete **companies**. 

#### Driver Role
- **Email**: `driver@driver.com`  
- **Password**: `12345678`  
- **Role**: `driver`  
- When you create a user with the role **Driver**:
  - ✅ Can access only trip view.     

   > Roles and permissions are managed via [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) and [Filament Shield](https://github.com/bezhanSalleh/filament-shield).  
   > If you update roles, re-run:
   ```bash
   php artisan shield:generate


###  Trips & Overlapping Technique

   The `Trip` model supports an **overlapping detection** feature to ensure that no two trips conflict in their schedules.

####  How It Works
   The `scopeOverlapping` method checks if a given time range overlaps with any existing trips:

 


###  Resource Availability Validation

   The application prevents assigning **drivers** or **vehicles** to trips if they are already booked for overlapping schedules.  
   This is handled by the custom validation rule `ResourceIsAvailable`.

####  Rule: `ResourceIsAvailable`

   Located in: `App\Rules\ResourceIsAvailable`

### Running Tests
   ```bash
   # Run all tests
   php artisan test
   ```

**Test Coverage**: The application includes comprehensive tests for trip overlapping detection, resource availability validation, and form conflict prevention to ensure reliable scheduling operations.




