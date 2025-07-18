# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application for "Sistem Informasi Pengajuan Surat Online" - an online letter submission system for Indonesian university students. The application manages the complete lifecycle of formal letter requests through multi-level administrative approval processes with dynamic workflows, PDF generation, and comprehensive role-based access control.

## Development Commands

### Environment Setup
```bash
# Install dependencies
composer install
npm install

# Environment configuration
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Cache configuration (required after timezone/config changes)
php artisan config:cache
```

### Development Workflow
```bash
# Start development server with all services (recommended)
composer dev
# This runs: php artisan serve, php artisan queue:listen, php artisan pail, and npm run dev concurrently

# Individual commands
php artisan serve        # Start Laravel server
npm run dev             # Start Vite development server
npm run build           # Build assets for production
php artisan queue:work  # Process background jobs
```

### Testing
```bash
# Run all tests with Pest PHP
composer test
# This runs: php artisan config:clear and php artisan test

# Run specific test types
php artisan test --filter=Unit
php artisan test --filter=Feature
```

### Code Quality
```bash
# Laravel Pint for code formatting
./vendor/bin/pint

# Check for style issues without fixing
./vendor/bin/pint --test
```

## Architecture Overview

### Application Purpose & Core Workflow
The system manages formal letter submissions through a sophisticated approval pipeline:
1. **Student Submission** → Dynamic form submission with file uploads
2. **Multi-level Approval** → Configurable approval workflows per letter type
3. **Document Generation** → PDF generation using customizable templates  
4. **Final Distribution** → Approved letters with official numbering

### Role-Based Access Control (RBAC)
Comprehensive multi-role system with time-based assignments:

**Primary Roles:**
- **`mahasiswa`** (Student) - Submit and track letter requests
- **`dosen`** (Lecturer/Dosen PA) - First-level approval for assigned students
- **`kaprodi`** (Head of Study Program) - Second-level approval  
- **`wadek1`** (Vice Dean 1) - Third-level approval
- **`tu`** (Administrative Office) - Final processing and document generation
- **`bak`** (Academic Administration Bureau) - Alternative final processing with same permissions as TU
- **`admin`** - System administration

**Advanced Features:**
- **Annual role assignments** via `DosenPaTahunan` and `KaprodiTahunan` tables
- **Dynamic approval workflows** configurable per letter type in `JenisSurat.approval_flow` JSON field
- **Dual-role support** (users can be both Dosen PA and Kaprodi)
- **Contextual permissions** based on student cohort and study program

### Core Models and Relationships
```
Users ← Mahasiswa → Prodi → Fakultas
Users ← DosenPaTahunan → Prodi (yearly assignments)  
Users ← KaprodiTahunan → Prodi (yearly assignments)
PengajuanSurat → JenisSurat → JenisSuratField (dynamic forms)
PengajuanSurat → PengajuanDetail (EAV pattern for flexible data)
PengajuanSurat → SuratTemplate (document generation)
```

### Dynamic Approval Workflow System
**Revolutionary Features:**
- **Configurable approval chains** stored as JSON in `JenisSurat.approval_flow`
- **Step-by-step processing** with `current_step` tracking
- **Dynamic role validation** at each approval step
- **Parallel approval support** for complex workflows
- **Approval history logging** with timestamps and rejection reasons

**Example Workflows:**
- Simple: `["dosen_pa", "kaprodi", "tu"]`
- Complex: `["dosen_pa", "kaprodi", "wadek1", "bak"]` 
- Alternative: `["kaprodi", "wadek1"]` (skip Dosen PA for certain letter types)

### Dynamic Form System with EAV Pattern
**Advanced Form Engine:**
- **Runtime form generation** based on `JenisSuratField` configuration
- **Multiple field types**: text, email, select, checkbox, file upload, textarea, number
- **Dynamic validation rules** stored as JSON and processed at runtime
- **Auto-populated fields** (nama, nim, fakultas, prodi, angkatan) from user context
- **File upload management** with automatic storage and metadata tracking
- **EAV storage** in `PengajuanDetail` for maximum flexibility

### Document Management & PDF Generation
**Professional Template System:**
- **WYSIWYG template editor** for letter layouts
- **Variable interpolation** with `{{placeholder}}` syntax  
- **CSS styling support** for professional document formatting
- **DomPDF integration** for server-side PDF generation
- **Bulk generation** capabilities for administrative efficiency
- **Version control** with active/inactive template states

