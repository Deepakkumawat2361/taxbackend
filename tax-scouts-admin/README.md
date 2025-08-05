<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# TaxScouts Admin Panel

A comprehensive Laravel-based admin panel for tax consultation services, inspired by TaxScouts.com. This system manages clients, accountants, tax returns, consultations, payments, and more.

## 🏗️ System Architecture

### Core Modules

#### 1. **Client Management**
- **Purpose**: Manage individual and business clients
- **Features**:
  - Client registration and profile management
  - Different client types (individual, self-employed, landlord, business, high earner)
  - Contact information and tax details
  - Client status tracking
  - Accountant assignment

#### 2. **Accountant Management**
- **Purpose**: Manage tax professionals and their expertise
- **Features**:
  - Accountant profiles with qualifications
  - Specialization tracking
  - Availability management
  - Client capacity limits
  - Performance ratings and reviews

#### 3. **Tax Returns**
- **Purpose**: Handle tax filing processes
- **Features**:
  - Multiple return types (Self Assessment, Corporation Tax, VAT, PAYE)
  - Income and expense tracking
  - Tax calculations
  - HMRC submission tracking
  - Deadline management

#### 4. **Consultation System**
- **Purpose**: Schedule and manage client meetings
- **Features**:
  - Multiple consultation types and methods
  - Scheduling system
  - Meeting notes and action items
  - Follow-up tracking
  - Client satisfaction ratings

#### 5. **Document Management**
- **Purpose**: Handle client tax documents
- **Features**:
  - Secure document upload
  - Document categorization (P60, P45, bank statements, etc.)
  - Review and approval workflow
  - OCR data extraction support
  - Sensitive document handling

#### 6. **Payment Processing**
- **Purpose**: Handle financial transactions
- **Features**:
  - Multiple payment methods
  - Gateway integration support
  - Invoice generation
  - Refund management
  - Payment tracking

#### 7. **Tax Advice System**
- **Purpose**: Provide structured tax guidance
- **Features**:
  - Categorized advice (planning, compliance, optimization)
  - Action item tracking
  - Legislation references
  - Potential savings calculations
  - Follow-up scheduling

#### 8. **Review & Rating System**
- **Purpose**: Collect and manage client feedback
- **Features**:
  - Multi-criteria ratings
  - Review moderation
  - Featured reviews
  - Accountant performance tracking

#### 9. **Content Management**
- **Purpose**: Manage blog posts and educational content
- **Features**:
  - Article publishing
  - SEO optimization
  - Content categorization
  - Related services linking

#### 10. **System Settings**
- **Purpose**: Configure system-wide settings
- **Features**:
  - Grouped configuration options
  - Type-safe value handling
  - Public/private settings
  - Validation rules

## 📊 Database Schema

### Main Tables

#### `clients`
```sql
- id (Primary Key)
- first_name, last_name
- email (Unique), phone
- date_of_birth, national_insurance_number, utr_number
- address, city, postcode, country
- client_type (individual, self_employed, landlord, business, high_earner)
- status (active, inactive, pending)
- annual_income, tax_years (JSON)
- assigned_accountant_id (Foreign Key)
- notes, last_contact
- timestamps
```

#### `accountants`
```sql
- id (Primary Key)
- first_name, last_name, email (Unique), phone
- qualification, years_experience
- specializations (JSON), status
- bio, profile_photo
- hourly_rate, max_clients, current_clients
- rating, total_reviews
- working_hours (JSON)
- available_for_new_clients
- timestamps
```

#### `tax_returns`
```sql
- id (Primary Key)
- client_id, accountant_id (Foreign Keys)
- tax_year, return_type
- status (pending, in_progress, review, completed, filed, rejected)
- total_income, total_expenses, taxable_income
- tax_due, tax_paid, refund_due
- deadline, submitted_date, filed_date
- hmrc_reference, hmrc_response
- income_sources, deductions, reliefs_claimed (JSON)
- notes, amendments_required
- timestamps
```

#### `services`
```sql
- id (Primary Key)
- name, slug (Unique), description, detailed_description
- category (tax_returns, tax_advice, business_planning, compliance, consultation)
- base_price, premium_price
- features, suitable_for (JSON)
- estimated_duration_hours
- requires_consultation, is_active, is_featured
- icon, banner_image, sort_order
- required_documents (JSON), process_steps
- timestamps
```

#### `consultations`
```sql
- id (Primary Key)
- client_id, accountant_id, service_id (Foreign Keys)
- type (initial, follow_up, tax_planning, review, urgent)
- method (phone, video, in_person, email)
- status (scheduled, in_progress, completed, cancelled, no_show)
- scheduled_at, started_at, ended_at, duration_minutes
- agenda, notes, action_items, client_questions
- documents_discussed (JSON), fee
- follow_up_required, next_consultation_date
- client_satisfaction, client_feedback
- timestamps
```

#### `documents`
```sql
- id (Primary Key)
- client_id, tax_return_id (Foreign Keys)
- name, original_filename, file_path
- mime_type, file_size
- document_type (p60, p45, bank_statement, invoice, receipt, etc.)
- tax_year, status (pending_review, approved, rejected, requires_clarification)
- notes, rejection_reason
- uploaded_by, reviewed_by (Foreign Keys)
- reviewed_at, is_sensitive
- extracted_data (JSON)
- timestamps
```

