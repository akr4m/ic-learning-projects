<?php

/**
 * CLI Class
 *
 * Command Line Interface এর জন্য হেল্পার ক্লাস।
 * ইনপুট/আউটপুট, কালার, টেবিল ফরম্যাটিং হ্যান্ডল করে।
 *
 * @package InventoryManagement
 * @author  AkrAm <https://github.com/akr4m>
 * @version 1.0.0
 */
class CLI
{
    // ========================================
    // ANSI কালার কোডস
    // ========================================

    /** রিসেট কালার */
    public const RESET = "\033[0m";

    /** বোল্ড টেক্সট */
    public const BOLD = "\033[1m";

    /** লাল রঙ */
    public const RED = "\033[31m";

    /** সবুজ রঙ */
    public const GREEN = "\033[32m";

    /** হলুদ রঙ */
    public const YELLOW = "\033[33m";

    /** নীল রঙ */
    public const BLUE = "\033[34m";

    /** ম্যাজেন্টা রঙ */
    public const MAGENTA = "\033[35m";

    /** সায়ান রঙ */
    public const CYAN = "\033[36m";

    /** সাদা রঙ */
    public const WHITE = "\033[37m";

    /** ব্যাকগ্রাউন্ড লাল */
    public const BG_RED = "\033[41m";

    /** ব্যাকগ্রাউন্ড সবুজ */
    public const BG_GREEN = "\033[42m";

    /** ব্যাকগ্রাউন্ড হলুদ */
    public const BG_YELLOW = "\033[43m";

    // ========================================
    // আউটপুট মেথডস
    // ========================================

    /**
     * সাধারণ টেক্সট প্রিন্ট করে
     *
     * @param string $text প্রিন্ট করার টেক্সট
     * @return void
     */
    public static function print(string $text): void
    {
        echo $text;
    }

    /**
     * নতুন লাইনসহ টেক্সট প্রিন্ট করে
     *
     * @param string $text প্রিন্ট করার টেক্সট
     * @return void
     */
    public static function println(string $text = ''): void
    {
        echo $text . PHP_EOL;
    }

    /**
     * কালারসহ টেক্সট প্রিন্ট করে
     *
     * @param string $text  প্রিন্ট করার টেক্সট
     * @param string $color কালার কোড
     * @return void
     */
    public static function printColored(string $text, string $color): void
    {
        echo $color . $text . self::RESET;
    }

    /**
     * সফলতার মেসেজ প্রিন্ট করে (সবুজ)
     *
     * @param string $text মেসেজ
     * @return void
     */
    public static function success(string $text): void
    {
        self::println(self::GREEN . "✓ " . $text . self::RESET);
    }

    /**
     * এরর মেসেজ প্রিন্ট করে (লাল)
     *
     * @param string $text মেসেজ
     * @return void
     */
    public static function error(string $text): void
    {
        self::println(self::RED . "✗ " . $text . self::RESET);
    }

    /**
     * সতর্কতা মেসেজ প্রিন্ট করে (হলুদ)
     *
     * @param string $text মেসেজ
     * @return void
     */
    public static function warning(string $text): void
    {
        self::println(self::YELLOW . "⚠ " . $text . self::RESET);
    }

    /**
     * তথ্য মেসেজ প্রিন্ট করে (সায়ান)
     *
     * @param string $text মেসেজ
     * @return void
     */
    public static function info(string $text): void
    {
        self::println(self::CYAN . "ℹ " . $text . self::RESET);
    }

    /**
     * হেডার/টাইটেল প্রিন্ট করে
     *
     * @param string $text টাইটেল
     * @return void
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
     * সাবহেডার প্রিন্ট করে
     *
     * @param string $text সাবহেডার টেক্সট
     * @return void
     */
    public static function subHeader(string $text): void
    {
        self::println();
        self::println(self::BOLD . self::YELLOW . "▶ " . $text . self::RESET);
        self::println(str_repeat('-', strlen($text) + 2));
    }

    /**
     * একটি লাইন সেপারেটর প্রিন্ট করে
     *
     * @param int    $length লাইনের দৈর্ঘ্য
     * @param string $char   ব্যবহার করার ক্যারেক্টার
     * @return void
     */
    public static function separator(int $length = 50, string $char = '-'): void
    {
        self::println(str_repeat($char, $length));
    }

