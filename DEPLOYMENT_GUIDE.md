# Tour Management Software - Deployment Guide

## 📋 System Overview

A complete Tour Management System with three main modules:
1. **Admin Module** - Complete tour, member, expense, and payment management
2. **Member Module** - Tour browsing, joining, payment tracking
3. **Website Module** - Public landing page and tour listing

---

## 🚀 Quick Start (Fresh Installation)

### Step 1: Install Dependencies
```bash
composer install
npm install
```

### Step 2: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tour_management
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Run Migrations and Seeders
```bash
php artisan migrate:fresh --seed
```

This will create:
- ✅ All database tables
- ✅ Default roles and permissions (Laratrust)
- ✅ 6 Expense categories (Room, Transport, Food, Entry Fee, Guide, Other)

### Step 4: Storage Link
```bash
php artisan storage:link
```

### Step 5: Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 📊 Database Schema

### Main Tables Created (in order):
1. `users` - Admin and Member users
2. `tours` - Tour information with costs and capacity
3. `expense_categories` - Expense categorization
4. `tour_members` - Member enrollments in tours
5. `tour_schedules` - Daily tour schedules
6. `expenses` - Tour expense tracking
7. `payments` - Member payment records

### Key Relationships:
- `tours` → `tour_members` (One-to-Many)
- `tours` → `tour_schedules` (One-to-Many)
- `tours` → `expenses` (One-to-Many)
- `tours` → `payments` (One-to-Many)
- `users` → `tour_members` (One-to-Many)
- `expense_categories` → `expenses` (One-to-Many)

---

## 🎯 Features Implemented

### Admin Module (/dashboard)
- ✅ Dashboard with real-time statistics
- ✅ Tour Management (CRUD + Schedule Management)
- ✅ Member Management (Add members to tours, assign room/seat)
- ✅ Expense Management (CRUD with categories)
- ✅ Expense Category Management (CRUD)
- ✅ Payment Management (CRUD)
- ✅ User Management (Admin & General Users)
- ✅ Roles & Permissions

### Member Module (/member)
- ✅ Registration & Login
- ✅ Dashboard with statistics
- ✅ Profile Management
- ✅ Browse Available Tours
- ✅ Join Tour
- ✅ Current Tour (with schedule, members, payment summary)
- ✅ Tour History
- ✅ Add Payment
- ✅ Payment History

### Website Module (Public)
- ✅ Modern Landing Page
- ✅ Tour Listing with Search, Filter, Sort
- ✅ Member Registration

---

## 🗂️ Key Files & Structure

### Models
- `User.php` - Admin and Member users
- `Tour.php` - Tours with status (upcoming, ongoing, completed, closed)
- `TourMember.php` - Tour enrollments with join_status
- `TourSchedule.php` - Daily tour schedules
- `Expense.php` - Tour expenses
- `ExpenseCategory.php` - Expense categories
- `Payment.php` - Member payments

### Controllers (Admin)
- `TourController.php` - Tour CRUD
- `TourScheduleController.php` - Schedule management
- `MemberManagementController.php` - Member-tour assignments
- `ExpenseController.php` - Expense CRUD
- `ExpenseCategoryController.php` - Category CRUD
- `PaymentController.php` - Payment CRUD

### Controllers (Member)
- `MemberController.php` - All member functionalities

### Controllers (Public)
- `LandingController.php` - Landing page and tour listing

### Services
- `TourService.php`
- `TourScheduleService.php`
- `MemberManagementService.php`
- `ExpenseService.php`
- `ExpenseCategoryService.php`
- `PaymentService.php`
- `MemberService.php`
- `DashboardService.php`

### Views
**Admin:** `resources/views/admin/`
- tour/ (CRUD views)
- tour-schedule/ (Schedule management)
- member-management/ (Member assignments)
- expense/ (CRUD views)
- expense-category/ (Category management)
- payment/ (CRUD views)

**Member:** `resources/views/member/`
- dashboard.blade.php
- profile.blade.php
- tours.blade.php
- current-tour.blade.php
- tour-history.blade.php
- add-payment.blade.php
- payment-history.blade.php
- register.blade.php

**Public:** `resources/views/`
- landing.blade.php
- tour-listing.blade.php

---

## 🔐 Default Seeded Data

### Expense Categories:
1. Room
2. Transport
3. Food
4. Entry Fee
5. Guide
6. Other

### Roles (Laratrust):
- Superadmin
- Admin
- User (Member)

---

## 🎨 Navigation Structure

### Admin Sidebar:
- Dashboard
- Tour Management
  - Tours
  - Member Management
- Financial Management
  - Expenses
  - Expense Categories
  - Payments
- User Management
  - Admin Users
  - General Users
- Access Control
  - Roles
  - Permissions

### Member Sidebar:
- Dashboard
- My Profile
- Browse Tours
- My Current Tour
- Tour History
- Add Payment
- Payment History

---

## ✅ Pre-Migration Checklist

Before running `migrate:fresh --seed`, ensure:
1. ✅ Database connection configured in `.env`
2. ✅ Database exists (create manually if needed)
3. ✅ PHP version >= 8.1
4. ✅ All composer dependencies installed
5. ✅ Storage directory writable

---

## 🐛 Common Issues & Solutions

### Issue: Migration fails with foreign key constraint
**Solution:** Run `php artisan migrate:fresh` (without seed first)

### Issue: ExpenseCategory seeder fails
**Solution:** Ensure `expense_categories` table exists before seeding

### Issue: Images not showing
**Solution:** Run `php artisan storage:link`

### Issue: 404 on routes
**Solution:** Run `php artisan route:clear && php artisan route:cache`

---

## 📝 Migration Order (Verified)

1. users
2. cache, jobs
3. laratrust tables (roles, permissions)
4. notifications, personal_access_tokens, settings
5. **tours** (2025_11_17_074615)
6. **expense_categories** (2025_11_17_074620)
7. **tour_members** (2025_11_17_074625)
8. **tour_schedules** (2025_11_17_074630)
9. **expenses** (2025_11_17_104741) - depends on tours, expense_categories
10. **payments** (2025_11_17_104757) - depends on tours, users

---

## 🎯 Post-Deployment Steps

1. Create admin user manually or via seeder
2. Set up roles and permissions
3. Create sample tours for testing
4. Test member registration flow
5. Test tour joining and payment flow

---

## 📞 Support

For any issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Database migrations: `php artisan migrate:status`
3. Route list: `php artisan route:list`

---

## ✨ System is Ready!

Just run:
```bash
php artisan migrate:fresh --seed
php artisan serve
```

And you're good to go! 🚀

