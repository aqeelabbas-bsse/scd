# Tests Kaise Run Karein - Step by Step Guide

## Step 1: PHPUnit Install Karein (Pehli Baar Sirf)

### Option A: Composer Se Install (Recommended)
```bash
cd c:\xampp\htdocs\scd
composer install
```

**Note:** Agar composer install nahi hai, to pehle Composer install karein:
- Download: https://getcomposer.org/download/
- Ya XAMPP ke saath already aata hai

### Option B: Agar Composer Nahi Hai
PHPUnit ko manually download karke install kar sakte hain, lekin composer recommended hai.

---

## Step 2: Tests Run Karein

### Method 1: FormValidator Tests Run Karein (JUnit Example Jaisa)
```bash
cd c:\xampp\htdocs\scd
vendor\bin\phpunit tests\TestFormValidator.php
```

**Expected Output:**
```
PHPUnit 9.5.x by Sebastian Bergmann

.........                                                        10 / 10 (100%)

Time: 00:00.123, Memory: 4.00 MB

OK (10 tests, 10 assertions)
```

---

### Method 2: TestRunner Se Run Karein (JUnit Example Jaisa)
```bash
cd c:\xampp\htdocs\scd
php tests\TestRunner.php
```

Ye JUnit example ke `java TestRunner` jaisa hai.

---

### Method 3: Sabhi Tests Ek Saath Run Karein
```bash
cd c:\xampp\htdocs\scd
vendor\bin\phpunit tests\
```

---

### Method 4: Composer Script Se (Aasan Tarika)
```bash
cd c:\xampp\htdocs\scd
composer test
```

Ya:
```bash
composer test-all      # Sab tests
composer test-booking  # Sirf BookingFacade tests
```

---

## Step 3: Agar Error Aaye To

### Error: "composer command not found"
**Solution:** Composer install karein ya XAMPP ke bin folder ko PATH mein add karein.

### Error: "PHPUnit not found"
**Solution:** `composer install` run karein.

### Error: "Class not found"
**Solution:** `composer install` run karein taaki autoloader setup ho.

---

## Quick Commands Summary

```bash
# 1. Install (pehli baar)
composer install

# 2. Run tests (kisi bhi method se)
vendor\bin\phpunit tests\TestFormValidator.php
php tests\TestRunner.php
composer test
```

---

## JUnit vs PHPUnit Comparison

| JUnit (Java) | PHPUnit (PHP) |
|-------------|---------------|
| `javac TestJunit.java` | `composer install` (pehli baar) |
| `java TestRunner` | `php tests\TestRunner.php` |
| `java org.junit.runner.JUnitCore TestJunit` | `vendor\bin\phpunit tests\TestFormValidator.php` |

---

## Expected Output Example

Jab tests successfully run honge, aapko dikhega:

```
=== JUnit Testing Concept - PHPUnit Implementation ===
Running tests for FormValidator class...

PHPUnit 9.5.x by Sebastian Bergmann

Runtime:       PHP 7.4.x
Configuration: tests/phpunit.xml

.........                                                        10 / 10 (100%)

Time: 00:00.123, Memory: 4.00 MB

OK (10 tests, 10 assertions)
```

Agar koi test fail hua, to woh bhi dikhega with details!
