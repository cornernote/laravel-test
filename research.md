# KeyVault Application - Research & Analysis

## Executive Summary

KeyVault is a **physical key management system** designed for locksmiths to manage their customers, locks, keys, and key issuance tracking. The application is a multi-tenant SaaS platform originally built with Yii Framework 1.1.12 and PHP 5.5, dating from approximately 2012-2013.

---

## Application Purpose

KeyVault provides a comprehensive solution for locksmiths to:
- Manage multiple customer accounts
- Track physical keys and locks in customer systems
- Issue keys to key holders (employees, residents, etc.)
- Monitor key distribution and track missing keys
- Process key orders with customer approval
- Maintain audit trails of all key-related activities
- Offer tiered subscription plans with usage quotas

---

## User Roles & Hierarchy

The application implements a **5-tier role-based access control system**:

### 1. **Admin** (Role ID: 1)
- Super user with full system access
- Can manage all accounts and view all data
- No quota restrictions

### 2. **Reseller** (Role ID: 2)
- Manages multiple locksmith accounts
- Acts as a distribution partner
- Has oversight of locksmith operations

### 3. **Locksmith** (Role ID: 3)
- Primary business user
- Manages multiple customer accounts
- Creates and manages locks, keys, and systems
- Subject to subscription plan quotas
- Has customer and key holder sub-accounts

### 4. **Customer** (Role ID: 4)
- Represents a business or property managed by a locksmith
- Can have multiple key holders
- Can view their systems, keys, and locks
- Can place orders for additional keys
- Manages their key holder permissions

### 5. **Key Holder** (Role ID: 5)
- End user (employee, resident, tenant)
- Assigned keys for specific locks
- Can view systems they have access to
- Can place key orders (if permitted)
- Requires digital signature when receiving keys

---

## Core Domain Models

### System
- **Purpose**: Represents a logical grouping of locks and keys (e.g., a building, facility, or security zone)
- **Relationships**: Belongs to locksmith and customer; has many locks and keys
- **Attributes**: Name, counts (cached totals for performance)

### Lock
- **Purpose**: Represents a physical lock in a system
- **Attributes**:
  - `stamped`: Unique identifier stamped on the lock
  - `description`: Descriptive text
  - `location`: Where the lock is installed
  - `reference`: Additional reference information
  - `image`: Optional image of the lock
- **Relationships**:
  - Belongs to system, locksmith, customer
  - Many-to-many with keys (via `key_to_lock` junction table)

### Key
- **Purpose**: Represents a unique physical key that can open one or more locks
- **Attributes**:
  - `stamped`: Unique identifier stamped on the key
  - `description`: Descriptive text
  - `issued_count`: Total number of key issues created
  - `ordered_count`: Number ordered
  - `missing_count`: Number reported missing
- **Relationships**:
  - Belongs to system, locksmith, customer
  - Many-to-many with locks
  - Has many key issues (individual physical keys issued to key holders)

### KeyIssue
- **Purpose**: Represents an individual physical key issued to a specific key holder
- **Attributes**:
  - `issue`: Sequential issue number for the key
  - `missing`: Boolean flag for lost/missing keys
  - `due_back`: Due date for temporary key assignments
  - `signature`: Digital signature from key holder
- **Relationships**:
  - Belongs to key, key holder, system, locksmith, customer
  - Can have a signature record
- **Business Logic**:
  - Automatically assigns issue numbers sequentially
  - Tracks missing keys
  - Supports batch creation for efficiency

### KeyHolder (extends User)
- **Purpose**: Person who receives physical keys
- **Special Features**:
  - Auto-creation from name when issuing keys
  - System-level access permissions (`KeyHolderAccess` table)
  - Can have web/API access or be name-only records
- **Access Control**:
  - `perm_view`: Can view system details
  - `perm_assign`: Can assign keys
  - `perm_order`: Can place key orders

### Order
- **Purpose**: Customer request for additional physical keys
- **Attributes**:
  - `status`: pending, processing, shipped, collected, cancelled
  - `purchase_order`: Customer's PO number
  - `shipping_method`: deliver or pickup
  - `password`: Requires password confirmation for security
- **Workflow**: Customers select keys, locksmith processes, updates status
- **Security**: Uses `Suid::encode()` for obfuscated order IDs

---

## Database Architecture

### Key Tables

1. **user** - Stores all user types (STI pattern via roles)
   - Single table for admin, reseller, locksmith, customer, key_holder
   - Distinguished by `user_to_role` relationships
   - Includes `locksmith_id` and `customer_id` for hierarchy

2. **system** - Logical grouping of locks/keys

3. **lock** - Physical locks

4. **key** - Physical keys

5. **key_to_lock** - Many-to-many relationship between keys and locks

6. **key_issue** - Individual key assignments to key holders

7. **order** / **order_key** - Key ordering system

