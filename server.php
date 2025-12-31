<?php
require dirname(__DIR__) . '/klikpilih.terpalb25.web.id/socket/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;

class RealCountServer implements MessageComponentInterface {
    protected $clients;
    protected $dataCache = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $conn->pantauSesi = null; // Tempat simpan ID sesi pilihan user
        $this->clients->attach($conn);
        echo "Client ({$conn->resourceId}) terhubung.\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        
        // Client melapor ingin memantau ID Sesi tertentu
        if (isset($data['action']) && $data['action'] === 'subscribe') {
            $idSesi = (int)$data['id_sesi'];
            $from->pantauSesi = $idSesi;
            
            echo "Client ({$from->resourceId}) memantau Sesi: $idSesi\n";

            // Jika cache ada, kirim langsung agar tidak blank
            if (isset($this->dataCache[$idSesi])) {
                $from->send(json_encode($this->dataCache[$idSesi]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    // --- FUNGSI UTAMA (UPDATE SQL DISINI) ---
    public function cekDatabase() {
        // 1. Cek sesi mana saja yang sedang dilihat user
        $sesiDibutuhkan = [];
        foreach ($this->clients as $client) {
            if ($client->pantauSesi !== null) {
                $sesiDibutuhkan[] = $client->pantauSesi;
            }
        }

        if (empty($sesiDibutuhkan)) return;
        $sesiUnik = array_unique($sesiDibutuhkan);

        $koneksi = new mysqli("localhost", "klikpilih", "PBLkel3~!@#$%%$#@!~", "klikpilih"); // SESUAIKAN CONFIG DB

        // 2. Loop per sesi
        foreach ($sesiUnik as $idSesi) {
            
            // --- QUERY BARU SESUAI STRUKTUR TABEL ANDA ---
            $sql = "SELECT 
                        k.no_kandidat, 
                        k.nama_kandidat, 
                        COUNT(ks.no_kandidat) as total_suara
                    FROM kandidat k
                    LEFT JOIN kotak_suara ks 
                        ON k.no_kandidat = ks.no_kandidat 
                        AND ks.id_sesi = k.id_sesi
                    WHERE k.id_sesi = ?
                    GROUP BY k.no_kandidat
                    ORDER BY total_suara DESC";
            
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $idSesi);
            $stmt->execute();
            $result = $stmt->get_result();

            $hasilData = [];
            while ($row = $result->fetch_assoc()) {
                $hasilData[] = [
                    'no' => $row['no_kandidat'],
                    'nama' => $row['nama_kandidat'],
                    'suara' => (int)$row['total_suara']
                ];
            }

            // 3. Cek Perubahan Data & Broadcast
            $jsonBaru = json_encode($hasilData);
            $jsonLama = isset($this->dataCache[$idSesi]) ? json_encode($this->dataCache[$idSesi]) : '';

            if ($jsonBaru !== $jsonLama) {
                echo "Update data pada Sesi $idSesi\n";
                $this->dataCache[$idSesi] = $hasilData;

                foreach ($this->clients as $client) {
                    if ($client->pantauSesi === $idSesi) {
                        $client->send($jsonBaru);
                    }
                }
            }
        }
        $koneksi->close();
    }
}

// Jalankan Server
$app = new RealCountServer();
$loop = Loop::get();
$loop->addPeriodicTimer(1, function () use ($app) {
    $app->cekDatabase();
});
$server = new IoServer(
    new HttpServer(new WsServer($app)),
    new React\Socket\SocketServer('0.0.0.0:8080', [], $loop),
    $loop
);
echo "Server Real Count Berjalan...\n";
$server->run();