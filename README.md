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

## Installation & Setup

1. **Clone the Repository**:
   ```bash
   # HTTPS (recommended for most users)
   git clone https://github.com/naleksicc/Transaction-Management-System-API.git
   cd Transaction-Management-System-API
   
   # OR SSH (if you have SSH keys configured)
   git clone git@github.com:naleksicc/Transaction-Management-System-API.git
   cd Transaction-Management-System-API
   ```

2. **Configure Environment**:
   ```bash
   # Copy the example environment file
   cp .env.example .env
   # Edit .env file with your configuration
   # Set APP_ENV=dev for development or APP_ENV=prod for production
   # Set CSV_STORAGE_PATH (see below)
   ```

3. **Install Dependencies**:
   ```bash
   composer install
   ```

4. **Set Up Data Storage** (Required Before Running):
   - In your `.env` file, configure the CSV storage location:
     ```env
     CSV_STORAGE_PATH=data/transactions.csv
     ```
   - The application will automatically create the directory and file if they do not exist, as long as the parent directory is writable.
   - **Permissions**: Ensure the parent directory is writable by the application (e.g., `chmod 755 data/`).

5. **(Optional) Seed Dummy Data**
   - After the file is created (e.g., after the first API call), you can optionally seed the CSV file with example transactions:
     ```bash
     php bin/seed_transactions.php
     ```

6. **Run the Application**

   **Development Server (Recommended):**
   ```bash
   symfony serve
   # or
   php -S localhost:8000 -t public/
   ```
   The API will be available at `http://localhost:8000/transactions` for both GET and POST requests.

   **Note:** There is no route at `/` (root). All API operations are under `/transactions`.

7. **Testing**
   ```bash
   composer test
   # or
   ./vendor/bin/phpunit
   ```

## Data Storage Details

- **CSV_STORAGE_PATH**: Set this in your `.env` file to specify where transaction data is stored (relative to project root).
- The application will create the directory and file if missing, and set appropriate permissions.
- If directory or file creation fails, a clear error message will be shown.

## API Endpoints

### Base URL
- Development: `http://localhost:8000/transactions`
- Production: `https://your-domain.com/transactions`

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

## Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application Environment
APP_ENV=dev
APP_SECRET=your-secret-key

# CSV File Path (relative to project root)
CSV_STORAGE_PATH=data/transactions.csv
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