8. **key_holder_access** - Granular permissions for key holders per system

### Supporting Tables

- **role** / **user_to_role** - RBAC implementation
- **audit_trail** - Field-level change tracking
- **page_trail** - Page visit and performance tracking
- **log** - Application events
- **email_spool** / **email_template** - Email management
- **token** - Password recovery and security tokens
- **signature** - Digital signatures for key receipt
- **attachment** - File uploads
- **model_cache** - Performance optimization
- **setting_eav** - Application configuration using EAV pattern
- **user_eav** / **order_eav** / **system_eav** - Extended attributes using EAV pattern

### Key Design Patterns

1. **Soft Deletes**: All main entities use `deleted` datetime column
2. **EAV (Entity-Attribute-Value)**: Flexible attributes via `*_eav` tables
3. **Audit Trail**: Comprehensive change tracking at field level
4. **Cached Counts**: Performance optimization via EAV stored counts

---

## Business Logic & Workflows

### Key Issuance Workflow
1. Locksmith creates a key in a system
2. Locksmith issues the key to a key holder (auto-creates key holder if needed)
3. Key holder receives key and provides digital signature
4. System tracks issue number, due date, and status
5. If key is lost, marked as missing and counts updated

### Key Ordering Workflow
1. Customer/Key Holder browses available keys in their systems
2. Adds keys to order with quantities
3. Provides password confirmation for security
4. Order enters "pending" status
5. Locksmith reviews and processes order
6. Updates status: processing → shipped/collected
7. Email notifications at status changes

### Locksmith Subscription Plans
- **Free**: 1 customer, 1 system, 10 locks, 20 keys
- **Small** ($29): 5 customers, 10 systems, 250 locks, 2,500 keys
- **Medium** ($55): 50 customers, 100 systems, 2,500 locks, 25,000 keys
- **Large** ($110): 500 customers, 1,000 systems, 25,000 locks, 250,000 keys
- **Unlimited** ($220): 500K+ customers, 1M+ systems, 25M+ locks, 250M+ keys

### Quota Enforcement
- Checked before creating new records
- Plan expiration triggers downgrade to free tier
- Active subscriptions bypass expiration
- Counts cached in EAV for performance

---

## Technical Architecture

### Framework & Version
- **Framework**: Yii 1.1.12 (yii-1.1.12.b600af)
- **PHP**: 5.5.x (uses deprecated `mysql_*` functions)
- **Architecture**: MVC with Active Record pattern
- **Database**: MySQL 5.5+

### Key Technologies & Libraries
- **SwiftMailer**: Email delivery via spool system
- **reCAPTCHA**: Form protection
- **Signature Pad**: JavaScript signature capture
- **FancyBox**: Modal dialogs and image viewing
- **phpExcelReader**: Import from Excel
- **Bootstrap 2.x**: UI framework (Bounce theme)
- **Mustache**: Template engine for emails
- **PasswordHash**: Bcrypt password hashing

### Configuration System
Uses a two-tier settings system:
1. **Core Settings** (`setting_eav` where `entity='core'`):
   - `debug`, `debug_db`, `debug_toolbar`, `debug_levels`
   - `app_version`, `yii_version`, `yii_lite`
   - `timezone`, `memory_limit`, `time_limit`

2. **App Settings** (`setting_eav` where `entity='app'`):
   - `name`, `domain`, `email`, `phone`, `website`
   - `theme`, `language`, `dateFormat`, `defaultPageSize`
   - `recaptcha`, `rememberMe`, `allowAutoLogin`

### Custom Behaviors
- **SoftDeleteBehavior**: Manages `deleted` column
- **AuditBehavior**: Tracks all field changes
- **EavBehavior**: Manages EAV attributes
- **CTimestampBehavior**: Auto-manages `created` column

### Helper Functions (globals.php)
Extensive shorthand functions for common operations:
- `app()`: Yii::app()
- `user()`: Current user
- `url()`, `route()`, `request()`
- `t()`: Translation
- `l()`: Link generation
- `h()`: HTML encoding
- `email()`: Email manager
- `cache()`: Caching
- `debug()`, `printr()`: Debugging

---

## Features & Functionality

### Core Features
1. **Multi-tenant Architecture**: Separate data for each locksmith
2. **Hierarchical Access Control**: 5-tier role system with granular permissions
3. **Key/Lock Management**: Complete CRUD for systems, locks, keys
4. **Key Issuance Tracking**: Track every physical key issued
5. **Digital Signatures**: Key holders sign when receiving keys
6. **Missing Key Tracking**: Flag and count lost keys
7. **Order Management**: Customers can order additional keys
8. **Bulk Operations**: Create multiple keys, locks, key holders at once
9. **Audit Trail**: Complete history of all changes
10. **Email Notifications**: Template-based email system with spool
11. **API Access**: API keys and authentication for locksmith integrations

