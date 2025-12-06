<?php

/**
 * Inventory Class
 *
 * ইনভেন্টরি ম্যানেজমেন্টের মূল ক্লাস।
 * CRUD অপারেশন, মোট মূল্য গণনা ও Low Stock Alert হ্যান্ডল করে।
 *
 * @package InventoryManagement
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */

require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/FileStorage.php';

class Inventory
{
    /**
     * FileStorage ইনস্ট্যান্স
     *
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * মেমরিতে রাখা প্রোডাক্টের অ্যারে
     *
     * @var array
     */
    private array $products = [];

    /**
     * Constructor - Inventory তৈরি করে
     *
     * @param string $dataPath ডাটা ফোল্ডারের পাথ
     */
    public function __construct(string $dataPath)
    {
        $this->storage = new FileStorage($dataPath);

        // ফাইল থেকে প্রোডাক্ট লোড করো
        $this->loadProducts();
    }

    /**
     * ফাইল থেকে সব প্রোডাক্ট লোড করে
     *
     * @return void
     */
    private function loadProducts(): void
    {
        $this->products = $this->storage->loadAll();
    }

    /**
     * সব প্রোডাক্ট ফাইলে সেভ করে
     *
     * @return bool সফল হলে true
     */
    private function saveProducts(): bool
    {
        return $this->storage->saveAll($this->products);
    }

    // ========================================
    // CRUD অপারেশনস
    // ========================================

    /**
     * CREATE - নতুন প্রোডাক্ট তৈরি করে
     *
     * @param string $name        প্রোডাক্টের নাম
     * @param string $description প্রোডাক্টের বিবরণ
     * @param float  $price       প্রোডাক্টের দাম
     * @param int    $quantity    স্টকের পরিমাণ
     * @param string $category    ক্যাটাগরি
     * @return Product|null তৈরি হলে Product, ব্যর্থ হলে null
     */
    public function createProduct(
        string $name,
        string $description,
        float $price,
        int $quantity,
        string $category = 'General'
    ): ?Product {
        // নতুন প্রোডাক্ট তৈরি করো
        $product = new Product($name, $description, $price, $quantity, $category);

        // অ্যারেতে যোগ করো
        $this->products[] = $product;

        // ফাইলে সেভ করো
        if ($this->saveProducts()) {
            return $product;
        }

        // সেভ ব্যর্থ হলে অ্যারে থেকে বাদ দাও
        array_pop($this->products);
        return null;
    }

