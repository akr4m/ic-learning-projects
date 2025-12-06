# Inventory Management CLI Tool - ধাপে ধাপে গাইড

## প্রজেক্টের উদ্দেশ্য

এই প্রজেক্টে আমরা শিখব:

- PHP OOP (Class, Object, Encapsulation)
- CRUD অপারেশন (Create, Read, Update, Delete)
- Array Manipulation (filter, map, sort)
- File Handling (JSON read/write)
- CLI (Command Line Interface) অ্যাপ্লিকেশন তৈরি

---

## প্রয়োজনীয় উপকরণ

- PHP 8.0 বা তার উপরে
- Code Editor (VS Code / PHPStorm)
- Terminal / Command Prompt

---

## প্রজেক্ট স্ট্রাকচার

```
project02/
├── app.php                 # মূল CLI অ্যাপ্লিকেশন
├── data/                   # ডাটা স্টোরেজ ফোল্ডার
│   └── products.json       # (স্বয়ংক্রিয়ভাবে তৈরি হবে)
├── src/                    # সোর্স কোড ফোল্ডার
│   ├── Product.php         # Product ক্লাস
│   ├── FileStorage.php     # ফাইল স্টোরেজ ক্লাস
│   ├── Inventory.php       # ইনভেন্টরি ম্যানেজমেন্ট ক্লাস
│   └── CLI.php             # CLI হেল্পার ক্লাস
└── README.md               # এই ফাইল
```

---

## ধাপ ১: প্রজেক্ট ফোল্ডার তৈরি

Terminal খুলুন এবং নিচের কমান্ডগুলো চালান:

```bash
# প্রজেক্ট ফোল্ডারে যান
cd /path/to/your/project

# সাব-ফোল্ডার তৈরি করুন
mkdir src
mkdir data
```

**যা শিখলাম:**

- `mkdir` কমান্ড দিয়ে ফোল্ডার তৈরি
- প্রজেক্ট স্ট্রাকচার অর্গানাইজ করা

---

## ধাপ ২: Product Class তৈরি (src/Product.php)

**ফাইল তৈরি করুন:** `src/Product.php`

```php
<?php

/**
 * Product Class
 *
 * এই ক্লাসটি একটি প্রোডাক্টের ডাটা ম্যানেজ করে।
 * Encapsulation প্রিন্সিপল ব্যবহার করা হয়েছে।
 */
class Product
{
    /**
     * প্রোডাক্টের ইউনিক আইডি
     * private মানে শুধু এই ক্লাসের ভেতর থেকে অ্যাক্সেস করা যাবে
     */
    private string $id;

    /**
     * প্রোডাক্টের নাম
     */
    private string $name;

    /**
     * প্রোডাক্টের বিবরণ
     */
    private string $description;

    /**
     * প্রোডাক্টের দাম (টাকায়)
     */
    private float $price;

    /**
     * স্টকে থাকা পরিমাণ
     */
    private int $quantity;

    /**
     * প্রোডাক্টের ক্যাটাগরি
     */
    private string $category;

    /**
     * প্রোডাক্ট তৈরির সময়
     */
    private string $createdAt;

    /**
     * সর্বশেষ আপডেটের সময়
     */
    private string $updatedAt;

    /**
     * Low stock এর সীমা
     * const মানে এই ভ্যালু পরিবর্তন করা যাবে না
     */
    public const LOW_STOCK_THRESHOLD = 10;

    /**
     * Constructor - নতুন Product অবজেক্ট তৈরি করে
     *
     * @param string      $name        প্রোডাক্টের নাম
     * @param string      $description প্রোডাক্টের বিবরণ
     * @param float       $price       প্রোডাক্টের দাম
     * @param int         $quantity    স্টকে থাকা পরিমাণ
     * @param string      $category    প্রোডাক্টের ক্যাটাগরি
     * @param string|null $id          প্রোডাক্ট আইডি
     */
    public function __construct(
        string $name,
        string $description,
        float $price,
        int $quantity,
        string $category = 'General',
        ?string $id = null
    ) {
        // আইডি না দিলে অটো জেনারেট করো
        $this->id = $id ?? $this->generateUniqueId();

        // প্রপার্টিগুলো সেট করো
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->category = $category;

        // টাইমস্ট্যাম্প সেট করো
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    /**
     * ইউনিক আইডি জেনারেট করে
     */
    private function generateUniqueId(): string
    {
        // bin2hex(random_bytes(8)) -> ১৬ অক্ষরের র‍্যান্ডম স্ট্রিং
        return 'prod_' . bin2hex(random_bytes(8));
    }

    // ========================================
    // GETTER মেথডস (ডাটা পড়ার জন্য)
    // ========================================

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    // ========================================
    // SETTER মেথডস (ডাটা পরিবর্তনের জন্য)
    // ========================================

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->updateTimestamp();
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->updateTimestamp();
    }

    /**
     * দাম সেট করে
     *
     * @throws InvalidArgumentException দাম নেগেটিভ হলে
     */
    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException('দাম নেগেটিভ হতে পারবে না।');
        }
        $this->price = $price;
        $this->updateTimestamp();
    }

    /**
     * পরিমাণ সেট করে
     *
     * @throws InvalidArgumentException পরিমাণ নেগেটিভ হলে
     */
    public function setQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('পরিমাণ নেগেটিভ হতে পারবে না।');
        }
        $this->quantity = $quantity;
        $this->updateTimestamp();
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->updateTimestamp();
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // ========================================
    // ব্যবসায়িক লজিক মেথডস
    // ========================================

    /**
     * আপডেট টাইমস্ট্যাম্প রিফ্রেশ করে
     */
    private function updateTimestamp(): void
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    /**
     * মোট মূল্য = দাম x পরিমাণ
     */
    public function getTotalValue(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * স্টক কম কিনা চেক করে
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= self::LOW_STOCK_THRESHOLD;
    }

    /**
     * স্টক শেষ কিনা চেক করে
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity === 0;
    }

    /**
     * স্টকে প্রোডাক্ট যোগ করে
     *
     * @throws InvalidArgumentException পরিমাণ ০ বা নেগেটিভ হলে
     */
    public function addStock(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('যোগ করার পরিমাণ পজিটিভ হতে হবে।');
        }
        $this->quantity += $amount;
        $this->updateTimestamp();
    }

    /**
     * স্টক থেকে প্রোডাক্ট কমায়
     *
     * @throws InvalidArgumentException পরিমাণ বেশি বা নেগেটিভ হলে
     */
    public function removeStock(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('কমানোর পরিমাণ পজিটিভ হতে হবে।');
        }
        if ($amount > $this->quantity) {
            throw new InvalidArgumentException('স্টকে যথেষ্ট প্রোডাক্ট নেই।');
        }
        $this->quantity -= $amount;
        $this->updateTimestamp();
    }

    /**
     * Product অবজেক্টকে Array তে কনভার্ট করে
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'quantity'    => $this->quantity,
            'category'    => $this->category,
            'created_at'  => $this->createdAt,
            'updated_at'  => $this->updatedAt
        ];
    }

    /**
     * Array থেকে Product অবজেক্ট তৈরি করে
     * static মানে অবজেক্ট ছাড়াই কল করা যায়: Product::fromArray($data)
     */
    public static function fromArray(array $data): Product
    {
        $product = new self(
            $data['name'],
            $data['description'],
            (float) $data['price'],
            (int) $data['quantity'],
            $data['category'] ?? 'General',
            $data['id']
        );

        if (isset($data['created_at'])) {
            $product->setCreatedAt($data['created_at']);
        }
        if (isset($data['updated_at'])) {
            $product->setUpdatedAt($data['updated_at']);
        }

        return $product;
    }
}
```