    /**
     * খালি লাইন প্রিন্ট করে
     *
     * @param int $count কতগুলো খালি লাইন
     * @return void
     */
    public static function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            self::println();
        }
    }

    /**
     * স্ক্রিন ক্লিয়ার করে
     *
     * @return void
     */
    public static function clear(): void
    {
        // Windows ও Unix/Linux/Mac উভয়ের জন্য
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }

    // ========================================
    // ইনপুট মেথডস
    // ========================================

    /**
     * ইউজার থেকে ইনপুট নেয়
     *
     * @param string $prompt প্রম্পট মেসেজ
     * @return string ইনপুট ভ্যালু
     */
    public static function input(string $prompt = ''): string
    {
        if ($prompt !== '') {
            self::print(self::BOLD . $prompt . self::RESET);
        }

        $input = trim(fgets(STDIN));
        return $input;
    }

    /**
     * ইউজার থেকে সংখ্যা ইনপুট নেয়
     *
     * @param string $prompt প্রম্পট মেসেজ
     * @return int|null বৈধ সংখ্যা বা null
     */
    public static function inputInt(string $prompt = ''): ?int
    {
        $input = self::input($prompt);

        if (is_numeric($input)) {
            return (int) $input;
        }

        return null;
    }

    /**
     * ইউজার থেকে ফ্লোট সংখ্যা ইনপুট নেয়
     *
     * @param string $prompt প্রম্পট মেসেজ
     * @return float|null বৈধ সংখ্যা বা null
     */
    public static function inputFloat(string $prompt = ''): ?float
    {
        $input = self::input($prompt);

        if (is_numeric($input)) {
            return (float) $input;
        }

        return null;
    }

    /**
     * Yes/No কনফার্মেশন নেয়
     *
     * @param string $prompt প্রম্পট মেসেজ
     * @return bool Yes হলে true
     */
    public static function confirm(string $prompt): bool
    {
        $input = self::input($prompt . " (y/n): ");
        return strtolower($input) === 'y' || strtolower($input) === 'yes';
    }

    /**
     * মেনু থেকে অপশন সিলেক্ট করতে দেয়
     *
     * @param array  $options অপশনের অ্যারে
     * @param string $prompt  প্রম্পট মেসেজ
     * @return int|null সিলেক্টেড ইনডেক্স বা null
     */
    public static function menu(array $options, string $prompt = "আপনার পছন্দ: "): ?int
    {
        foreach ($options as $index => $option) {
            self::println(self::CYAN . "  [" . ($index + 1) . "] " . self::RESET . $option);
        }

        self::println();
        $choice = self::inputInt($prompt);

        if ($choice !== null && $choice >= 1 && $choice <= count($options)) {
            return $choice - 1;
        }

        return null;
    }

    // ========================================
    // টেবিল ফরম্যাটিং
    // ========================================

    /**
     * টেবিল আকারে ডাটা প্রিন্ট করে
     *
     * @param array $headers টেবিল হেডার
     * @param array $rows    টেবিল রো (2D অ্যারে)
     * @return void
     */
    public static function table(array $headers, array $rows): void
    {
        // কলামের প্রস্থ বের করো
        $widths = [];

        // হেডার থেকে প্রস্থ বের করো
        foreach ($headers as $index => $header) {
            $widths[$index] = strlen($header);
        }

        // রো থেকে সর্বোচ্চ প্রস্থ বের করো
        foreach ($rows as $row) {
            foreach ($row as $index => $cell) {
                $cellLength = strlen((string) $cell);
                if (!isset($widths[$index]) || $cellLength > $widths[$index]) {
                    $widths[$index] = $cellLength;
                }
            }
        }

        // টেবিল লাইন তৈরি করো
        $line = '+';
        foreach ($widths as $width) {
            $line .= str_repeat('-', $width + 2) . '+';
        }

        // হেডার প্রিন্ট করো
        self::println($line);
        self::print('|');
        foreach ($headers as $index => $header) {
            self::print(self::BOLD . ' ' . str_pad($header, $widths[$index]) . ' ' . self::RESET . '|');
        }
        self::println();
        self::println($line);

        // রো প্রিন্ট করো
        foreach ($rows as $row) {
            self::print('|');
            foreach ($row as $index => $cell) {
                self::print(' ' . str_pad((string) $cell, $widths[$index]) . ' |');
            }
            self::println();
        }

        self::println($line);
    }

    /**
     * কী-ভ্যালু পেয়ার প্রিন্ট করে
     *
     * @param array $data কী-ভ্যালু অ্যারে
     * @return void
     */
    public static function keyValue(array $data): void
    {
        // সর্বোচ্চ কী-এর দৈর্ঘ্য বের করো
        $maxKeyLength = 0;
        foreach (array_keys($data) as $key) {
            if (strlen($key) > $maxKeyLength) {
                $maxKeyLength = strlen($key);
            }
        }

        // প্রিন্ট করো
        foreach ($data as $key => $value) {
            self::println(
                self::CYAN . str_pad($key, $maxKeyLength) . self::RESET .
                " : " .
                self::BOLD . $value . self::RESET
            );
        }
    }

    /**
     * প্রোগ্রেস বার দেখায়
     *
     * @param int $current বর্তমান ভ্যালু
     * @param int $total   মোট ভ্যালু
     * @param int $width   বারের প্রস্থ
     * @return void
     */
    public static function progressBar(int $current, int $total, int $width = 30): void
    {
        $percentage = ($current / $total) * 100;
        $filled = (int) (($current / $total) * $width);
        $empty = $width - $filled;

        $bar = str_repeat('█', $filled) . str_repeat('░', $empty);

        self::print("\r[" . $bar . "] " . number_format($percentage, 1) . "%");

        if ($current === $total) {
            self::println();
        }
    }

    // ========================================
    // ইউটিলিটি মেথডস
    // ========================================

    /**
     * টাকার পরিমাণ ফরম্যাট করে
     *
     * @param float $amount পরিমাণ
     * @return string ফরম্যাটেড স্ট্রিং (যেমন: ৳1,234.56)
     */
    public static function formatMoney(float $amount): string
    {
        return '৳' . number_format($amount, 2);
    }

    /**
     * সংখ্যা ফরম্যাট করে
     *
     * @param int $number সংখ্যা
     * @return string ফরম্যাটেড স্ট্রিং
     */
    public static function formatNumber(int $number): string
    {
        return number_format($number);
    }

    /**
     * তারিখ ফরম্যাট করে
     *
     * @param string $datetime ডেটটাইম স্ট্রিং
     * @return string ফরম্যাটেড তারিখ
     */
    public static function formatDate(string $datetime): string
    {
        return date('d M Y, h:i A', strtotime($datetime));
    }

    /**
     * কিছুক্ষণ অপেক্ষা করে
     *
     * @param int $seconds সেকেন্ড
     * @return void
     */
    public static function wait(int $seconds = 1): void
    {
        sleep($seconds);
    }

    /**
     * Enter চাপতে বলে
     *
     * @return void
     */
    public static function pressEnter(): void
    {
        self::input(PHP_EOL . "চালিয়ে যেতে Enter চাপুন...");
    }
}