### Data Import/Export Capabilities
**Excel Integration Features:**
- **Bulk student import** via Excel files with comprehensive validation
- **NIM auto-detection** with automatic prodi assignment (format: YYYYPPXXXX)
- **Prodi code mapping**: 21=Teknik Elektro, 22=Teknik Industri, 23=TTI, 24=Sistem Informasi, 25=Teknik Mesin
- **Data export** for reporting and analysis
- **Template downloads** for standardized data entry
- **Route ordering critical**: Specific routes (users/import, users/export, users/sample) must be placed BEFORE resource routes to prevent 404 errors

## Key Technical Implementations

### Key Packages and Technologies
**Core Dependencies:**
- **Laravel 12.0** with latest features
- **Laravel Breeze** for authentication scaffolding
- **Yajra DataTables** for advanced table management with server-side processing
- **Maatwebsite/Laravel-Excel** for data import/export functionality
- **DomPDF** for PDF document generation
- **Tailwind CSS** with Vite for modern frontend development
- **Pest PHP** for testing framework

### Database Architecture Patterns
**Design Patterns Used:**
- **EAV (Entity-Attribute-Value)** pattern for dynamic form data in `PengajuanDetail`
- **Strategy pattern** for approval workflows in `JenisSurat.approval_flow`
- **Template pattern** for document generation via `SuratTemplate`
- **State machine** pattern for submission status tracking
- **Time-based role assignments** for academic staff

### Important File Locations
**Critical Files:**
- **Routes**: `routes/web.php` - Complex role-based routing with proper ordering
- **Main Controllers**: `app/Http/Controllers/PengajuanSuratController.php`, `app/Http/Controllers/UsersController.php`
- **User Model**: `app/Models/User.php` - Contains role checking and display methods
- **Dynamic Models**: `app/Models/JenisSurat.php` - Approval flow logic
- **Views**: `resources/views/` - Blade templates organized by role
- **File Storage**: `storage/app/public/pengajuan_files/` - Uploaded files organized by submission ID

### Configuration Notes
**Timezone & Localization:**
- **Timezone**: `Asia/Jakarta` (UTC+7) configured in `.env` and `config/app.php`
- **Locale**: Indonesian (`id`) for consistent date/time formatting
- **Environment variables**: `APP_TIMEZONE`, `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`

### Security and Validation
**Security Measures:**
- **Custom middleware**: `RoleMiddleware` for role-based access control
- **CSRF protection** on all forms
- **File upload validation** with type and size restrictions
- **SQL injection prevention** via Eloquent ORM
- **XSS protection** through Blade templating
- **Role-based file access** permissions in controllers

### Testing Framework Details
**Pest PHP Configuration:**
- **Feature tests** in `tests/Feature/` for integration testing
- **Unit tests** in `tests/Unit/` for component testing
- **Database testing** uses SQLite in-memory database
- **Custom test commands** via `composer test`

## Development Best Practices

### Role Assignment Logic
- **Annual assignments**: Check `DosenPaTahunan` and `KaprodiTahunan` for current year
- **Multi-role support**: Users can have multiple roles simultaneously
- **Permission checking**: Use `User::canApproveAs($role, $prodi_id, $year)` methods
- **Dynamic workflow validation**: Check `PengajuanSurat::canBeApprovedBy($user, $role)`

### File Upload System
- **Storage path**: `storage/app/public/pengajuan_files/{pengajuan_id}/`
- **Metadata storage**: JSON format in `pengajuan_details.value` field
- **Access control**: Download/view permissions based on user roles and submission ownership
- **File validation**: Type checking, size limits, and security scanning

### Frontend Integration
- **Modal-based workflows** for approvals and rejections
- **AJAX APIs** for real-time data fetching and updates
- **Progressive enhancement** with JavaScript
- **DataTables integration** with server-side processing for performance

### Route Ordering Critical Issue
**IMPORTANT**: In `routes/web.php`, specific routes like `users/import`, `users/export`, `users/sample` MUST be placed BEFORE the resource route `Route::resource('users', UsersController::class)` to prevent Laravel from treating specific route names as user ID parameters, which causes 404 errors.

### Performance Considerations
- **Eager loading** for reducing N+1 queries in relationships
- **Database indexing** on frequently queried fields (user_id, status, etc.)
- **Pagination** for large datasets in DataTables
- **File storage optimization** with organized directory structure
- **Caching strategies** for configuration and static data