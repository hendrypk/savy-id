# 💰 Savy: Smart Personal Finance Manager

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue 3](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-9553E9?style=for-the-badge)](https://inertiajs.com)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php)](https://php.net)

**Savy** is a modern, mobile-first personal finance application designed to give you total control over your wealth. Manage budgets, track savings goals, and monitor loans through a sleek **Neo-Fintech** interface built with Glassmorphism aesthetics.

---

## 🚀 Core Features

### 🏦 Multi-Wallet Management
* **Consolidated Net Worth:** View your total balance across Cash, Bank accounts, and E-Wallets in one view.
* **Real-time Balance:** Instant wallet updates as you log transactions or move funds.

### 📊 Smart Budgeting & Tracking
* **Expense Allocation:** Set monthly limits for categories like Food, Transport, and Entertainment.
* **Visual Progress:** Dynamic progress bars that shift from Green to Red as you approach your limits.
* **Automatic Locking:** Previous months are locked to maintain historical data integrity.

### 🐖 Savings Goal Tracker
* **Target-Based Savings:** Create specific goals like "Emergency Fund" or "Dream Vacation."
* **Progress Analytics:** Monitor achievement percentages and track exactly how much more you need.
* **Internal Transfers:** Seamlessly move money from active wallets directly into your savings.

### 💳 Loan & Debt Command Center
* **Disbursement Tracking:** Record new loans that instantly reflect in your wallet balance.
* **Repayment Logic:** Link payments to specific `loan_id`s to automatically reduce outstanding debt.

### 📑 Centralized Transaction Ledger
* **Unified History:** Income, Expenses, Savings, and Loans are managed in one master list.
* **Smart Linking:** Every transaction is contextually linked to its respective Wallet, Budget, or Goal.

---

## 🛠 Technical Architecture

Savy utilizes a **Relational Ledger System** where the `wallet_transactions` table acts as the central pivot for the entire ecosystem:



| Column | Description |
| --- | --- |
| `wallet_id` | The source/destination of funds |
| `budget_id` | Optional: Link to a specific budget limit |
| `saving_id` | Optional: Link to a savings goal |
| `loan_id` | Optional: Link to an active loan/debt |
| `type` | Enum: `income`, `expense`, `budget_spending`, `saving`, `loan_repayment` |

---

## 💻 Installation Guide

### 1. Prerequisites
* **PHP >= 8.3** (Strictly required)
* **Node.js & NPM** (LTS)
* **Composer**
* **MySQL / PostgreSQL / SQLite**

### 2. Setup Steps
```bash
# Clone the repo
git clone [https://github.com/hendrypk/savy-id.git](https://github.com/hendrypk/savy-id.git)
cd savy-id

# Install PHP dependencies
composer install

# Setup Environment
cp .env.example .env
php artisan key:generate

# Run Migrations
php artisan migrate

# Install Frontend
npm install
npm run dev