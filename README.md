# 💰 Savy: Smart Personal Finance Manager

**Savy** is a modern, mobile-first personal finance application designed to give you total control over your wealth. Manage budgets, track savings goals, and monitor loans with a sleek, "Neo-Fintech" interface.

## 🚀 Core Features

### 🏦 Multi-Wallet Management
Manage all your funding sources in one unified view.
* **Consolidated Net Worth:** View your total balance across Cash, Bank accounts, and E-Wallets.
* **Real-time Balance:** Instant updates to your wallet balances as you log transactions.

### 📊 Smart Budgeting & Tracking
Take the guesswork out of your monthly spending.
* **Expense Allocation:** Set monthly limits for categories like Food, Transport, and Entertainment.
* **Visual Progress:** Dynamic progress bars that shift from Green to Red as you approach your limits.
* **Auto-Deduction:** Transactions linked to a `budget_id` automatically update your remaining budget.

### 🐖 Savings Goal Tracker (The Heart of Savy)
Build your future with goal-oriented savings, accessible right from the center of your nav bar.
* **Target-Based Savings:** Create specific goals such as "Emergency Fund" or "Dream Vacation."
* **Progress Analytics:** Monitor achievement percentages and see exactly how much more you need.
* **Internal Transfers:** Seamlessly move money from your active wallets into your savings.

### 💳 Loan & Debt Command Center
A dedicated space to manage what you owe and what is owed to you.
* **Disbursement Tracking:** Record new loans that instantly reflect in your wallet balance.
* **Repayment Logic:** Link payments to specific `loan_id`s to automatically reduce outstanding debt.
* **Debt Overview:** Clear visibility on remaining installments and payment history.

### 📑 Centralized Transaction Ledger
The "Single Source of Truth" for your financial journey.
* **Integrated History:** Income, Expenses, Savings, and Loan activities all in one master list.
* **Contextual Metadata:** Every transaction is smartly linked to its respective Wallet, Budget, or Goal.

### 🔒 Security & Privacy
* **Security Checkpoint:** Sensitive settings and high-value actions are protected by a mandatory password confirmation.
* **Privacy-First Design:** A consistent, secure mobile layout built to keep your financial data personal.

---

## 🛠 Technical Architecture

### Database Schema Structure
Savy utilizes a **Relational Ledger System** where the `wallet_transactions` table acts as the central pivot for the entire ecosystem:

| Column | Description |
| --- | --- |
| `wallet_id` | The source/destination of funds |
| `budget_id` | Optional: Link to a specific budget limit |
| `saving_id` | Optional: Link to a savings goal |
| `loan_id` | Optional: Link to an active loan/debt |
| `type` | Enum: `income`, `expense`, `budget_spending`, `saving`, `loan_repayment`, `loan_disbursement` |

---

## 🎨 UI/UX Philosophy
* **Savy Center-Action:** The Savings feature is placed in the center of the navigation for quick access.
* **One-Handed Operation:** Optimized for mobile users with all critical buttons within thumb's reach.
* **Modern Aesthetics:** Premium Glassmorphism effects and a clean Slate-based dark mode.