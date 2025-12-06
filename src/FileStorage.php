<?php

/**
 * FileStorage Class
 *
 * JSON ফাইলে প্রোডাক্ট ডাটা সেভ ও লোড করার জন্য।
 *
 * @package InventoryManagement
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */

require_once __DIR__ . '/Product.php';

class FileStorage
{
    /**
     * ডাটা ফোল্ডারের পাথ
     *
     * @var string
     */
    private string $dataPath;

    /**
     * JSON ফাইলের নাম
     */
    private const JSON_FILENAME = 'products.json';

    /**
     * Constructor - FileStorage তৈরি করে
     *
     * @param string $dataPath ডাটা ফোল্ডারের পাথ
     */
    public function __construct(string $dataPath)
    {
        $this->dataPath = rtrim($dataPath, '/');

        // ডাটা ফোল্ডার আছে কিনা নিশ্চিত করো
        $this->ensureDirectoryExists();
    }

    /**
     * ডাটা ফোল্ডার তৈরি করে (না থাকলে)
     *
     * @return void
     */
    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->dataPath)) {
            mkdir($this->dataPath, 0755, true);
        }
    }

    /**
     * JSON ফাইলের পূর্ণ পাথ রিটার্ন করে
     *
     * @return string ফাইল পাথ
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
        // প্রোডাক্টগুলোকে অ্যারেতে কনভার্ট করো
        $productsArray = array_map(function (Product $product) {
            return $product->toArray();
        }, $products);

        // JSON এ কনভার্ট করো (সুন্দর ফরম্যাটে)
        $json = json_encode(
            $productsArray,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        if ($json === false) {
            return false;
        }

        // ফাইলে লেখো
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

        // ফাইল না থাকলে খালি অ্যারে রিটার্ন করো
        if (!file_exists($jsonPath)) {
            return $products;
        }

        // ফাইল পড়ো
        $json = file_get_contents($jsonPath);

        if ($json === false) {
            return $products;
        }

        // JSON ডিকোড করো
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
     * একটি প্রোডাক্ট সেভ করে (যোগ বা আপডেট)
     *
     * @param Product $product সেভ করার প্রোডাক্ট
     * @return bool সফল হলে true
     */
    public function save(Product $product): bool
    {
        $products = $this->loadAll();
        $found = false;

        // প্রোডাক্ট আগে থেকে আছে কিনা চেক করো
        foreach ($products as $index => $existingProduct) {
            if ($existingProduct->getId() === $product->getId()) {
                // আপডেট করো
                $products[$index] = $product;
                $found = true;
                break;
            }
        }

        // নতুন প্রোডাক্ট হলে যোগ করো
        if (!$found) {
            $products[] = $product;
        }

        return $this->saveAll($products);
    }

    /**
     * একটি প্রোডাক্ট ডিলিট করে
     *
     * @param string $productId ডিলিট করার প্রোডাক্টের আইডি
     * @return bool সফল হলে true
     */
    public function delete(string $productId): bool
    {
        $products = $this->loadAll();
        $initialCount = count($products);

        // আইডি দিয়ে ফিল্টার করো
        $products = array_filter($products, function (Product $product) use ($productId) {
            return $product->getId() !== $productId;
        });

        // কিছু ডিলিট হয়নি মানে প্রোডাক্ট পাওয়া যায়নি
        if (count($products) === $initialCount) {
            return false;
        }

        // ইনডেক্স রিসেট করো
        $products = array_values($products);

        return $this->saveAll($products);
    }

    /**
     * আইডি দিয়ে প্রোডাক্ট খোঁজে
     *
     * @param string $productId খোঁজার আইডি
     * @return Product|null পেলে Product, না পেলে null
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
     *
     * @param string $name খোঁজার নাম
     * @return array মিলে যাওয়া Product এর অ্যারে
     */
    public function findByName(string $name): array
    {
        $products = $this->loadAll();
        $searchTerm = strtolower($name);

        return array_filter($products, function (Product $product) use ($searchTerm) {
            return strpos(strtolower($product->getName()), $searchTerm) !== false;
        });
    }

    /**
     * ক্যাটাগরি দিয়ে প্রোডাক্ট খোঁজে
     *
     * @param string $category খোঁজার ক্যাটাগরি
     * @return array ওই ক্যাটাগরির Product এর অ্যারে
     */
    public function findByCategory(string $category): array
    {
        $products = $this->loadAll();
        $searchTerm = strtolower($category);

        return array_filter($products, function (Product $product) use ($searchTerm) {
            return strtolower($product->getCategory()) === $searchTerm;
        });
    }

    /**
     * ফাইল আছে কিনা চেক করে
     *
     * @return bool থাকলে true
     */
    public function fileExists(): bool
    {
        return file_exists($this->getJsonPath());
    }

    /**
     * সব ডাটা মুছে ফেলে
     *
     * @return bool সফল হলে true
     */
    public function deleteAll(): bool
    {
        $jsonPath = $this->getJsonPath();

        if (file_exists($jsonPath)) {
            return unlink($jsonPath);
        }

        return true;
    }
}
