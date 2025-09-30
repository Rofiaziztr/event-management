# Event Management System AI Assistant Instructions

This document provides essential context for AI assistants working with this Laravel-based event management system.

## Project Overview

-   Event management system built with Laravel 12 (PHP 8.2+)
-   Dual role system: Administrators and Participants with strict role-based access
-   Core features: QR-code based attendance tracking, multi-sheet Excel exports, email invitations
-   Indonesian UI with English codebase (note mixed language patterns)

## Architecture & Domain Models

### Core Entities & Relationships

-   `Event`: Central entity with auto-generated unique `code` for QR scanning, dynamic status calculation
-   `User`: Role-based (admin/participant) with NIP, division, institution fields for Indonesian organizational structure
-   `EventParticipant`: Join table managing many-to-many event participation
-   `Attendance`: Tracks actual check-ins via QR code with timestamps
-   `Document`: Event file attachments with uploader tracking
-   `Category`: Event categorization system

### Critical Patterns

#### QR Code Attendance System

-   Events auto-generate unique codes in `Event::booted()`
-   `ScanController::verify()` validates: participant registration → event active time → no duplicate attendance
-   Uses `endroid/qr-code` package with PNG writer
-   Attendance only allowed during `start_time` to `end_time` window

#### Role-Based Architecture

-   Routes grouped by role prefix: `/admin/*` and `/participant/*` with middleware `role:admin|participant`
-   Dashboard redirect logic based on `auth()->user()->role` in `/dashboard` route
-   Controllers namespaced: `Admin\EventController` vs `Participant\EventController`

#### Dynamic Event Status System

```php
// Event model has computed status based on time windows
public function getStatusAttribute() {
    if ($status === 'Dibatalkan') return 'Dibatalkan';
    $now = now();
    if ($now < $this->start_time) return 'Terjadwal';
    elseif ($now >= $this->start_time && $now <= $this->end_time) return 'Berlangsung';
    else return 'Selesai';
}
```

## Project-Specific Conventions

### Data Export Architecture

-   Multi-sheet Excel exports using `Maatwebsite\Excel` with `WithMultipleSheets` pattern
-   Exports use Traits (`PreparesEventData`) for data preparation
-   Sheet classes in `app/Exports/Sheets/` for modular export logic
-   Pattern: `EventReportExport` → `EventSummarySheet` + `EventParticipantsSheet` + `AttendanceReportSheet`

### Frontend Integration

-   `BladewindUI` components with Tailwind CSS
-   `html5-qrcode` library for camera-based QR scanning
-   `Chart.js` for dashboard analytics
-   `Flatpickr` for datetime inputs in forms

### Key Workflow Commands

```bash
# Development setup (Indonesian environment)
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve && npm run dev

# Testing with Pest PHP
php artisan test
```

### Indonesian Organizational Context

-   Users have `nip` (employee ID), `division`, `institution` fields
-   Support for external participants (non-employee invitations)
-   Event invitations by division with bulk operations
-   Manual attendance override for participants having QR issues

### Model Relationships Pattern

```php
// Event → Users (many-to-many via event_participants)
$event->participants() // BelongsToMany
$event->attendances() // HasMany for actual check-ins

// User methods for role checking
$user->isAdmin() // Helper method
$user->participatedEvents() // Many-to-many events
```

### Common Implementation Patterns

-   Event code generation via observers/model events
-   Logging for security-sensitive operations (attendance scanning)
-   Middleware chaining: `['auth', 'verified', 'role:admin']`
-   Export classes extending base export interfaces with traits
