<?php

namespace App\Services;

// add for read value from .env
require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use App\Models\Message;
use App\Models\Pembayaran;
use App\Models\Token;
use Dotenv\Dotenv;
use PDO;
use PDOException;

class WaBlasService
{

    static string $token;
    static string $baseUrl;
    private static ?PDO $pdo = null;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        // set token from .env file
        // self::$token = $_ENV['TOKEN_WABLAS'];
        self::$token = Token::first()->token ?? '';
        self::$baseUrl = $_ENV['BASE_URL_API'];
    }
    // Inisialisasi koneksi PDO
    private static function getPdo(): PDO
    {
        if (self::$pdo === null) {
            // Load environment variables from .env file
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();

            // Read database connection information from environment variables
            $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_DATABASE'] . ';charset=utf8';
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];

            try {
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    // Metode untuk mendapatkan status tagihan
    private static function getTagihanStatus(string $tagihan): string
    {
        try {
            // Asumsi: self::getPdo() adalah metode yang mengembalikan instance PDO yang sudah terkoneksi dengan database
            $pdo = self::getPdo();

            $query = 'SELECT status FROM tagihans WHERE siswa_id = :tagihan LIMIT 1';
            $stmt = $pdo->prepare($query);
            $stmt->execute([':tagihan' => $tagihan]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['status'] : 'unknown';
        } catch (PDOException $e) {
            // Menangani exception PDO jika terjadi kesalahan
            error_log('Database query error: ' . $e->getMessage());
            return 'error'; // Mengembalikan 'error' jika terjadi kesalahan
        }
    }


    // Metode untuk mendapatkan jumlah tagihan
    private static function getJumlahTagihan(string $tagihan_details): float
    {
        try {
            // Mendapatkan instance PDO
            $pdo = self::getPdo();
            $pembayaran = Pembayaran::select('jumlah_dibayar')->where('tagihan_id', $tagihan_details)->sum('jumlah_dibayar');
            $queryTagihan = '
                SELECT td.jumlah_biaya
                FROM tagihan_details td
                JOIN tagihans t ON td.tagihan_id = t.id
                WHERE td.tagihan_id = :tagihan
                LIMIT 1
            ';
            $stmt = $pdo->prepare($queryTagihan);
            $stmt->execute([':tagihan' => $tagihan_details]);
            $tagihan = $stmt->fetch(PDO::FETCH_ASSOC);
            $tagihanBiaya = isset($tagihan['jumlah_biaya']) ? (float)$tagihan['jumlah_biaya'] : 0.0;
            // Mengembalikan jumlah_biaya jika ada, jika tidak, kembalikan 0.0
            return $tagihanBiaya - $pembayaran;
        } catch (PDOException $e) {
            // Menangani exception PDO jika terjadi kesalahan
            error_log('Database query error: ' . $e->getMessage());
            return 0.0; // Mengembalikan 0.0 jika terjadi kesalahan
        }
    }


    // Metode untuk mendapatkan jumlah dibayar
    // private static function getJumlahDibayar(string $tagihan): float
    // {
    //     try {
    //         // Mendapatkan instance PDO
    //         $pdo = self::getPdo();
    //         $query = 'SELECT jumlah_dibayar FROM pembayarans WHERE tagihan_id = :tagihan LIMIT 1';
    //         $stmt = $pdo->prepare($query);
    //         $stmt->execute([':tagihan' => $tagihan]);
    //         $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //         return isset($result['jumlah_dibayar']) ? (float)$result['jumlah_dibayar'] : 0.0;
    //     } catch (PDOException $e) {
    //         // Menangani exception PDO jika terjadi kesalahan
    //         error_log('Database query error: ' . $e->getMessage());
    //         return 0.0; // Mengembalikan 0.0 jika terjadi kesalahan
    //     }
    // }


    private static function isPaid(string $tagihan): bool
    {
        $pdo = self::getPdo();
        $query = 'SELECT status FROM tagihans WHERE id = :tagihan LIMIT 1';
        $stmt = $pdo->prepare($query);
        $stmt->execute([':tagihan' => $tagihan]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Mengembalikan true jika status adalah 'lunas', false jika tidak
            return $result['status'] === 'lunas';
        }

        // Jika tidak ada hasil, anggap tagihan belum dibayar (status bukan 'lunas')
        return false;
    }

    // Get device info
    public static function getDeviceInfo()
    {
        $url = self::$baseUrl . "/device/info?token=" . self::$token;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL,  $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
        return $result->data;
    }

    // disconnect device
    public static function disconnectDevice(): bool
    {
        $url = self::$baseUrl . "/device/disconnect";
        $headers = [
            'Authorization: ' . self::$token,
            'Content-Type: application/json',
        ];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL,  $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
        return $result->status;
    }

    public static function sendSingleMessage(string $to, string $message): bool
    {
        $url = self::$baseUrl . "/send-message";
        $data = [
            "phone" => $to,
            "message" => $message,
        ];
        $headers = [
            'Authorization: ' . self::$token,
            'Content-Type: application/json',
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_URL,  $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
        // dd($result);
        // return $result->status
        return $result->status;
    }

    // public static function sendMultipleMessage(array $to, array $siswa, string $tagihan, string $jatuhTempo, float $jumlah_biaya): object
    // {
    //     $url = self::$baseUrl . "/send-message";
    //     $bulan = date('m', strtotime($tagihan));
    //     $tahun = date('Y', strtotime($tagihan));
    //     $message = Message::first()->message ?? '';
    //     // Format jumlah_biaya
    //     $jumlah_biaya_formatted = number_format($jumlah_biaya, 2, ',', '.');
    //     $data = array();
    //     for ($i = 0; $i < count($to); $i++) {
    //         $data[] = [
    //             "phone" => $to[$i],
    //             "message" => str_replace(['{bulan}', '{tahun}', '{nama}', '{jatuh-tempo}', '{jumlah_biaya}'], [$bulan, $tahun, $siswa[$i], $jatuhTempo, $jumlah_biaya_formatted], $message),
    //         ];
    //     }

    //     $payload = [
    //         "data" => $data
    //     ];
    //     // dd(json_encode($body));


    //     $curl = curl_init($url);
    //     curl_setopt(
    //         $curl,
    //         CURLOPT_HTTPHEADER,
    //         array(
    //             "Authorization: " . self::$token,
    //             "Content-Type: application/json"
    //         )
    //     );
    //     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    //     curl_setopt($curl, CURLOPT_URL,  "https://jkt.wablas.com/api/v2/send-message");
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    //     $result = curl_exec($curl);
    //     $response = json_decode($result);
    //     curl_close($curl);
    //     echo "<script> console.log('$result')</script>";
    //     return $response;
    // }

    public static function sendSchedulesMessage(array $to, array $siswa, string $schedule, string $tagihan, int $tagihanId, string $jatuhTempo): object
    {
        // Cek apakah tagihan sudah dibayar
        if (self::isPaid($tagihan)) {
            // Jika tagihan sudah dibayar, tidak perlu mengirim pesan
            return (object)[
                'status' => 'success',
                'message' => 'The bill is already paid, no message will be sent.'
            ];
        }

        $url = self::$baseUrl . "/v2/schedule";

        $message = Message::first()->message ?? '';

        $bulan = date('m', strtotime($tagihan));
        $tahun = date('Y', strtotime($tagihan));
        $jumlah_biaya = self::getJumlahTagihan($tagihanId);
        $jumlah_biaya_formatted = number_format($jumlah_biaya, 2, ',', '.');

        for ($i = 0; $i < count($to); $i++) {
            $data[] = [
                "category" => "text",
                "phone" => $to[$i],
                "text" => str_replace(['{bulan}', '{tahun}', '{nama}', '{jatuh-tempo}', '{jumlah_biaya}'], [$bulan, $tahun, $siswa[$i], $jatuhTempo, $jumlah_biaya_formatted], $message),
                "scheduled_at" => $schedule // example date : "2021-09-01 10:00:00",
            ];
        }
        $payload = [
            "data" => $data
        ];

        $headers = [
            'Authorization: ' . self::$token,
            'Content-Type: application/json',
        ];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
        return $result;
    }
}