    /**
     * READ - আইডি দিয়ে প্রোডাক্ট খোঁজে
     *
     * @param string $productId প্রোডাক্ট আইডি
     * @return Product|null পেলে Product, না পেলে null
     */
    public function getProduct(string $productId): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $productId) {
                return $product;
            }
        }
        return null;
    }

    /**
     * READ - সব প্রোডাক্ট রিটার্ন করে
     *
     * @return array Product অবজেক্টের অ্যারে
     */
    public function getAllProducts(): array
    {
        return $this->products;
    }

    /**
     * UPDATE - প্রোডাক্ট আপডেট করে
     *
     * @param string      $productId   প্রোডাক্ট আইডি
     * @param string|null $name        নতুন নাম (না দিলে আগেরটাই থাকবে)
     * @param string|null $description নতুন বিবরণ
     * @param float|null  $price       নতুন দাম
     * @param int|null    $quantity    নতুন পরিমাণ
     * @param string|null $category    নতুন ক্যাটাগরি
     * @return bool সফল হলে true
     */
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
        if ($name !== null) {
            $product->setName($name);
        }
        if ($description !== null) {
            $product->setDescription($description);
        }
        if ($price !== null) {
            $product->setPrice($price);
        }
        if ($quantity !== null) {
            $product->setQuantity($quantity);
        }
        if ($category !== null) {
            $product->setCategory($category);
        }

        return $this->saveProducts();
    }

    /**
     * DELETE - প্রোডাক্ট ডিলিট করে
     *
     * @param string $productId ডিলিট করার প্রোডাক্টের আইডি
     * @return bool সফল হলে true
     */
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

        // কিছু ডিলিট হয়নি মানে প্রোডাক্ট পাওয়া যায়নি
        if (count($this->products) === $initialCount) {
            return false;
        }

        return $this->saveProducts();
    }

    // ========================================
    // সার্চ অপারেশনস
    // ========================================

    /**
     * নাম দিয়ে প্রোডাক্ট খোঁজে
     *
     * @param string $name খোঁজার নাম
     * @return array মিলে যাওয়া Product এর অ্যারে
     */
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

    /**
     * ক্যাটাগরি দিয়ে প্রোডাক্ট খোঁজে
     *
     * @param string $category খোঁজার ক্যাটাগরি
     * @return array ওই ক্যাটাগরির Product এর অ্যারে
     */
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

    /**
     * সব ইউনিক ক্যাটাগরি রিটার্ন করে
     *
     * @return array ক্যাটাগরির অ্যারে
     */
    public function getAllCategories(): array
    {
        $categories = array_map(function (Product $product) {
            return $product->getCategory();
        }, $this->products);

        // ইউনিক ক্যাটাগরি
        return array_unique($categories);
    }

    // ========================================
    // স্ট্যাটিস্টিক্স ও রিপোর্ট
    // ========================================

    /**
     * ইনভেন্টরির মোট মূল্য গণনা করে
     *
     * @return float মোট মূল্য
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
     * মোট প্রোডাক্ট সংখ্যা রিটার্ন করে
     *
     * @return int প্রোডাক্ট সংখ্যা
     */
    public function getTotalProductCount(): int
    {
        return count($this->products);
    }

    /**
     * মোট স্টক (সব প্রোডাক্টের পরিমাণের যোগফল) রিটার্ন করে
     *
     * @return int মোট স্টক
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
     * Low stock প্রোডাক্টগুলো রিটার্ন করে
     *
     * @return array Low stock Product এর অ্যারে
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
     * Out of stock প্রোডাক্টগুলো রিটার্ন করে
     *
     * @return array Out of stock Product এর অ্যারে
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
     * Low stock alert আছে কিনা চেক করে
     *
     * @return bool থাকলে true
     */
    public function hasLowStockAlert(): bool
    {
        return count($this->getLowStockProducts()) > 0;
    }

    // ========================================
    // স্টক অপারেশনস
    // ========================================

    /**
     * প্রোডাক্টে স্টক যোগ করে
     *
     * @param string $productId প্রোডাক্ট আইডি
     * @param int    $amount    যোগ করার পরিমাণ
     * @return bool সফল হলে true
     */
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

    /**
     * প্রোডাক্ট থেকে স্টক কমায়
     *
     * @param string $productId প্রোডাক্ট আইডি
     * @param int    $amount    কমানোর পরিমাণ
     * @return bool সফল হলে true
     */
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
     * নাম অনুযায়ী সর্ট করা প্রোডাক্ট রিটার্ন করে
     *
     * @param bool $ascending বড় থেকে ছোট হলে false
     * @return array সর্ট করা Product এর অ্যারে
     */
    public function getProductsSortedByName(bool $ascending = true): array
    {
        $products = $this->products;

        usort($products, function (Product $a, Product $b) use ($ascending) {
            $result = strcmp($a->getName(), $b->getName());
            return $ascending ? $result : -$result;
        });

        return $products;
    }

    /**
     * দাম অনুযায়ী সর্ট করা প্রোডাক্ট রিটার্ন করে
     *
     * @param bool $ascending কম থেকে বেশি হলে true
     * @return array সর্ট করা Product এর অ্যারে
     */
    public function getProductsSortedByPrice(bool $ascending = true): array
    {
        $products = $this->products;

        usort($products, function (Product $a, Product $b) use ($ascending) {
            $result = $a->getPrice() <=> $b->getPrice();
            return $ascending ? $result : -$result;
        });

        return $products;
    }

    /**
     * স্টক অনুযায়ী সর্ট করা প্রোডাক্ট রিটার্ন করে
     *
     * @param bool $ascending কম থেকে বেশি হলে true
     * @return array সর্ট করা Product এর অ্যারে
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
     * ইনভেন্টরি সামারি অ্যারে রিটার্ন করে
     *
     * @return array সামারি ডাটা
     */
    public function getSummary(): array
    {
        return [
            'total_products'      => $this->getTotalProductCount(),
            'total_stock'         => $this->getTotalStock(),
            'total_value'         => $this->getTotalInventoryValue(),
            'low_stock_count'     => count($this->getLowStockProducts()),
            'out_of_stock_count'  => count($this->getOutOfStockProducts()),
            'categories'          => count($this->getAllCategories())
        ];
    }
}