### Advanced Features
1. **ProMaster Import**: Bulk import from locksmith software Excel files
2. **Excel Export**: Export key/lock data
3. **Return URLs**: Maintains navigation context in AJAX modals
4. **Model Caching**: Performance optimization for complex relationships
5. **Batch Processing**: Efficient bulk key issuance
6. **Permission Management**: Fine-grained key holder access per system
7. **Due Date Tracking**: Temporary key assignments with due dates
8. **Security Features**:
   - Password confirmation for orders
   - API key generation
   - Token-based password recovery
   - reCAPTCHA protection

### UI/UX Features
- **AJAX Modal Dialogs**: Most forms open in modals
- **Grid Views**: Sortable, filterable data tables
- **Responsive Theme**: Bootstrap-based "Bounce" theme
- **Breadcrumbs**: Navigation context
- **Flash Messages**: User feedback
- **Ask to Save**: Prevents data loss on navigation
- **Client-side Validation**: Yii active form validation

---

## Data Migration Considerations

When migrating to Laravel, consider:

### Database Changes Needed
1. **Remove deprecated patterns**:
   - Replace EAV with proper JSON columns or polymorphic relationships
   - Consider single table inheritance vs separate tables for user types

2. **Modernize structure**:
   - Add proper foreign key constraints
   - Use Laravel's soft deletes convention (`deleted_at`)
   - Add indexes for performance

3. **Schema improvements**:
   - Normalize EAV attributes into proper columns where appropriate
   - Consider pivot table conventions for many-to-many relationships

### Code Migration Priorities
1. **Authentication & Authorization**:
   - Migrate to Laravel's auth scaffolding
   - Implement gates/policies for role-based access
   - Create middleware for role checks

2. **Models & Relationships**:
   - Preserve all relationships in Eloquent format
   - Implement soft deletes
   - Create model factories and seeders

3. **Business Logic**:
   - Extract to service classes
   - Preserve quota checking logic
   - Maintain count caching strategy (consider listeners/observers)

4. **Features to Preserve**:
   - Audit trail system (consider Laravel Auditing package)
   - Email spool and templates
   - Order workflow
   - Digital signature capture
   - Bulk operations

5. **UI Considerations**:
   - Migrate to modern frontend (Laravel + Alpine.js/Livewire or Filament)
   - Preserve modal dialog UX
   - Maintain grid functionality
   - Update to modern Bootstrap or Tailwind CSS

---

## Security Considerations

### Current Security Features
- Bcrypt password hashing
- API key authentication
- Role-based access control
- Password confirmation for sensitive operations
- Token-based password recovery
- reCAPTCHA on forms
- SQL injection protection via Active Record
- XSS protection via HTML encoding helpers

### Security Concerns to Address
1. **Deprecated Functions**: Uses `mysql_*` functions (vulnerable)
2. **Old PHP Version**: PHP 5.5 is end-of-life
3. **Framework Version**: Yii 1.1.12 is outdated
4. **Password Reset**: Token system needs review
5. **Session Management**: Review session security
6. **CSRF Protection**: Ensure comprehensive coverage

---

## Performance Optimizations

### Current Strategies
1. **Cached Counts**: Stores aggregate counts in EAV tables
2. **Model Cache**: Caches complex relationship data
3. **Query Optimization**: Uses `with()` for eager loading
4. **Batch Operations**: Minimizes database calls during bulk inserts
5. **Asset Publishing**: Manages static assets efficiently

### Recommendations for Laravel
1. Use Laravel's query builder and eager loading
2. Implement cache tags for cache invalidation
3. Use database transactions for bulk operations
4. Consider Redis for caching and sessions
5. Implement queue system for email sending
6. Use Laravel Telescope for debugging performance issues

---

## Conclusion

KeyVault is a well-architected **physical key management system** with a clear business domain and comprehensive feature set. The application demonstrates solid understanding of key tracking needs for locksmiths managing multiple customers and properties.

### Strengths
- Clear domain model
- Comprehensive audit trails
- Multi-tenant architecture
- Flexible permission system
- Quota-based subscriptions
- Bulk operations support

### Migration Strategy
The application is well-suited for migration to Laravel 12, with particular emphasis on:
1. Preserving the core business logic and workflows
2. Modernizing the UI with Filament
3. Implementing proper Laravel conventions
4. Adding modern features (notifications, queues, events)
5. Improving performance with caching strategies
6. Enhancing security with modern Laravel features

### Next Steps
1. Create comprehensive test suite to preserve business logic
2. Design new database schema with Laravel conventions
3. Build authentication and authorization system
4. Migrate models and relationships
5. Implement API endpoints if needed
6. Build modern UI with Filament
7. Add queue workers for email processing
8. Implement event-driven architecture for count updates and audit trails
