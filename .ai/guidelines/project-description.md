# Project Context : Apple Nepal

## 1. Project Overview
This project is a high-performance, bilingual (Nepali and English) web portal. The platform serves a dual purpose:
1. **Device Specifications Database:** Detailed specifications, comparisons, and pricing (in NPR) for phones, laptops, and tech gadgets.
2. **News Portal:** A scalable news publishing platform starting with tech news, architected to scale into general news, politics, and local events.

**Target Traffic:** The architecture must be built to handle aggressive traffic spikes (proven past loads of 30,000+ rapid requests). Performance, caching, and database query optimization are paramount.

---

## 2. Technology Stack
- **Language/Framework:** PHP 8.3+ / Laravel 13.x
- **Frontend Reactive Framework:** Livewire v4 + Alpine.js
- **Styling:** Tailwind CSS v4
- **Database:** MySQL 8.0+ (or PostgreSQL equivalent)
- **Caching & Queues:** Laravel-inbuilt - Database
- **Search:** Laravel Scout (Meilisearch or Algolia) for rapid hardware and news querying.

---

## 3. Core Architecture & Modules

### 3.1 Multilingual System (English & Nepali)
- **Implementation:** Content models (Articles, Product Names) must support dual languages. Use JSON-based translation columns (e.g., `spatie/laravel-translatable`) or localized EAV tables.
- **Frontend:** Routing should support locale prefixes (e.g., `/np/news/...` vs `/en/news/...`) or a GET parameter (`en` by default, switchable to `np`).
- **Admin Interface:** Admins should be able to input content in both languages seamlessly, with side-by-side input fields for English and Nepali. Validation should ensure that at least one language is filled for critical fields (e.g., article title, product name).

### 3.2 News Module
- **Models:** `Article`, `Category`, `Tag`, `Author`.
- **Features:** Draft/Publish states, Scheduled Publishing, View counters (Redis-backed to avoid DB locking), SEO metadata.

### 3.3 Specifications Module
- **Models:** `Product`, `Brand`, `Category` (Phone, Laptop).
- **Data Structure:** Because specs vary wildly between laptops and phones, implement a flexible schema. Use JSON columns for generic spec sheets or an Entity-Attribute-Value (EAV) pattern for filterable attributes (e.g., "RAM", "Processor", "Battery").
- **Features:** Device comparison engine, historical price tracking.

---

## 4. Strict AI Coding Directives

When writing code for this repository, AI agents MUST adhere to the following rules:

### PHP & Laravel
1. **Strict Typing:** Always use strict typing (`declare(strict_types=1);`). All methods must have defined parameter and return types.
2. **Fat Models, Skinny Controllers:** Encapsulate business logic in Models or dedicated Action/Service classes. Controllers should primarily handle HTTP request/response.
3. **Database Optimization:** - NEVER introduce N+1 queries. Always use `with()` for eager loading.
    - Index all heavily queried columns (foreign keys, slugs, statuses).
4. **Caching:** Wrap all read-heavy, public-facing queries (e.g., homepage articles, latest specs) in `Cache::remember`.

### Livewire v4
1. Modern Syntax: Utilize Livewire v4's latest native features (`#[Validate]`, `#[Computed]`, `#[Locked]`). Do not use legacy Livewire v2/v3 patterns.
2. Component Granularity: Break down large views into smaller, highly cohesive Livewire components. Keep state management minimal to reduce network payload.
3. Form Submissions: Use Livewire Form Objects (`Livewire\Form`) for complex data entry rather than bloating the main component.
4. Single File Components (SFCs): For smaller, highly cohesive UI elements, prefer Livewire v4's Single File Component format (combining PHP logic and Blade template within a single `.blade.php` file using the `<?php` script tag) to reduce file sprawl.
5. Islands Architecture & Lazy Loading: Maximize performance for heavy database queries (like complex spec grids or deep news comment sections) by utilizing Livewire v4 islands. Isolate non-critical sections of the page using the `lazy` or `defer` attribute.
6. Custom Placeholders: Always provide explicit, lightweight HTML placeholders using the `@placeholder` attribute when lazy/defer loading components to ensure a seamless, cumulative layout shift (CLS)-free user experience during slow network requests.

### Tailwind CSS v4
1. **Utility-First:** Stick strictly to utility classes in Blade files.
2. **Modern Directives:** Ensure compatibility with Tailwind v4 engine changes. Avoid deprecated arbitrary values if native v4 variables exist.

---

## 5. Security & Deployment
- Validate all user/admin inputs using Form Requests or Livewire attributes.
- Use Laravel's built-in XSS and CSRF protections strictly.
- Ensure all media uploads (news thumbnails, product images) are sanitized, compressed, and stored on scalable storage (S3/Cloudflare R2).
