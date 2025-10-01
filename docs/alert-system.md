# Alert System Documentation

The alert system provides an easy way to display notifications to users in the Event Management System. It supports both backend (PHP) and frontend (JavaScript) implementations, with automatic dismissal and progress indicators.

## Features

-   Four types of alerts: success, error, warning, info
-   Auto-dismissal with progress indicator
-   Custom titles, icons, and durations
-   Backend (PHP) and Frontend (JavaScript) APIs
-   Responsive design
-   Animated appearance/disappearance
-   Laravel session flash integration
-   Facade support

## Backend Usage (PHP)

### Using the Alert Helper

```php
use App\Helpers\Alert;

// Basic usage
Alert::success('Operation successful!');
Alert::error('Something went wrong!');
Alert::warning('Warning message');
Alert::info('Informational message');

// Advanced usage with options
Alert::success('Operation successful!', [
    'title' => 'Custom Title', // Optional
    'duration' => 8000, // Optional, milliseconds
]);
```

### Using the Alert Facade

```php
use App\Facades\Alert;

Alert::success('Operation successful!');
```

### In Controllers

```php
public function store(Request $request)
{
    // Process form...

    Alert::success('Data saved successfully!');

    return redirect()->route('dashboard');
}
```

## Frontend Usage (JavaScript)

### Basic Usage

```javascript
// Basic usage
window.showSuccess("Operation successful!");
window.showError("Something went wrong!");
window.showWarning("Warning message");
window.showInfo("Informational message");

// With custom duration (milliseconds)
window.showSuccess("Success message", 8000);

// With additional options
window.showSuccess("Success message", 5000, {
    title: "Custom Title",
    icon: "🚀", // Custom icon
});
```

### Advanced Usage

```javascript
// Direct access to the alert store
window.Alpine.store("app").addAlert("success", "Custom message", 5000, {
    title: "Custom Title",
    icon: "👍",
});
```

## Component Usage

The `<x-alert-handler>` component is automatically included in the app layout, so you don't need to add it manually. It will automatically display:

1. Alerts from session flash data
2. Alerts triggered by JavaScript

## Laravel Flash Messages Integration

The alert system automatically captures Laravel's built-in flash messages:

```php
return redirect()->back()->with('success', 'Operation completed successfully!');
return redirect()->back()->with('error', 'An error occurred!');
```

## Examples

See `/alert-examples` route for live examples and implementation patterns.