**যা শিখলাম:**

- `class` দিয়ে ক্লাস তৈরি
- `private` properties (Encapsulation)
- `const` দিয়ে constant তৈরি
- Constructor মেথড (`__construct`)
- Getter ও Setter মেথড
- Type declarations (`string`, `float`, `int`, `bool`, `?string`)
- `static` method
- Exception throwing

---

## ধাপ ৩: FileStorage Class তৈরি (src/FileStorage.php)

**ফাইল তৈরি করুন:** `src/FileStorage.php`

```php
<?php

/**
 * FileStorage Class
 *
 * JSON ফাইলে প্রোডাক্ট ডাটা সেভ ও লোড করার জন্য।
 */

require_once __DIR__ . '/Product.php';

class FileStorage
{
    /**
     * ডাটা ফোল্ডারের পাথ
     */
    private string $dataPath;

    /**
     * JSON ফাইলের নাম
     */
    private const JSON_FILENAME = 'products.json';

    /**
     * Constructor
     */
    public function __construct(string $dataPath)
    {
        // শেষের / বাদ দাও
        $this->dataPath = rtrim($dataPath, '/');

        // ফোল্ডার আছে কিনা নিশ্চিত করো
        $this->ensureDirectoryExists();
    }

    /**
     * ডাটা ফোল্ডার তৈরি করে (না থাকলে)
     */
    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->dataPath)) {
            // 0755 = owner: rwx, group: r-x, others: r-x
            mkdir($this->dataPath, 0755, true);
        }
    }

    /**
     * JSON ফাইলের পূর্ণ পাথ রিটার্ন করে
     */
    private function getJsonPath(): string
    {
        return $this->dataPath . '/' . self::JSON_FILENAME;
    }

    /**
     * সব প্রোডাক্ট JSON ফাইলে সেভ করে
     *
     * @param array $products Product অবজেক্টের অ্যারে
     * @return bool সফল হলে true
     */
    public function saveAll(array $products): bool
    {
        // array_map: প্রতিটি Product কে array তে কনভার্ট করো
        $productsArray = array_map(function (Product $product) {
            return $product->toArray();
        }, $products);

        // JSON এ কনভার্ট করো
        // JSON_PRETTY_PRINT: সুন্দর ফরম্যাটে
        // JSON_UNESCAPED_UNICODE: বাংলা ঠিকভাবে দেখাবে
        $json = json_encode(
            $productsArray,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        if ($json === false) {
            return false;
        }

        // ফাইলে লেখো
        // LOCK_EX: একসাথে একাধিক write প্রতিরোধ
        $result = file_put_contents($this->getJsonPath(), $json, LOCK_EX);

        return $result !== false;
    }

    /**
     * JSON ফাইল থেকে সব প্রোডাক্ট লোড করে
     *
     * @return array Product অবজেক্টের অ্যারে
     */
    public function loadAll(): array
    {
        $jsonPath = $this->getJsonPath();
        $products = [];

        // ফাইল না থাকলে খালি অ্যারে
        if (!file_exists($jsonPath)) {
            return $products;
        }

        // ফাইল পড়ো
        $json = file_get_contents($jsonPath);

        if ($json === false) {
            return $products;
        }

        // JSON থেকে PHP অ্যারেতে কনভার্ট করো
        // true = associative array হিসেবে
        $productsArray = json_decode($json, true);

        if (!is_array($productsArray)) {
            return $products;
        }

        // প্রতিটি ডাটা থেকে Product অবজেক্ট তৈরি করো
        foreach ($productsArray as $productData) {
            if (is_array($productData) && isset($productData['id'])) {
                $products[] = Product::fromArray($productData);
            }
        }

        return $products;
    }

    /**
     * আইডি দিয়ে প্রোডাক্ট খোঁজে
     */
    public function findById(string $productId): ?Product
    {
        $products = $this->loadAll();

        foreach ($products as $product) {
            if ($product->getId() === $productId) {
                return $product;
            }
        }

        return null;
    }

    /**
     * নাম দিয়ে প্রোডাক্ট খোঁজে
     */
    public function findByName(string $name): array
    {
        $products = $this->loadAll();
        $searchTerm = strtolower($name);

        // array_filter: শর্ত মেলে এমন এলিমেন্ট রাখে
        return array_filter($products, function (Product $product) use ($searchTerm) {
            // strpos: স্ট্রিং এর মধ্যে খোঁজে
            return strpos(strtolower($product->getName()), $searchTerm) !== false;
        });
    }
}
```

