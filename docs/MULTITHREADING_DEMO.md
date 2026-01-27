## SCD Lab – Multithreading + Synchronization Demo (PHP Version)

This document explains how the **Java multithreading concepts from your SCD slides** are implemented in your existing **PHP (XAMPP) WanderLust project** without touching any website routes or database logic.

All files live inside your project:
`C:\xampp\htdocs\scd\`

---

### 1. Files Added

- `scripts/threading/worker_log.php`  
- `scripts/threading/worker_notify.php`  
- `scripts/threading/thread_demo.php`  
- `run-thread-demo.bat` (root)  
- `scripts/threading/shared.log` (generated at runtime)  
- `scripts/threading/shared.lock` (generated at runtime)  
- `tests/TestThreadingDemo.php` (optional PHPUnit test)  

No existing PHP pages, DB files, or routes were modified.

---

### 2. Java Threads vs PHP “Processes”

Your slides use **Java threads** (`Thread`, `Runnable`, `start()`, `run()`).  
PHP (especially standard XAMPP CLI) does **not** have true Java-style threads, but we can simulate the same idea using **multiple PHP CLI processes**:

- In Java:  
  - `new ThreadDemo("Thread-1");`  
  - `t1.start(); t2.start();`
- In this demo (PHP):  
  - `thread_demo.php` uses `proc_open()` to start **two separate PHP CLI processes**:  
    - `worker_log.php` (simulated booking/audit logger)  
    - `worker_notify.php` (simulated email/notification sender)  
  - Both processes run **concurrently** and write to the same shared log file.

Conceptually:

| Java Term            | PHP Demo Equivalent                          |
|----------------------|----------------------------------------------|
| Thread class         | `worker_log.php`, `worker_notify.php`       |
| Runnable / run()     | `for` loop inside each worker file          |
| `start()`            | `proc_open()` in `thread_demo.php`          |
| `join()` / waiting   | `proc_get_status()` loop until finished     |
| Shared resource      | `shared.log` file                           |
| Monitor / lock       | `shared.lock` file + `flock(LOCK_EX)`       |

---

### 3. Thread Lifecycle Mapping

Java thread lifecycle stages from the slides:

1. **New** – Thread object created but not yet started  
2. **Runnable** – `start()` called, ready to run / running  
3. **Waiting / Timed Waiting** – waiting for something (sleep/join/lock)  
4. **Terminated (Dead)** – finished execution  

PHP demo mapping:

- **New**  
  - `thread_demo.php` builds the commands for two workers (`worker_log.php`, `worker_notify.php`) but has **not** yet started them.
- **Runnable**  
  - `proc_open()` is called for both workers. They begin executing and are “runnable” just like threads after `start()`.
- **Waiting / Timed Waiting**  
  - Each worker calls `usleep(...)` inside its loop → this is like `Thread.sleep()` (timed waiting).  
  - In `lock` mode, workers call `flock($lockFile, LOCK_EX)` and **wait** until they get the lock, just like `synchronized` waiting on a monitor.
- **Terminated**  
  - When each worker’s loop finishes, the PHP process ends.  
  - In `thread_demo.php`, the `proc_get_status()` loop sees that the process is no longer `running` and calls `proc_close()` (equivalent to a joined/terminated thread).

---

### 4. Synchronization Mapping (Java `synchronized` → PHP `flock`)

In Java you learned:

```java
synchronized (objectIdentifier) {
    // access shared resource
}
```

In this demo:

- Shared resource: `scripts/threading/shared.log`  
- Monitor / lock object: `scripts/threading/shared.lock`  
- PHP code (simplified from workers):

```php
$lockHandle = fopen('shared.lock', 'c');
if (flock($lockHandle, LOCK_EX)) {       // ENTER critical section (like synchronized)
    $logHandle = fopen('shared.log', 'ab');
    fwrite($logHandle, $line);           // write to shared resource
    fclose($logHandle);
    flock($lockHandle, LOCK_UN);         // EXIT critical section
}
fclose($lockHandle);
```

So:

| Java `synchronized(obj) { }` | PHP `flock($lockHandle, LOCK_EX) { }` |
|------------------------------|----------------------------------------|
| Only one thread at a time    | Only one PHP worker at a time         |
| Protects shared memory/file  | Protects `shared.log` writes          |

---

### 5. Demo Modes

There are **two modes**:

- **NOLOCK mode (`nolock`)**  
  - Workers write to `shared.log` **without** locking.  
  - This simulates the **“multithreading without synchronization”** example from slides.  
  - Lines from `worker_log` and `worker_notify` can be interleaved in any order.

- **LOCK mode (`lock`)**  
  - Workers write to `shared.log` **inside a `flock(LOCK_EX)` critical section**.  
  - This simulates Java’s **`synchronized`** examples (with monitors).  
  - Output is more consistent and safe — no overlapping writes at the OS level.

---

### 6. How to Run the Demo (Commands You Can Show in Viva)

#### From VS Code Terminal (inside project root `C:\xampp\htdocs\scd`)

1. **Without synchronization (NOLOCK)**  
```bash
php scripts/threading/thread_demo.php nolock
```

2. **With synchronization (LOCK)**  
```bash
php scripts/threading/thread_demo.php lock
```

Each command will:

- Clear `shared.log`  
- Start two worker processes (log + notify) “concurrently”  
- Wait for both to finish  
- Print a JUnit-style **summary**: total lines, lines per worker, log path  

Then open:

- `scripts/threading/shared.log` (latest run) in VS Code / Notepad

For viva, you can **first** run `nolock`, show mixed / interleaved lines, then run `lock` and show synchronized behaviour.

---

### 7. Easy Windows Shortcut

In project root there is:

- `run-thread-demo.bat`

Double-click it (or run from terminal):

```bat
run-thread-demo.bat
```

It will automatically:

1. Run **NOLOCK** mode  
2. Save log as `scripts/threading/shared_nolock.log`  
3. Run **LOCK** mode  
4. Save log as `scripts/threading/shared_lock.log`  
5. Show instructions to open the two logs

These two files give you a perfect **before/after** comparison for viva.

---

### 8. Optional PHPUnit Test

File: `tests/TestThreadingDemo.php`

What it checks (LOCK mode):

- `thread_demo.php lock` executes successfully  
- `shared.log` is created  
- `worker_log` wrote **10** lines  
- `worker_notify` wrote **10** lines  
- Total worker lines = **20**  

Run (if PHPUnit installed):

```bash
php vendor/bin/phpunit tests/TestThreadingDemo.php
```

This test is **DB-independent** and only uses the threading demo scripts.

---

### 9. How to Explain in Viva (Talking Points)

- **What is being simulated?**  
  - Two concurrent activities in the WanderLust system: logging and notifications.  
  - Each activity runs as a separate PHP CLI process (like two Java threads).

- **Where is the shared resource?**  
  - `scripts/threading/shared.log` file, shared by both workers.

- **Where is synchronization?**  
  - `scripts/threading/shared.lock` + `flock(LOCK_EX)` act as the monitor lock.  
  - Only one worker can hold the lock at a time → safe writes.

- **Where is the “thread runner”?**  
  - `scripts/threading/thread_demo.php` is just like `TestThread` / `ThreadClassDemo` from Java.

- **How is lifecycle shown?**  
  - New: commands prepared for workers.  
  - Runnable: `proc_open()` starts both workers.  
  - Waiting / Timed Waiting: `usleep()` in workers and `flock()` lock waiting.  
  - Terminated: processes end, `proc_get_status()` returns `running = false`.

- **Why this does not break the website?**  
  - All code is in `scripts/threading` and CLI-only.  
  - No changes to existing controllers, pages, or database logic.

---

### 10. Quick Recap (One-Liners)

- **Multithreading concept**: two PHP worker processes running at the same time, each doing a different task.  
- **Shared resource**: `shared.log` file.  
- **Race condition demo**: `nolock` mode (no file lock).  
- **Synchronization demo**: `lock` mode with `flock(shared.lock)`.  
- **Runner**: `thread_demo.php` (like Java `TestThread` / `ThreadClassDemo`).  
- **Easy command**: `run-thread-demo.bat` or `php scripts/threading/thread_demo.php lock`.  

This fully implements the **“Multithreading + Thread Synchronization”** concept from your Java SCD slides inside your existing PHP project, without touching your live website functionality.

