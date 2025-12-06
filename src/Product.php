<?php

/**
 * Product Class
 *
 * এই ক্লাসটি একটি প্রোডাক্টের ডাটা ম্যানেজ করে।
 * Encapsulation প্রিন্সিপল ব্যবহার করা হয়েছে।
 *
 * @package InventoryManagement
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */
class Product
{
    /**
     * প্রোডাক্টের ইউনিক আইডি
     *
     * @var string
     */
    private string $id;

    /**
     * প্রোডাক্টের নাম
     *
     * @var string
     */
    private string $name;

    /**
     * প্রোডাক্টের বিবরণ
     *
     * @var string
     */
    private string $description;

    /**
     * প্রোডাক্টের দাম (টাকায়)
     *
     * @var float
     */
    private float $price;

    /**
     * স্টকে থাকা পরিমাণ
     *
     * @var int
     */
    private int $quantity;

    /**
     * প্রোডাক্টের ক্যাটাগরি
     *
     * @var string
     */
    private string $category;

    /**
     * প্রোডাক্ট তৈরির সময়
     *
     * @var string
     */
    private string $createdAt;

    /**
     * সর্বশেষ আপডেটের সময়
     *
     * @var string
     */
    private string $updatedAt;

    /**
     * Low stock এর সীমা (এর নিচে গেলে alert দেখাবে)
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
     * @param string|null $id          প্রোডাক্ট আইডি (না দিলে অটো জেনারেট)
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
     *
     * @return string ইউনিক আইডি (যেমন: prod_abc123)
     */
    private function generateUniqueId(): string
    {
        return 'prod_' . bin2hex(random_bytes(8));
    }

    // ========================================
    // GETTER মেথডস (ডাটা পড়ার জন্য)
    // ========================================

    /**
     * প্রোডাক্ট আইডি রিটার্ন করে
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * প্রোডাক্টের নাম রিটার্ন করে
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * প্রোডাক্টের বিবরণ রিটার্ন করে
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * প্রোডাক্টের দাম রিটার্ন করে
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * স্টকের পরিমাণ রিটার্ন করে
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * ক্যাটাগরি রিটার্ন করে
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * তৈরির সময় রিটার্ন করে
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * আপডেটের সময় রিটার্ন করে
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    // ========================================
    // SETTER মেথডস (ডাটা পরিবর্তনের জন্য)
    // ========================================

    /**
     * প্রোডাক্টের নাম সেট করে
     *
     * @param string $name নতুন নাম
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->updateTimestamp();
    }

    /**
     * প্রোডাক্টের বিবরণ সেট করে
     *
     * @param string $description নতুন বিবরণ
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->updateTimestamp();
    }

    /**
     * প্রোডাক্টের দাম সেট করে
     *
     * @param float $price নতুন দাম
     * @return void
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
     * স্টকের পরিমাণ সেট করে
     *
     * @param int $quantity নতুন পরিমাণ
     * @return void
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

    /**
     * ক্যাটাগরি সেট করে
     *
     * @param string $category নতুন ক্যাটাগরি
     * @return void
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->updateTimestamp();
    }

    /**
     * তৈরির সময় সেট করে (ফাইল থেকে লোড করার সময়)
     *
     * @param string $createdAt টাইমস্ট্যাম্প
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * আপডেটের সময় সেট করে
     *
     * @param string $updatedAt টাইমস্ট্যাম্প
     * @return void
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // ========================================
    // ব্যবসায়িক লজিক মেথডস
    // ========================================

    /**
     * আপডেট টাইমস্ট্যাম্প রিফ্রেশ করে
     *
     * @return void
     */
    private function updateTimestamp(): void
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    /**
     * প্রোডাক্টের মোট মূল্য গণনা করে (দাম x পরিমাণ)
     *
     * @return float মোট মূল্য
     */
    public function getTotalValue(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * স্টক কম কিনা চেক করে
     *
     * @return bool কম হলে true
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= self::LOW_STOCK_THRESHOLD;
    }

    /**
     * স্টক শেষ কিনা চেক করে
     *
     * @return bool শেষ হলে true
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity === 0;
    }

    /**
     * স্টকে প্রোডাক্ট যোগ করে
     *
     * @param int $amount যোগ করার পরিমাণ
     * @return void
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
     * @param int $amount কমানোর পরিমাণ
     * @return void
     * @throws InvalidArgumentException পরিমাণ বেশি হলে বা নেগেটিভ হলে
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
     *
     * @return array প্রোডাক্ট ডাটার অ্যারে
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
     *
     * @param array $data প্রোডাক্ট ডাটার অ্যারে
     * @return Product নতুন Product অবজেক্ট
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

        // টাইমস্ট্যাম্প সেট করো
        if (isset($data['created_at'])) {
            $product->setCreatedAt($data['created_at']);
        }
        if (isset($data['updated_at'])) {
            $product->setUpdatedAt($data['updated_at']);
        }

        return $product;
    }

    /**
     * প্রোডাক্টের সামারি স্ট্রিং রিটার্ন করে
     *
     * @return string প্রোডাক্ট সামারি
     */
    public function __toString(): string
    {
        return sprintf(
            "[%s] %s - ৳%.2f x %d = ৳%.2f",
            $this->id,
            $this->name,
            $this->price,
            $this->quantity,
            $this->getTotalValue()
        );
    }
}