**যা শিখলাম:**

- `json_encode()` - PHP থেকে JSON এ কনভার্ট
- `json_decode()` - JSON থেকে PHP তে কনভার্ট
- `file_get_contents()` - ফাইল পড়া
- `file_put_contents()` - ফাইলে লেখা
- `array_map()` - অ্যারের প্রতিটি এলিমেন্ট ট্রান্সফর্ম করা
- `array_filter()` - অ্যারে ফিল্টার করা
- Anonymous functions (Closures)
- `use` keyword দিয়ে বাইরের ভেরিয়েবল অ্যাক্সেস

---

## ধাপ ৪: Inventory Class তৈরি (src/Inventory.php)

**ফাইল তৈরি করুন:** `src/Inventory.php`

```php
<?php

/**
 * Inventory Class
 *
 * ইনভেন্টরি ম্যানেজমেন্টের মূল ক্লাস।
 * CRUD অপারেশন ও রিপোর্টিং হ্যান্ডল করে।
 */

require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/FileStorage.php';

class Inventory
{
    /**
     * FileStorage ইনস্ট্যান্স
     */
    private FileStorage $storage;

    /**
     * মেমরিতে রাখা প্রোডাক্টের অ্যারে
     */
    private array $products = [];

    /**
     * Constructor
     */
    public function __construct(string $dataPath)
    {
        $this->storage = new FileStorage($dataPath);
        $this->loadProducts();
    }

    /**
     * ফাইল থেকে প্রোডাক্ট লোড করে
     */
    private function loadProducts(): void
    {
        $this->products = $this->storage->loadAll();
    }

    /**
     * প্রোডাক্ট ফাইলে সেভ করে
     */
    private function saveProducts(): bool
    {
        return $this->storage->saveAll($this->products);
    }

    // ========================================
    // CREATE - নতুন প্রোডাক্ট তৈরি
    // ========================================

    public function createProduct(
        string $name,
        string $description,
        float $price,
        int $quantity,
        string $category = 'General'
    ): ?Product {
        $product = new Product($name, $description, $price, $quantity, $category);

        // অ্যারেতে যোগ করো
        $this->products[] = $product;

        // ফাইলে সেভ করো
        if ($this->saveProducts()) {
            return $product;
        }

        // সেভ ব্যর্থ হলে বাদ দাও
        array_pop($this->products);
        return null;
    }

    // ========================================
    // READ - প্রোডাক্ট পড়া
    // ========================================

    public function getProduct(string $productId): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $productId) {
                return $product;
            }
        }
        return null;
    }

    public function getAllProducts(): array
    {
        return $this->products;
    }

    // ========================================
    // UPDATE - প্রোডাক্ট আপডেট
    // ========================================

    public function updateProduct(
        string $productId,
        ?string $name = null,
        ?string $description = null,
        ?float $price = null,
        ?int $quantity = null,
        ?string $category = null
    ): bool {
        $product = $this->getProduct($productId);

        if ($product === null) {
            return false;
        }

        // শুধু যেগুলো দেওয়া হয়েছে সেগুলো আপডেট করো
        if ($name !== null) $product->setName($name);
        if ($description !== null) $product->setDescription($description);
        if ($price !== null) $product->setPrice($price);
        if ($quantity !== null) $product->setQuantity($quantity);
        if ($category !== null) $product->setCategory($category);

        return $this->saveProducts();
    }

    // ========================================
    // DELETE - প্রোডাক্ট ডিলিট
    // ========================================

    public function deleteProduct(string $productId): bool
    {
        $initialCount = count($this->products);

        // ফিল্টার করে ওই প্রোডাক্ট বাদ দাও
        $this->products = array_filter(
            $this->products,
            function (Product $product) use ($productId) {
                return $product->getId() !== $productId;
            }
        );

        // ইনডেক্স রিসেট করো
        $this->products = array_values($this->products);

        if (count($this->products) === $initialCount) {
            return false; // কিছু ডিলিট হয়নি
        }

        return $this->saveProducts();
    }

    // ========================================
    // সার্চ অপারেশনস
    // ========================================

    public function searchByName(string $name): array
    {
        $searchTerm = strtolower(trim($name));

        return array_filter(
            $this->products,
            function (Product $product) use ($searchTerm) {
                return strpos(strtolower($product->getName()), $searchTerm) !== false;
            }
        );
    }

    public function searchByCategory(string $category): array
    {
        $searchTerm = strtolower(trim($category));

        return array_filter(
            $this->products,
            function (Product $product) use ($searchTerm) {
                return strtolower($product->getCategory()) === $searchTerm;
            }
        );
    }

    public function getAllCategories(): array
    {
        $categories = array_map(function (Product $product) {
            return $product->getCategory();
        }, $this->products);

        // array_unique: ডুপ্লিকেট বাদ দেয়
        return array_unique($categories);
    }

    // ========================================
    // স্ট্যাটিস্টিক্স ও রিপোর্ট
    // ========================================

    /**
     * ইনভেন্টরির মোট মূল্য
     */
    public function getTotalInventoryValue(): float
    {
        $total = 0.0;

        foreach ($this->products as $product) {
            $total += $product->getTotalValue();
        }

        return $total;
    }

    /**
     * মোট প্রোডাক্ট সংখ্যা
     */
    public function getTotalProductCount(): int
    {
        return count($this->products);
    }

    /**
     * মোট স্টক
     */
    public function getTotalStock(): int
    {
        $total = 0;

        foreach ($this->products as $product) {
            $total += $product->getQuantity();
        }

        return $total;
    }

    // ========================================
    // Low Stock Alerts
    // ========================================

    /**
     * Low stock প্রোডাক্ট রিটার্ন করে
     */
    public function getLowStockProducts(): array
    {
        return array_filter(
            $this->products,
            function (Product $product) {
                return $product->isLowStock();
            }
        );
    }

    /**
     * Out of stock প্রোডাক্ট রিটার্ন করে
     */
    public function getOutOfStockProducts(): array
    {
        return array_filter(
            $this->products,
            function (Product $product) {
                return $product->isOutOfStock();
            }
        );
    }

    /**
     * Low stock alert আছে কিনা
     */
    public function hasLowStockAlert(): bool
    {
        return count($this->getLowStockProducts()) > 0;
    }

    // ========================================
    // স্টক অপারেশনস
    // ========================================

    public function addStock(string $productId, int $amount): bool
    {
        $product = $this->getProduct($productId);

        if ($product === null) {
            return false;
        }

        try {
            $product->addStock($amount);
            return $this->saveProducts();
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    public function removeStock(string $productId, int $amount): bool
    {
        $product = $this->getProduct($productId);

        if ($product === null) {
            return false;
        }

        try {
            $product->removeStock($amount);
            return $this->saveProducts();
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    // ========================================
    // সর্টিং
    // ========================================

    /**
     * নাম অনুযায়ী সর্ট
     */
    public function getProductsSortedByName(bool $ascending = true): array
    {
        $products = $this->products;

        // usort: কাস্টম comparison ফাংশন দিয়ে সর্ট
        usort($products, function (Product $a, Product $b) use ($ascending) {
            $result = strcmp($a->getName(), $b->getName());
            return $ascending ? $result : -$result;
        });

        return $products;
    }

    /**
     * দাম অনুযায়ী সর্ট
     */
    public function getProductsSortedByPrice(bool $ascending = true): array
    {
        $products = $this->products;

        usort($products, function (Product $a, Product $b) use ($ascending) {
            // <=> spaceship operator: -1, 0, বা 1 রিটার্ন করে
            $result = $a->getPrice() <=> $b->getPrice();
            return $ascending ? $result : -$result;
        });

        return $products;
    }

    /**
     * স্টক অনুযায়ী সর্ট
     */
    public function getProductsSortedByQuantity(bool $ascending = true): array
    {
        $products = $this->products;

        usort($products, function (Product $a, Product $b) use ($ascending) {
            $result = $a->getQuantity() <=> $b->getQuantity();
            return $ascending ? $result : -$result;
        });

        return $products;
    }

    /**
     * ইনভেন্টরি সামারি
     */
    public function getSummary(): array
    {
        return [
            'total_products'     => $this->getTotalProductCount(),
            'total_stock'        => $this->getTotalStock(),
            'total_value'        => $this->getTotalInventoryValue(),
            'low_stock_count'    => count($this->getLowStockProducts()),
            'out_of_stock_count' => count($this->getOutOfStockProducts()),
            'categories'         => count($this->getAllCategories())
        ];
    }
}
```

