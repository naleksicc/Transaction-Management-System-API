# Transaction Management System API

PHP-based REST API built with Symfony for managing financial transactions. The system provides endpoints for creating and retrieving transactions with automatic status assignment and CSV-based data persistence.

---

⚠️ **DEVELOPER NOTICE**  
For detailed technical documentation, architecture overview, and developer guidance, please refer to [README.md](docs/README.md) inside the `docs` folder in the root of the project.

---

## Features

- **Transaction Management**: Create and retrieve financial transactions via REST API
- **Automatic Status Assignment**: Random status assignment (Pending, Settled, Failed) for new transactions
- **Data Validation**: Comprehensive input validation with detailed error messages
- **CSV Storage**: File-based data persistence using structured CSV format
- **Error Handling**: Robust exception handling with custom error classes and global error boundaries
- **Middleware Architecture**: Request validation middleware for input sanitization
- **Service Layer**: Modular service architecture with dependency injection
- **Testing Support**: PHPUnit test suite with comprehensive coverage

## Prerequisites

- **PHP**: 8.2 or higher
- **Composer**: 2.0 or higher
- **Web Server**: Apache/Nginx (for production) or Symfony CLI (for development)
- **Git**: For cloning the repository
- **Extensions**: `ext-ctype`, `ext-iconv` (usually included with PHP)

## Installation

1. **Clone the Repository**:
   
   HTTPS:
   ```bash
   git clone https://github.com/youruser/transaction-management-system-api.git
   cd transaction-management-system-api
   ```
   
   SSH:
   ```bash
   git clone git@github.com:youruser/transaction-management-system-api.git
   cd transaction-management-system-api
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   # Copy the example environment file
   cp .env.example .env
   
   # Edit .env file with your configuration
   # Set APP_ENV=dev for development or APP_ENV=prod for production
   ```

4. **Set Up Data Directory**:
   ```bash
   # Ensure data directory has proper permissions
   chmod 755 data/
   chmod 644 data/transactions.csv
   ```

## Running the Application

### Development Server (Recommended for Development)

Using Symfony CLI:
```bash
# Install Symfony CLI if not already installed
# https://symfony.com/download

# Start the development server
symfony serve
```
The API will be available at `http://127.0.0.1:8000`

### Alternative: PHP Built-in Server
```bash
# Start PHP development server
php -S localhost:8000 -t public/
```
The API will be available at `http://localhost:8000`

### Production Deployment

For production deployment, configure your web server (Apache/Nginx) to point to the `public/` directory and ensure proper file permissions.

## Data Storage

### Environment Configuration Required

Before the application can automatically manage its data storage, you need to configure the CSV storage path:

1. **Set CSV Storage Path**: In your `.env` file, configure the storage location:
   ```env
   CSV_STORAGE_PATH=data/transactions.csv
   ```
   
   **Note**: You can use any path relative to your project root directory. Examples:
   ```env
   # Alternative locations
   CSV_STORAGE_PATH=storage/data/transactions.csv
   CSV_STORAGE_PATH=files/transaction_data.csv
   CSV_STORAGE_PATH=app_data/transactions.csv
   ...
   ```

2. **Verify Configuration**: Ensure the path is relative to your project root directory and the application has write permissions to the parent directory.

### Automatic Directory Creation

Once the environment is properly configured, the application automatically manages its data storage setup:

- **Directory Creation**: The directory specified in your `CSV_STORAGE_PATH` is created automatically when the application first runs
- **CSV File**: The CSV file is created automatically with proper headers if it doesn't exist
- **Permissions**: The application sets appropriate file permissions (755 for directories, 644 for files)
- **Error Handling**: If directory creation fails due to permission issues, the application will provide clear error messages

**Important**: The `CSV_STORAGE_PATH` environment variable must be set before running the application. Without this configuration, the application cannot determine where to store transaction data.

## API Documentation

### Base URL
- Development: `http://127.0.0.1:8000`
- Production: `https://your-domain.com`

### Endpoints

#### Get All Transactions
- **Method**: `GET`
- **URL**: `/transactions`
- **Description**: Retrieve all transactions from the system

**Response Example**:
```json
[
  {
    "Transaction Date": "2025-03-01",
    "Account Number": "7289-3445-1121",
    "Account Holder Name": "Maria Johnson",
    "Amount": "150.00",
    "Status": "Settled"
  }
]
```

#### Create Transaction
- **Method**: `POST`
- **URL**: `/transactions`
- **Content-Type**: `application/json`

**Request Body**:
```json
{
  "transactionDate": "2025-03-15",
  "accountNumber": "1234-5678-9012",
  "accountHolderName": "John Doe",
  "amount": 250.75
}
```

**Success Response** (201 Created):
```json
null
```

**Error Response** (400 Bad Request):
```json
{
  "error": "Validation failed",
  "violations": [
    "Transaction date is required",
    "Account number must be in format XXXX-XXXX-XXXX"
  ]
}
```

### CORS Support
The API includes CORS support for cross-origin requests via OPTIONS method handling.

## Testing

### Run All Tests
```bash
# Using Composer script
composer test

# Or directly with PHPUnit
./vendor/bin/phpunit
```

### Run Specific Test Suites
```bash
# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage/

# Run specific test file
./vendor/bin/phpunit tests/Services/Transaction/TransactionServiceTest.php
```

## Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application Environment
APP_ENV=dev
APP_SECRET=your-secret-key

# CSV File Path (relative to project root)
CSV_FILE_PATH=data/transactions.csv
```

### Service Configuration

Services are configured via Symfony's dependency injection container in `config/services.yaml`.

## Development

### Code Style
The project follows PSR-12 coding standards with strict typing enabled.

### Architecture
- **Controllers**: Handle HTTP requests and responses
- **Services**: Business logic implementation
- **Middleware**: Request preprocessing and validation
- **Storage**: Data persistence layer
- **Constants**: Centralized configuration values
- **Exceptions**: Custom error handling

Remember: This documentation assumes familiarity with REST APIs and basic web development concepts. For detailed technical implementation details, refer to the comprehensive documentation in the `docs/` folder.
