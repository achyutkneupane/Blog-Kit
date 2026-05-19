# Project Context: Modern Laravel Blog Kit

## 1. Project Overview
This project is a high-performance, SEO-optimized, and multilingual-ready starter kit for building robust blogging platforms and digital publications. The architecture is engineered to handle aggressive traffic spikes and provides a seamless, SPA-like user experience without the complexity of a separate JavaScript frontend.

**Target Traffic & Scale:** The platform is built to handle high concurrent user loads efficiently. Performance, intelligent caching strategies, and database query optimization are paramount to the system's core design.

---

## 2. Technology Stack
- **Language/Framework:** PHP 8.3+ / Laravel 13.x
- **Frontend Reactive Framework:** Livewire v4 + Alpine.js
- **Styling:** Tailwind CSS v4
- **Database:** MySQL 8.0+ (or PostgreSQL equivalent)
- **Caching & Queues:** Laravel-inbuilt (Redis recommended for production)
- **Search:** Laravel Scout (Meilisearch or Algolia) for rapid, typo-tolerant article querying.

---

## 3. Core Architecture & Modules

### 3.1 Multilingual System (i18n)
- **Implementation:** Content models (Articles, Categories, Tags) are architected to support multiple languages out of the box. Utilizes JSON-based translation columns (e.g., via `spatie/laravel-translatable`).
- **Frontend:** Dynamic routing supports locale prefixes (e.g., `/en/blog/...` vs `/es/blog/...`) for robust SEO indexing across different regions.
- **Admin Interface:** The backend provides a seamless localization experience, allowing editors to input and manage content in multiple languages simultaneously without leaving the page.

### 3.2 Core Publishing Module
- **Models:** `Article`, `Category`, `Tag`, `Author`, `Comment`.
- **Features:**
    - Rich text and Markdown editing capabilities.
    - Granular publication states (Draft, Review, Published, Archived).
    - Scheduled publishing capabilities.
    - View counters (designed to use Redis or atomic locks to avoid database locking under heavy load).
    - Built-in SEO metadata management (OpenGraph, Twitter cards, meta descriptions).

### 3.3 Media & Asset Management
- **Architecture:** Centralized media library for managing thumbnails, inline article images, and author avatars.
- **Features:** Automated image compression, responsive image generation, and native integration for scalable cloud storage solutions like AWS S3 or Cloudflare R2.

---

## 4. Strict AI Coding Directives

When writing code for this repository, all contributions MUST adhere to the following rules:

### PHP & Laravel
1. **Strict Typing:** Always use strict typing (`declare(strict_types=1);`). All methods must have defined parameter and return types.
2. **Fat Models, Skinny Controllers:** Encapsulate business logic in Models or dedicated Action/Service classes. Controllers should primarily handle HTTP request/response lifecycles.
3. **Database Optimization:**
    - NEVER introduce N+1 queries. Always use `with()` or `load()` for eager loading relationships.
    - Index all heavily queried columns (foreign keys, slugs, publication statuses).
4. **Caching:** Wrap all read-heavy, public-facing queries (e.g., homepage feeds, popular articles) in `Cache::remember` or similar caching strategies.

### Livewire v4
1. **Modern Syntax:** Utilize Livewire v4's latest native features (`#[Validate]`, `#[Computed]`, `#[Locked]`). Do not use legacy Livewire v2/v3 patterns.
2. **Component Granularity:** Break down large views into smaller, highly cohesive Livewire components. Keep state management minimal to reduce network payloads.
3. **Form Submissions:** Use Livewire Form Objects (`Livewire\Form`) for complex data entry (e.g., creating articles) rather than bloating the main component logic.
4. **Single File Components (SFCs):** For smaller, UI-focused elements, prefer Livewire v4's Single File Component format (combining PHP logic and Blade template within a single `.blade.php` file) to reduce file sprawl.
5. **Islands Architecture & Lazy Loading:** Maximize performance for heavy queries (like deep comment sections or "Related Articles" widgets) by isolating non-critical sections of the page using the `lazy` or `defer` attributes.
6. **Custom Placeholders:** Always provide explicit, lightweight HTML placeholders using the `@placeholder` directive when lazy-loading components to ensure a cumulative layout shift (CLS)-free user experience.

### Tailwind CSS v4
1. **Utility-First:** Stick strictly to utility classes in Blade files. Avoid writing custom CSS unless absolutely necessary.
2. **Modern Directives:** Ensure compatibility with the Tailwind v4 engine. Avoid deprecated arbitrary values if native v4 variables or syntax exist.

---

## 5. Security & Deployment
- **Input Validation:** Validate all user and admin inputs strictly using Laravel Form Requests or Livewire `#[Validate]` attributes.
- **Data Sanitization:** Ensure all media uploads are sanitized to prevent malicious payload execution.
- **Vulnerability Protection:** Utilize Laravel's built-in XSS, CSRF, and SQL Injection protections natively. Never bypass Eloquent's query builder for raw queries unless heavily sanitized.