**যা শিখলাম:**

- CRUD অপারেশন (Create, Read, Update, Delete)
- `array_filter()` দিয়ে ফিল্টারিং
- `array_map()` দিয়ে ট্রান্সফর্মেশন
- `array_unique()` দিয়ে ডুপ্লিকেট বাদ দেওয়া
- `array_values()` দিয়ে ইনডেক্স রিসেট
- `usort()` দিয়ে কাস্টম সর্টিং
- Spaceship operator (`<=>`)
- Exception handling (`try-catch`)

---

## ধাপ ৫: CLI Helper Class তৈরি (src/CLI.php)

**ফাইল তৈরি করুন:** `src/CLI.php`

```php
<?php

/**
 * CLI Class
 *
 * Command Line Interface এর জন্য হেল্পার ক্লাস।
 * ইনপুট/আউটপুট ও ফরম্যাটিং হ্যান্ডল করে।
 */
class CLI
{
    // ANSI কালার কোডস
    public const RESET = "\033[0m";
    public const BOLD = "\033[1m";
    public const RED = "\033[31m";
    public const GREEN = "\033[32m";
    public const YELLOW = "\033[33m";
    public const CYAN = "\033[36m";

    /**
     * নতুন লাইনসহ প্রিন্ট করে
     */
    public static function println(string $text = ''): void
    {
        echo $text . PHP_EOL;
    }

    /**
     * সফলতার মেসেজ (সবুজ)
     */
    public static function success(string $text): void
    {
        self::println(self::GREEN . "✓ " . $text . self::RESET);
    }

    /**
     * এরর মেসেজ (লাল)
     */
    public static function error(string $text): void
    {
        self::println(self::RED . "✗ " . $text . self::RESET);
    }

    /**
     * সতর্কতা মেসেজ (হলুদ)
     */
    public static function warning(string $text): void
    {
        self::println(self::YELLOW . "⚠ " . $text . self::RESET);
    }

    /**
     * তথ্য মেসেজ (সায়ান)
     */
    public static function info(string $text): void
    {
        self::println(self::CYAN . "ℹ " . $text . self::RESET);
    }

    /**
     * হেডার প্রিন্ট করে
     */
    public static function header(string $text): void
    {
        $length = strlen($text) + 4;
        $line = str_repeat('═', $length);

        self::println();
        self::println(self::BOLD . self::CYAN . "╔" . $line . "╗" . self::RESET);
        self::println(self::BOLD . self::CYAN . "║  " . $text . "  ║" . self::RESET);
        self::println(self::BOLD . self::CYAN . "╚" . $line . "╝" . self::RESET);
        self::println();
    }

    /**
     * ইউজার থেকে ইনপুট নেয়
     */
    public static function input(string $prompt = ''): string
    {
        if ($prompt !== '') {
            echo self::BOLD . $prompt . self::RESET;
        }

        // STDIN থেকে পড়ো
        $input = trim(fgets(STDIN));
        return $input;
    }

    /**
     * সংখ্যা ইনপুট নেয়
     */
    public static function inputInt(string $prompt = ''): ?int
    {
        $input = self::input($prompt);
        return is_numeric($input) ? (int) $input : null;
    }

    /**
     * ফ্লোট ইনপুট নেয়
     */
    public static function inputFloat(string $prompt = ''): ?float
    {
        $input = self::input($prompt);
        return is_numeric($input) ? (float) $input : null;
    }

    /**
     * Yes/No কনফার্মেশন
     */
    public static function confirm(string $prompt): bool
    {
        $input = self::input($prompt . " (y/n): ");
        return strtolower($input) === 'y' || strtolower($input) === 'yes';
    }

    /**
     * মেনু দেখায় ও সিলেকশন নেয়
     */
    public static function menu(array $options, string $prompt = "আপনার পছন্দ: "): ?int
    {
        foreach ($options as $index => $option) {
            self::println(self::CYAN . "  [" . ($index + 1) . "] " . self::RESET . $option);
        }

        self::println();
        $choice = self::inputInt($prompt);

        if ($choice !== null && $choice >= 1 && $choice <= count($options)) {
            return $choice - 1; // 0-based index
        }

        return null;
    }

    /**
     * টেবিল প্রিন্ট করে
     */
    public static function table(array $headers, array $rows): void
    {
        // কলামের প্রস্থ বের করো
        $widths = [];

        foreach ($headers as $index => $header) {
            $widths[$index] = strlen($header);
        }

        foreach ($rows as $row) {
            foreach ($row as $index => $cell) {
                $cellLength = strlen((string) $cell);
                if (!isset($widths[$index]) || $cellLength > $widths[$index]) {
                    $widths[$index] = $cellLength;
                }
            }
        }

        // টেবিল লাইন
        $line = '+';
        foreach ($widths as $width) {
            $line .= str_repeat('-', $width + 2) . '+';
        }

        // হেডার প্রিন্ট
        self::println($line);
        echo '|';
        foreach ($headers as $index => $header) {
            echo self::BOLD . ' ' . str_pad($header, $widths[$index]) . ' ' . self::RESET . '|';
        }
        self::println();
        self::println($line);

        // রো প্রিন্ট
        foreach ($rows as $row) {
            echo '|';
            foreach ($row as $index => $cell) {
                echo ' ' . str_pad((string) $cell, $widths[$index]) . ' |';
            }
            self::println();
        }

        self::println($line);
    }

    /**
     * Key-Value প্রিন্ট করে
     */
    public static function keyValue(array $data): void
    {
        $maxKeyLength = max(array_map('strlen', array_keys($data)));

        foreach ($data as $key => $value) {
            self::println(
                self::CYAN . str_pad($key, $maxKeyLength) . self::RESET .
                " : " .
                self::BOLD . $value . self::RESET
            );
        }
    }

    /**
     * টাকা ফরম্যাট
     */
    public static function formatMoney(float $amount): string
    {
        return '৳' . number_format($amount, 2);
    }

    /**
     * সংখ্যা ফরম্যাট
     */
    public static function formatNumber(int $number): string
    {
        return number_format($number);
    }

    /**
     * তারিখ ফরম্যাট
     */
    public static function formatDate(string $datetime): string
    {
        return date('d M Y, h:i A', strtotime($datetime));
    }

    /**
     * Enter চাপতে বলে
     */
    public static function pressEnter(): void
    {
        self::input(PHP_EOL . "চালিয়ে যেতে Enter চাপুন...");
    }

    /**
     * স্ক্রিন ক্লিয়ার
     */
    public static function clear(): void
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }

    /**
     * অপেক্ষা করে
     */
    public static function wait(int $seconds = 1): void
    {
        sleep($seconds);
    }

    /**
     * খালি লাইন
     */
    public static function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            self::println();
        }
    }

    /**
     * সাবহেডার
     */
    public static function subHeader(string $text): void
    {
        self::println();
        self::println(self::BOLD . self::YELLOW . "▶ " . $text . self::RESET);
        self::println(str_repeat('-', strlen($text) + 2));
    }
}
```

