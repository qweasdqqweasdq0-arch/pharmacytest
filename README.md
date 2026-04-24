# Pharmacy Management System - Quality Assurance Report

## 1. Automated Testing (CI/CD)
- **Status:** Passed ✅
- **Environment:** GitHub Actions with PHP 8.2 & SQLite.
- **Tools:** PHPUnit, GitLeaks.

## 2. Performance Testing
- **Search Query Latency:** 145ms (Average).
- **Stock Update Logic:** Optimized using Laravel Eloquent.
- **Database:** SQLite for local testing, MySQL for production.

## 3. End-to-End (E2E) Scenario
- **Scenario:** Patient searches for "Panadol" -> System verifies stock -> Patient creates a reservation.
- **Validation:** Asserting HTTP 200 on all critical endpoints.

