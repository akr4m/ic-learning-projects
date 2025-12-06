#!/usr/bin/env php
<?php

/**
 * Inventory Management CLI Application
 *
 * মূল CLI অ্যাপ্লিকেশন যা ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম চালায়।
 *
 * @package InventoryManagement
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 *
 * ব্যবহার: php app.php
 */

// ক্লাস ফাইলগুলো লোড করো
require_once __DIR__ . '/src/Product.php';
require_once __DIR__ . '/src/FileStorage.php';
require_once __DIR__ . '/src/Inventory.php';
require_once __DIR__ . '/src/CLI.php';

/**
 * InventoryApp Class
 *
 * CLI অ্যাপ্লিকেশনের মূল ক্লাস।
 */
class InventoryApp
{
    /**
     * Inventory ইনস্ট্যান্স
     *
     * @var Inventory
     */
    private Inventory $inventory;

    /**
     * অ্যাপ চলছে কিনা
     *
     * @var bool
     */
    private bool $running = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        // ইনভেন্টরি তৈরি করো (data ফোল্ডারে সেভ হবে)
        $this->inventory = new Inventory(__DIR__ . '/data');
    }

    /**
     * অ্যাপ্লিকেশন চালু করে
     *
     * @return void
     */
    public function run(): void
    {
        // স্বাগত মেসেজ দেখাও
        $this->showWelcome();

        // Low stock alert চেক করো
        $this->checkLowStockAlert();

        // মেইন লুপ
        while ($this->running) {
            $this->showMainMenu();
        }

        // বিদায় মেসেজ
        CLI::newLine();
        CLI::success("ধন্যবাদ! আবার আসবেন।");
        CLI::newLine();
    }

    /**
     * স্বাগত মেসেজ দেখায়
     *
     * @return void
     */
    private function showWelcome(): void
    {
        CLI::clear();
        CLI::header("ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম");
        CLI::info("Version 1.0.0 | by AkrAm");
        CLI::newLine();
    }

    /**
     * Low stock alert চেক ও দেখায়
     *
     * @return void
     */
    private function checkLowStockAlert(): void
    {
        if ($this->inventory->hasLowStockAlert()) {
            $lowStock = $this->inventory->getLowStockProducts();
            CLI::warning("সতর্কতা: " . count($lowStock) . " টি প্রোডাক্টের স্টক কম!");
            CLI::newLine();
        }
    }

    /**
     * মেইন মেনু দেখায় ও অপশন হ্যান্ডল করে
     *
     * @return void
     */
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

        if ($choice === null) {
            CLI::error("সঠিক অপশন নির্বাচন করুন।");
            CLI::wait(1);
            return;
        }

        // অপশন অনুযায়ী অ্যাকশন নাও
        switch ($choice) {
            case 0:
                $this->addProduct();
                break;
            case 1:
                $this->listProducts();
                break;
            case 2:
                $this->searchProduct();
                break;
            case 3:
                $this->updateProduct();
                break;
            case 4:
                $this->deleteProduct();
                break;
            case 5:
                $this->manageStock();
                break;
            case 6:
                $this->showLowStockAlerts();
                break;
            case 7:
                $this->showSummary();
                break;
            case 8:
                $this->running = false;
                break;
        }
    }

    /**
     * নতুন প্রোডাক্ট যোগ করে
     *
     * @return void
     */
    private function addProduct(): void
    {
        CLI::subHeader("নতুন প্রোডাক্ট যোগ করুন");

        // ইনপুট নাও
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
        if (empty($category)) {
            $category = 'General';
        }

        // প্রোডাক্ট তৈরি করো
        $product = $this->inventory->createProduct(
            $name,
            $description,
            $price,
            $quantity,
            $category
        );

        if ($product !== null) {
            CLI::newLine();
            CLI::success("প্রোডাক্ট সফলভাবে যোগ করা হয়েছে!");
            CLI::info("আইডি: " . $product->getId());
        } else {
            CLI::error("প্রোডাক্ট যোগ করতে সমস্যা হয়েছে।");
        }

        CLI::pressEnter();
    }

    /**
     * সব প্রোডাক্ট তালিকা দেখায়
     *
     * @return void
     */
    private function listProducts(): void
    {
        CLI::subHeader("সব প্রোডাক্ট");

        $products = $this->inventory->getAllProducts();

        if (empty($products)) {
            CLI::warning("কোনো প্রোডাক্ট নেই।");
            CLI::pressEnter();
            return;
        }

        // সর্টিং অপশন
        CLI::println("সর্ট করুন:");
        $sortOptions = [
            "নাম অনুযায়ী (A-Z)",
            "দাম অনুযায়ী (কম থেকে বেশি)",
            "স্টক অনুযায়ী (কম থেকে বেশি)",
            "সর্ট ছাড়াই দেখুন"
        ];

        $sortChoice = CLI::menu($sortOptions);

        switch ($sortChoice) {
            case 0:
                $products = $this->inventory->getProductsSortedByName();
                break;
            case 1:
                $products = $this->inventory->getProductsSortedByPrice();
                break;
            case 2:
                $products = $this->inventory->getProductsSortedByQuantity();
                break;
        }

        // টেবিল হেডার
        $headers = ['আইডি', 'নাম', 'দাম', 'স্টক', 'মোট মূল্য', 'ক্যাটাগরি'];

        // টেবিল রো তৈরি করো
        $rows = [];
        foreach ($products as $product) {
            $row = [
                substr($product->getId(), 0, 12) . '...',
                substr($product->getName(), 0, 20),
                CLI::formatMoney($product->getPrice()),
                $product->getQuantity(),
                CLI::formatMoney($product->getTotalValue()),
                $product->getCategory()
            ];

            // Low stock হলে মার্ক করো
            if ($product->isLowStock()) {
                $row[3] = $product->getQuantity() . ' ⚠';
            }

            $rows[] = $row;
        }

        CLI::newLine();
        CLI::table($headers, $rows);
        CLI::newLine();
        CLI::info("মোট: " . count($products) . " টি প্রোডাক্ট");

        CLI::pressEnter();
    }

    /**
     * প্রোডাক্ট খোঁজে
     *
     * @return void
     */
    private function searchProduct(): void
    {
        CLI::subHeader("প্রোডাক্ট খুঁজুন");

        $options = [
            "নাম দিয়ে খুঁজুন",
            "আইডি দিয়ে খুঁজুন",
            "ক্যাটাগরি দিয়ে খুঁজুন"
        ];

        $choice = CLI::menu($options);

        if ($choice === null) {
            return;
        }

        switch ($choice) {
            case 0:
                $name = CLI::input("প্রোডাক্টের নাম: ");
                $products = $this->inventory->searchByName($name);
                $this->displaySearchResults($products);
                break;

            case 1:
                $id = CLI::input("প্রোডাক্ট আইডি: ");
                $product = $this->inventory->getProduct($id);
                if ($product) {
                    $this->displayProductDetails($product);
                } else {
                    CLI::error("প্রোডাক্ট পাওয়া যায়নি।");
                }
                break;

            case 2:
                // সব ক্যাটাগরি দেখাও
                $categories = $this->inventory->getAllCategories();
                if (empty($categories)) {
                    CLI::warning("কোনো ক্যাটাগরি নেই।");
                } else {
                    CLI::println("উপলব্ধ ক্যাটাগরি:");
                    $catChoice = CLI::menu(array_values($categories));
                    if ($catChoice !== null) {
                        $selectedCategory = array_values($categories)[$catChoice];
                        $products = $this->inventory->searchByCategory($selectedCategory);
                        $this->displaySearchResults($products);
                    }
                }
                break;
        }

        CLI::pressEnter();
    }

    /**
     * সার্চ রেজাল্ট দেখায়
     *
     * @param array $products প্রোডাক্ট অ্যারে
     * @return void
     */
    private function displaySearchResults(array $products): void
    {
        if (empty($products)) {
            CLI::warning("কোনো প্রোডাক্ট পাওয়া যায়নি।");
            return;
        }

        CLI::success("পাওয়া গেছে: " . count($products) . " টি প্রোডাক্ট");
        CLI::newLine();

        foreach ($products as $product) {
            $this->displayProductDetails($product);
            CLI::separator();
        }
    }

    /**
     * একটি প্রোডাক্টের বিস্তারিত দেখায়
     *
     * @param Product $product প্রোডাক্ট অবজেক্ট
     * @return void
     */
    private function displayProductDetails(Product $product): void
    {
        CLI::newLine();
        CLI::keyValue([
            'আইডি'      => $product->getId(),
            'নাম'       => $product->getName(),
            'বিবরণ'     => $product->getDescription() ?: '-',
            'দাম'       => CLI::formatMoney($product->getPrice()),
            'স্টক'      => $product->getQuantity() . ($product->isLowStock() ? ' (⚠ কম)' : ''),
            'মোট মূল্য'  => CLI::formatMoney($product->getTotalValue()),
            'ক্যাটাগরি'  => $product->getCategory(),
            'তৈরি'      => CLI::formatDate($product->getCreatedAt()),
            'আপডেট'     => CLI::formatDate($product->getUpdatedAt())
        ]);
    }

    /**
     * প্রোডাক্ট আপডেট করে
     *
     * @return void
     */
    private function updateProduct(): void
    {
        CLI::subHeader("প্রোডাক্ট আপডেট করুন");

        $id = CLI::input("প্রোডাক্ট আইডি: ");
        $product = $this->inventory->getProduct($id);

        if ($product === null) {
            CLI::error("প্রোডাক্ট পাওয়া যায়নি।");
            CLI::pressEnter();
            return;
        }

        // বর্তমান ডাটা দেখাও
        CLI::info("বর্তমান তথ্য:");
        $this->displayProductDetails($product);

        CLI::newLine();
        CLI::info("নতুন ভ্যালু দিন (খালি রাখলে আগেরটাই থাকবে):");
        CLI::newLine();

        // নতুন ইনপুট নাও
        $name = CLI::input("নাম [" . $product->getName() . "]: ");
        $description = CLI::input("বিবরণ [" . ($product->getDescription() ?: '-') . "]: ");
        $priceInput = CLI::input("দাম [" . $product->getPrice() . "]: ");
        $quantityInput = CLI::input("পরিমাণ [" . $product->getQuantity() . "]: ");
        $category = CLI::input("ক্যাটাগরি [" . $product->getCategory() . "]: ");

        // আপডেট করো
        $result = $this->inventory->updateProduct(
            $id,
            !empty($name) ? $name : null,
            !empty($description) ? $description : null,
            is_numeric($priceInput) ? (float) $priceInput : null,
            is_numeric($quantityInput) ? (int) $quantityInput : null,
            !empty($category) ? $category : null
        );

        if ($result) {
            CLI::success("প্রোডাক্ট আপডেট হয়েছে!");
        } else {
            CLI::error("আপডেট করতে সমস্যা হয়েছে।");
        }

        CLI::pressEnter();
    }

    /**
     * প্রোডাক্ট ডিলিট করে
     *
     * @return void
     */
    private function deleteProduct(): void
    {
        CLI::subHeader("প্রোডাক্ট ডিলিট করুন");

        $id = CLI::input("প্রোডাক্ট আইডি: ");
        $product = $this->inventory->getProduct($id);

        if ($product === null) {
            CLI::error("প্রোডাক্ট পাওয়া যায়নি।");
            CLI::pressEnter();
            return;
        }

        // ডিলিট করার আগে দেখাও
        CLI::warning("এই প্রোডাক্টটি ডিলিট হবে:");
        $this->displayProductDetails($product);

        CLI::newLine();
        if (CLI::confirm("আপনি কি নিশ্চিত?")) {
            if ($this->inventory->deleteProduct($id)) {
                CLI::success("প্রোডাক্ট ডিলিট হয়েছে!");
            } else {
                CLI::error("ডিলিট করতে সমস্যা হয়েছে।");
            }
        } else {
            CLI::info("ডিলিট বাতিল করা হয়েছে।");
        }

        CLI::pressEnter();
    }

    /**
     * স্টক ম্যানেজ করে (যোগ/কমানো)
     *
     * @return void
     */
    private function manageStock(): void
    {
        CLI::subHeader("স্টক ম্যানেজমেন্ট");

        $id = CLI::input("প্রোডাক্ট আইডি: ");
        $product = $this->inventory->getProduct($id);

        if ($product === null) {
            CLI::error("প্রোডাক্ট পাওয়া যায়নি।");
            CLI::pressEnter();
            return;
        }

        CLI::info($product->getName() . " - বর্তমান স্টক: " . $product->getQuantity());
        CLI::newLine();

        $options = [
            "স্টক যোগ করুন",
            "স্টক কমান"
        ];

        $choice = CLI::menu($options);

        if ($choice === null) {
            return;
        }

        $amount = CLI::inputInt("পরিমাণ: ");

        if ($amount === null || $amount <= 0) {
            CLI::error("সঠিক পরিমাণ দিন।");
            CLI::pressEnter();
            return;
        }

        if ($choice === 0) {
            // স্টক যোগ করো
            if ($this->inventory->addStock($id, $amount)) {
                $newProduct = $this->inventory->getProduct($id);
                CLI::success("স্টক যোগ হয়েছে! নতুন স্টক: " . $newProduct->getQuantity());
            } else {
                CLI::error("স্টক যোগ করতে সমস্যা হয়েছে।");
            }
        } else {
            // স্টক কমাও
            if ($this->inventory->removeStock($id, $amount)) {
                $newProduct = $this->inventory->getProduct($id);
                CLI::success("স্টক কমানো হয়েছে! নতুন স্টক: " . $newProduct->getQuantity());
            } else {
                CLI::error("স্টক কমাতে সমস্যা হয়েছে। (পর্যাপ্ত স্টক নেই?)");
            }
        }

        CLI::pressEnter();
    }

    /**
     * Low stock alerts দেখায়
     *
     * @return void
     */
    private function showLowStockAlerts(): void
    {
        CLI::subHeader("Low Stock Alerts");

        $lowStock = $this->inventory->getLowStockProducts();
        $outOfStock = $this->inventory->getOutOfStockProducts();

        // Out of Stock
        if (!empty($outOfStock)) {
            CLI::error("স্টক শেষ (" . count($outOfStock) . " টি):");
            CLI::newLine();

            foreach ($outOfStock as $product) {
                CLI::println(CLI::RED . "  • " . $product->getName() . " [" . $product->getId() . "]" . CLI::RESET);
            }

            CLI::newLine();
        }

        // Low Stock (কিন্তু out of stock না)
        $lowStockOnly = array_filter($lowStock, function ($p) {
            return !$p->isOutOfStock();
        });

        if (!empty($lowStockOnly)) {
            CLI::warning("স্টক কম (" . count($lowStockOnly) . " টি):");
            CLI::newLine();

            foreach ($lowStockOnly as $product) {
                CLI::println(
                    CLI::YELLOW . "  • " . $product->getName() .
                    " - স্টক: " . $product->getQuantity() .
                    " [" . $product->getId() . "]" . CLI::RESET
                );
            }

            CLI::newLine();
        }

        if (empty($lowStock)) {
            CLI::success("সব প্রোডাক্টের স্টক ঠিক আছে!");
        }

        CLI::newLine();
        CLI::info("Low Stock সীমা: ≤ " . Product::LOW_STOCK_THRESHOLD . " টি");

        CLI::pressEnter();
    }

    /**
     * ইনভেন্টরি সামারি দেখায়
     *
     * @return void
     */
    private function showSummary(): void
    {
        CLI::subHeader("ইনভেন্টরি সামারি");

        $summary = $this->inventory->getSummary();

        CLI::keyValue([
            'মোট প্রোডাক্ট'        => CLI::formatNumber($summary['total_products']) . ' টি',
            'মোট স্টক'            => CLI::formatNumber($summary['total_stock']) . ' টি',
            'মোট মূল্য'           => CLI::formatMoney($summary['total_value']),
            'ক্যাটাগরি'           => $summary['categories'] . ' টি',
            'Low Stock'         => $summary['low_stock_count'] . ' টি',
            'Out of Stock'      => $summary['out_of_stock_count'] . ' টি'
        ]);

        CLI::pressEnter();
    }
}

// ========================================
// অ্যাপ্লিকেশন চালাও
// ========================================

$app = new InventoryApp();
$app->run();
