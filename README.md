# IC Learning Projects 🚀

এই রিপোতে আমার শেখার সময় তৈরি করা কিছু প্র্যাক্টিস প্রজেক্ট আছে। প্রতিটা প্রজেক্ট আলাদা আলাদা branch এ রাখা হয়েছে যাতে সব গুছিয়ে থাকে।

---

## 📁 Projects

### 1. User Registration System

**Branch:** [`01-user-registration-system`](https://github.com/akr4m/ic-learning-projects/tree/01-user-registration-system)

সিম্পল একটা user registration সিস্টেম বানিয়েছি যেখানে -

- User এর নাম, ইমেইল, পাসওয়ার্ড CSV/JSON ফাইলে সেভ হয়
- Input validation আর password hashing করা হয়েছে
- OOP concept ব্যবহার করে User class বানানো হয়েছে encapsulation সহ

---

### 2. Inventory Management CLI Tool

**Branch:** [`02-inventory-management-cli-tool`](https://github.com/akr4m/ic-learning-projects/tree/02-inventory-management-cli-tool)

Terminal based একটা inventory management tool। এইটায় -

- Products এর CRUD operations করা যায়
- Total stock value calculate করে আর low-stock alert দেয়
- File storage আর array manipulation শিখতে কাজে লাগছে

---

### 3. Responsive Blog Theme

**Branch:** [`Responsive-Blog-Theme`](https://github.com/akr4m/ic-learning-projects/tree/Responsive-Blog-Theme)

HTML/CSS দিয়ে তৈরি একটা responsive blog theme -

- Grid আর card layout ব্যবহার করা হয়েছে
- Typography আর form styling করা হয়েছে
- Dark mode toggle ও আছে!

---

### 4. Task Management App

**Branch:** [`Task-Management-App`](https://github.com/akr4m/ic-learning-projects/tree/Task-Management-App)

Laravel দিয়ে বানানো task management app -

- Tasks আর users এর জন্য migrations তৈরি করা হয়েছে
- Blade templates আর authentication আছে
- Storage ব্যবহার করে file attachment এর ব্যবস্থা

---

### 5. Recipe Sharing Platform

**Branch:** [`Recipe-Sharing-Platform`](https://github.com/akr4m/ic-learning-projects/tree/Recipe-Sharing-Platform)

Recipe শেয়ারিং এর জন্য একটা platform -

- Recipe আর Ingredients এর মধ্যে Eloquent relationships
- Query Builder দিয়ে search আর filter functionality
- Recipe এর জন্য image upload

---

### 6. Role-Based Blog

**Branch:** [`Role-Based-Blog`](https://github.com/akr4m/ic-learning-projects/tree/Role-Based-Blog)

Authorization শেখার জন্য একটা blog system -

- Authors পোস্ট লিখতে পারবে, Editors approve করবে
- Policies দিয়ে edit/delete permission handle করা হয়েছে
- Sanctum দিয়ে API endpoints protect করা

---

## 🔀 কিভাবে branch switch করবেন?

```bash
git checkout branch-name
```

যেমন User Registration প্রজেক্ট দেখতে চাইলে:

```bash
git checkout 01-user-registration-system
```