#### `payments`
```sql
- id (Primary Key)
- client_id, service_id, tax_return_id, consultation_id (Foreign Keys)
- payment_reference (Unique)
- amount, tax_amount, total_amount, currency
- status (pending, processing, completed, failed, refunded, cancelled)
- payment_method, payment_gateway
- gateway_transaction_id, gateway_response (JSON)
- description, paid_at, refunded_at
- refunded_amount, refund_reason
- invoice_number, invoice_sent, invoice_sent_at
- timestamps
```

#### `tax_advice`
```sql
- id (Primary Key)
- client_id, accountant_id, consultation_id (Foreign Keys)
- advice_reference (Unique)
- category (tax_planning, compliance, optimization, relief_claims, dispute_resolution, general)
- subject, client_question, advice_given, recommendations
- action_items, relevant_legislation, supporting_documents (JSON)
- potential_savings, implementation_deadline
- priority (low, medium, high, urgent)
- status (draft, reviewed, sent, implemented, archived)
- requires_follow_up, follow_up_date, follow_up_notes
- client_rating, client_feedback
- timestamps
```

#### `reviews`
```sql
- id (Primary Key)
- client_id, accountant_id, service_id, tax_return_id (Foreign Keys)
- overall_rating, communication_rating, expertise_rating
- timeliness_rating, value_rating
- review_text, positive_feedback, improvement_suggestions
- would_recommend, is_published, is_featured
- status (pending, approved, rejected)
- admin_notes, moderated_by, moderated_at
- timestamps
```

#### `blog_posts`
```sql
- id (Primary Key)
- title, slug (Unique), excerpt, content
- featured_image, gallery_images (JSON)
- status (draft, published, archived)
- category (tax_tips, legislation_updates, case_studies, guides, news, general)
- tags (JSON), author_id (Foreign Key)
- published_at, meta_title, meta_description, meta_keywords (JSON)
- views_count, likes_count, shares_count
- is_featured, allow_comments
- reading_time_minutes, related_services (JSON)
- timestamps
```

#### `settings`
```sql
- id (Primary Key)
- key (Unique), value, type (string, integer, boolean, json, text, file)
- group (general, tax_rates, notifications, etc.)
- label, description
- is_editable, is_public
- validation_rules (JSON), default_value
- sort_order
- timestamps
```

### Pivot Tables

#### `client_service`
```sql
- id (Primary Key)
- client_id, service_id (Foreign Keys)
- status (requested, in_progress, completed, cancelled)
- agreed_price, start_date, completion_date, deadline
- special_requirements, notes
- progress_percentage
- timestamps
```

## 🚀 Key Features

### Client Portal Features
- **Multi-type Client Support**: Individual, self-employed, landlords, businesses, high earners
- **Document Upload**: Secure document management with categorization
- **Real-time Status**: Track tax return progress and consultation schedules
- **Payment Integration**: Multiple payment methods with invoice generation

### Accountant Features
- **Workload Management**: Client capacity limits and availability tracking
- **Specialization Matching**: Automatic client-accountant pairing based on expertise
- **Performance Tracking**: Ratings, reviews, and performance metrics
- **Consultation Tools**: Comprehensive meeting management and note-taking

### Admin Features
- **Comprehensive Dashboard**: Real-time analytics and system overview
- **User Management**: Role-based access control for different user types
- **Content Management**: Blog posts and educational content publishing
- **System Configuration**: Flexible settings management

### Business Intelligence
- **Revenue Tracking**: Payment analytics and financial reporting
- **Performance Metrics**: Accountant performance and client satisfaction
- **Operational Analytics**: Consultation trends and service popularity
- **Compliance Monitoring**: Deadline tracking and regulatory compliance

## 🔧 Technical Stack

- **Framework**: Laravel 12.x
- **Database**: SQLite (development) / MySQL/PostgreSQL (production)
- **PHP Version**: 8.4+
- **Authentication**: Laravel built-in authentication
- **File Storage**: Laravel filesystem with configurable drivers
- **Queue System**: Laravel queues for background processing

## 📋 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd tax-scouts-admin
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed # Optional: seed with sample data
   ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🎯 Next Steps

1. **Create Admin Controllers** - Build controllers for all modules
2. **Design Admin Views** - Create responsive admin interface
3. **Implement Authentication** - Set up role-based access control
4. **Add Seeders & Factories** - Create sample data for testing
5. **API Development** - Build REST API for mobile/external integrations
6. **Testing Suite** - Comprehensive test coverage
7. **Deployment Configuration** - Production-ready setup

## 📝 API Endpoints (Planned)

### Client Management
- `GET /api/clients` - List clients
- `POST /api/clients` - Create client
- `GET /api/clients/{id}` - Get client details
- `PUT /api/clients/{id}` - Update client
- `DELETE /api/clients/{id}` - Delete client

### Tax Returns
- `GET /api/tax-returns` - List tax returns
- `POST /api/tax-returns` - Create tax return
- `GET /api/tax-returns/{id}` - Get tax return details
- `PUT /api/tax-returns/{id}/status` - Update status

### Consultations
- `GET /api/consultations` - List consultations
- `POST /api/consultations` - Schedule consultation
- `PUT /api/consultations/{id}` - Update consultation

### Documents
- `POST /api/documents/upload` - Upload document
- `GET /api/documents/{id}/download` - Download document
- `PUT /api/documents/{id}/review` - Review document

This system provides a complete foundation for a professional tax consultation service with room for extensive customization and scaling.