**যা শিখলাম:**

- ANSI color codes দিয়ে টার্মিনালে কালার
- `STDIN` থেকে ইনপুট নেওয়া (`fgets`)
- `static` methods
- `str_pad()` দিয়ে প্যাডিং
- `str_repeat()` দিয়ে স্ট্রিং রিপিট
- `number_format()` দিয়ে সংখ্যা ফরম্যাট
- `date()` ও `strtotime()` দিয়ে তারিখ ফরম্যাট
- `system()` দিয়ে shell command চালানো

---

## ধাপ ৬: Main CLI Application তৈরি (app.php)

**ফাইল তৈরি করুন:** `app.php` (প্রজেক্টের রুটে)

```php
#!/usr/bin/env php
<?php

/**
 * Inventory Management CLI Application
 *
 * মূল CLI অ্যাপ্লিকেশন
 */

// ক্লাস ফাইলগুলো লোড করো
require_once __DIR__ . '/src/Product.php';
require_once __DIR__ . '/src/FileStorage.php';
require_once __DIR__ . '/src/Inventory.php';
require_once __DIR__ . '/src/CLI.php';

/**
 * InventoryApp Class
 */
class InventoryApp
{
    private Inventory $inventory;
    private bool $running = true;

    public function __construct()
    {
        $this->inventory = new Inventory(__DIR__ . '/data');
    }

    /**
     * অ্যাপ চালু করে
     */
    public function run(): void
    {
        $this->showWelcome();
        $this->checkLowStockAlert();

        // মেইন লুপ - যতক্ষণ running = true
        while ($this->running) {
            $this->showMainMenu();
        }

        CLI::newLine();
        CLI::success("ধন্যবাদ! আবার আসবেন।");
    }

    private function showWelcome(): void
    {
        CLI::clear();
        CLI::header("ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম");
        CLI::info("Version 1.0.0 | by AkrAm");
    }

    private function checkLowStockAlert(): void
    {
        if ($this->inventory->hasLowStockAlert()) {
            $count = count($this->inventory->getLowStockProducts());
            CLI::warning("সতর্কতা: {$count} টি প্রোডাক্টের স্টক কম!");
            CLI::newLine();
        }
    }

    private function showMainMenu(): void
    {
        CLI::subHeader("মেইন মেনু");

        $options = [
            "প্রোডাক্ট যোগ করুন",
            "সব প্রোডাক্ট দেখুন",
            "প্রোডাক্ট খুঁজুন",
            "প্রোডাক্ট আপডেট করুন",
            "প্রোডাক্ট ডিলিট করুন",
            "স্টক যোগ/কমান",
            "Low Stock Alerts",
            "ইনভেন্টরি সামারি",
            "প্রস্থান করুন"
        ];

        $choice = CLI::menu($options);

        switch ($choice) {
            case 0: $this->addProduct(); break;
            case 1: $this->listProducts(); break;
            case 2: $this->searchProduct(); break;
            case 3: $this->updateProduct(); break;
            case 4: $this->deleteProduct(); break;
            case 5: $this->manageStock(); break;
            case 6: $this->showLowStockAlerts(); break;
            case 7: $this->showSummary(); break;
            case 8: $this->running = false; break;
            default:
                CLI::error("সঠিক অপশন নির্বাচন করুন।");
                CLI::wait(1);
        }
    }

    // বাকি মেথডগুলো আগের app.php থেকে নিন...

    private function addProduct(): void
    {
        CLI::subHeader("নতুন প্রোডাক্ট যোগ করুন");

        $name = CLI::input("প্রোডাক্টের নাম: ");
        if (empty($name)) {
            CLI::error("নাম খালি রাখা যাবে না।");
            CLI::pressEnter();
            return;
        }

        $description = CLI::input("বিবরণ: ");

        $price = CLI::inputFloat("দাম (টাকায়): ");
        if ($price === null || $price < 0) {
            CLI::error("সঠিক দাম দিন।");
            CLI::pressEnter();
            return;
        }

        $quantity = CLI::inputInt("পরিমাণ: ");
        if ($quantity === null || $quantity < 0) {
            CLI::error("সঠিক পরিমাণ দিন।");
            CLI::pressEnter();
            return;
        }

        $category = CLI::input("ক্যাটাগরি (খালি রাখলে General): ");
        if (empty($category)) $category = 'General';

        $product = $this->inventory->createProduct(
            $name, $description, $price, $quantity, $category
        );

        if ($product !== null) {
            CLI::success("প্রোডাক্ট যোগ হয়েছে! আইডি: " . $product->getId());
        } else {
            CLI::error("প্রোডাক্ট যোগ করতে সমস্যা হয়েছে।");
        }

        CLI::pressEnter();
    }

    private function listProducts(): void
    {
        CLI::subHeader("সব প্রোডাক্ট");

        $products = $this->inventory->getAllProducts();

        if (empty($products)) {
            CLI::warning("কোনো প্রোডাক্ট নেই।");
            CLI::pressEnter();
            return;
        }

        $headers = ['আইডি', 'নাম', 'দাম', 'স্টক', 'মোট মূল্য'];
        $rows = [];

        foreach ($products as $product) {
            $rows[] = [
                substr($product->getId(), 0, 12) . '...',
                substr($product->getName(), 0, 20),
                CLI::formatMoney($product->getPrice()),
                $product->getQuantity() . ($product->isLowStock() ? ' ⚠' : ''),
                CLI::formatMoney($product->getTotalValue())
            ];
        }

        CLI::table($headers, $rows);
        CLI::info("মোট: " . count($products) . " টি প্রোডাক্ট");
        CLI::pressEnter();
    }

    private function searchProduct(): void
    {
        CLI::subHeader("প্রোডাক্ট খুঁজুন");

        $name = CLI::input("প্রোডাক্টের নাম: ");
        $products = $this->inventory->searchByName($name);

        if (empty($products)) {
            CLI::warning("কোনো প্রোডাক্ট পাওয়া যায়নি।");
        } else {
            CLI::success("পাওয়া গেছে: " . count($products) . " টি");
            foreach ($products as $product) {
                CLI::println("  • " . $product->getName() . " - " .
                    CLI::formatMoney($product->getPrice()));
            }
        }

        CLI::pressEnter();
    }

    private function updateProduct(): void
    {
        CLI::subHeader("প্রোডাক্ট আপডেট");

        $id = CLI::input("প্রোডাক্ট আইডি: ");
        $product = $this->inventory->getProduct($id);

        if (!$product) {
            CLI::error("প্রোডাক্ট পাওয়া যায়নি।");
            CLI::pressEnter();
            return;
        }

        CLI::info("বর্তমান: " . $product->getName() . " - " .
            CLI::formatMoney($product->getPrice()));

        $name = CLI::input("নতুন নাম (খালি = আগেরটা): ");
        $priceInput = CLI::input("নতুন দাম (খালি = আগেরটা): ");

        $this->inventory->updateProduct(
            $id,
            !empty($name) ? $name : null,
            null,
            is_numeric($priceInput) ? (float)$priceInput : null
        );

        CLI::success("আপডেট হয়েছে!");
        CLI::pressEnter();
    }

    private function deleteProduct(): void
    {
        CLI::subHeader("প্রোডাক্ট ডিলিট");

        $id = CLI::input("প্রোডাক্ট আইডি: ");

        if (CLI::confirm("আপনি কি নিশ্চিত?")) {
            if ($this->inventory->deleteProduct($id)) {
                CLI::success("ডিলিট হয়েছে!");
            } else {
                CLI::error("ডিলিট করতে সমস্যা হয়েছে।");
            }
        }

        CLI::pressEnter();
    }

    private function manageStock(): void
    {
        CLI::subHeader("স্টক ম্যানেজমেন্ট");

        $id = CLI::input("প্রোডাক্ট আইডি: ");
        $product = $this->inventory->getProduct($id);

        if (!$product) {
            CLI::error("প্রোডাক্ট পাওয়া যায়নি।");
            CLI::pressEnter();
            return;
        }

        CLI::info("বর্তমান স্টক: " . $product->getQuantity());

        $options = ["স্টক যোগ", "স্টক কমান"];
        $choice = CLI::menu($options);
        $amount = CLI::inputInt("পরিমাণ: ");

        if ($amount === null || $amount <= 0) {
            CLI::error("সঠিক পরিমাণ দিন।");
            CLI::pressEnter();
            return;
        }

        $success = $choice === 0
            ? $this->inventory->addStock($id, $amount)
            : $this->inventory->removeStock($id, $amount);

        if ($success) {
            CLI::success("স্টক আপডেট হয়েছে!");
        } else {
            CLI::error("সমস্যা হয়েছে।");
        }

        CLI::pressEnter();
    }

    private function showLowStockAlerts(): void
    {
        CLI::subHeader("Low Stock Alerts");

        $lowStock = $this->inventory->getLowStockProducts();

        if (empty($lowStock)) {
            CLI::success("সব ঠিক আছে!");
        } else {
            CLI::warning(count($lowStock) . " টি প্রোডাক্টের স্টক কম:");
            foreach ($lowStock as $product) {
                CLI::println("  • " . $product->getName() .
                    " - স্টক: " . $product->getQuantity());
            }
        }

        CLI::pressEnter();
    }

    private function showSummary(): void
    {
        CLI::subHeader("ইনভেন্টরি সামারি");

        $summary = $this->inventory->getSummary();

        CLI::keyValue([
            'মোট প্রোডাক্ট'   => $summary['total_products'] . ' টি',
            'মোট স্টক'       => $summary['total_stock'] . ' টি',
            'মোট মূল্য'      => CLI::formatMoney($summary['total_value']),
            'Low Stock'    => $summary['low_stock_count'] . ' টি'
        ]);

        CLI::pressEnter();
    }
}

// অ্যাপ চালাও
$app = new InventoryApp();
$app->run();
```

**যা শিখলাম:**

- CLI অ্যাপ্লিকেশন স্ট্রাকচার
- `while` লুপ দিয়ে মেইন লুপ
- `switch-case` দিয়ে মেনু হ্যান্ডলিং
- Method organization

---

## ধাপ ৭: প্রজেক্ট চালানো

### অ্যাপ চালু করুন

```bash
cd /path/to/project02
php app.php
```

### টেস্ট করুন

1. **প্রোডাক্ট যোগ করুন** - কয়েকটি প্রোডাক্ট যোগ করুন
2. **সব প্রোডাক্ট দেখুন** - তালিকা দেখুন
3. **প্রোডাক্ট খুঁজুন** - নাম দিয়ে খুঁজুন
4. **স্টক কমান** - ১০ এর নিচে নিয়ে যান
5. **Low Stock Alerts দেখুন** - সতর্কতা দেখুন
6. **সামারি দেখুন** - মোট মূল্য দেখুন

### সেভ করা ডাটা দেখুন

```bash
cat data/products.json
```

---

## সারসংক্ষেপ

| বিষয় | ব্যবহৃত কনসেপ্ট |
|-------|----------------|
| **OOP** | Class, Object, Constructor, Methods, Constants |
| **Encapsulation** | Private properties, Getters, Setters |
| **CRUD** | Create, Read, Update, Delete অপারেশন |
| **Array Functions** | `array_map`, `array_filter`, `array_unique`, `usort` |
| **File Handling** | `json_encode`, `json_decode`, `file_get_contents`, `file_put_contents` |
| **CLI** | `STDIN`, ANSI colors, মেনু, টেবিল |
| **Exception** | `throw`, `try-catch` |

---

## অতিরিক্ত চ্যালেঞ্জ

1. **Export to CSV** - প্রোডাক্ট CSV এ এক্সপোর্ট করুন
2. **Import from CSV** - CSV থেকে ইমপোর্ট করুন
3. **Price History** - দামের ইতিহাস রাখুন
4. **Multiple Warehouses** - একাধিক গুদাম সাপোর্ট করুন
5. **Barcode Support** - বারকোড দিয়ে প্রোডাক্ট খুঁজুন

---

**শুভ কামনা! Happy Coding!**
