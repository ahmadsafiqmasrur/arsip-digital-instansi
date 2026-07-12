<style>
    /* CSS Internal untuk halaman Dashboard Utama */
    .user-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #e0e0e0;
        margin-bottom: 50px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background-color: #000;
        border-radius: 50%;
    }

    .user-text h3 {
        margin: 0 0 5px 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #000;
    }

    .user-text p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #333;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: color 0.2s;
    }

    .logout-btn:hover {
        color: #d63031;
    }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .btn-tambah {
        background-color: #0c7a5c;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .btn-tambah:hover {
        background-color: #09634a;
    }

    .search-form {
        display: flex;
        gap: 0;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 4px;
        width: 450px;
    }

    .search-input {
        border: none;
        outline: none;
        padding: 10px 15px;
        flex-grow: 1;
        font-size: 0.95rem;
        background: transparent;
        font-family: 'DM Sans', sans-serif;
    }

    .search-input::placeholder {
        color: #999;
    }

    .btn-cari {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 30px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
    }

    .btn-cari:hover {
        background-color: #0069d9;
    }

    /* Grid Kartu Statistik per Kelurahan */
    .kelurahan-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .kelurahan-card {
        border-radius: 12px;
        padding: 25px 20px;
        text-decoration: none;
        color: #fff;
        display: block;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .kelurahan-card.canden {
        background-color: #0c7a5c;
    }
    .kelurahan-card.patalan {
        background-color: #198754;
    }
    .kelurahan-card.sumberagung {
        background-color: #20c997;
    }
    .kelurahan-card.trimulyo {
        background-color: #2ecc71;
    }

    .kelurahan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .kelurahan-card h4 {
        margin: 0 0 8px 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .kelurahan-card p {
        margin: 0;
        font-size: 0.95rem;
        color: #ffffff;
    }

    @media (max-width: 1024px) {
        .kelurahan-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .search-form {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .kelurahan-grid {
            grid-template-columns: 1fr;
        }

        .user-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
    }

    /* Styling Filter Waktu */
    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid #dcdcdc;
        background: #fff;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .filter-btn.active {
        background-color: #0c7a5c;
        color: #fff;
        border-color: #0c7a5c;
    }

    .filter-btn:hover:not(.active) {
        background-color: #f4f5f7;
    }
</style>

<?php
/**
 * @var string $search
 * @var string $filter
 * @var string $timeframe
 * @var string $selected_year
 * @var array $kelurahans
 * @var array $counts
 * @var array $available_years
 */
?>

<!-- Informasi Pengguna Login -->
<div class="user-card">
    <div class="user-info">
        <div class="user-avatar"></div>
        <div class="user-text">
            <h3>Selamat bekerja</h3>
            <p><?= htmlspecialchars($this->session->userdata('username') ?? 'username'); ?></p>
        </div>
    </div>
    <a href="<?= site_url('auth/logout') ?>" class="logout-btn"
        onclick="return confirm('Apakah anda yakin ingin keluar?');">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        Logout
    </a>
</div>

<!-- Tombol Tambah dan Pencarian Cepat -->
<div class="action-bar">
    <a href="<?= site_url('arsip/tambah') ?>" class="btn-tambah">+ Tambah Arsip Baru</a>
    <form action="<?= site_url('dashboard') ?>" method="GET" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Cari"
            value="<?= htmlspecialchars($search ?? '') ?>" required>
        <button type="submit" class="btn-cari">Cari</button>
    </form>
</div>

<!-- Kartu Ringkasan Jumlah Arsip per Kelurahan -->
<div class="kelurahan-grid">
    <?php foreach ($kelurahans as $kel): ?>
        <?php
            $class = strtolower(str_replace(' ', '', $kel));
        ?>
        <a href="<?= site_url('arsip?kelurahan=' . urlencode($kel)) ?>" class="kelurahan-card <?= $class ?>">
            <h4><?= $kel ?></h4>
            <p><?= $counts[$kel] ?> Arsip</p>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($this->session->userdata('role') === 'koor'): ?>
<!-- Bagian Grafik Arsip per Kecamatan -->
<div style="margin-top: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 20px; flex-wrap: wrap;">
        <h3 style="font-size: 1.2rem; font-weight: 600; color: #000; margin: 0;">Grafik Arsip per Kelurahan</h3>

        <div class="filter-bar">
            <label for="timeframe" style="color:#666; font-size:0.95rem; margin-right:8px;">Periode:</label>
            <select id="timeframe" name="timeframe" style="padding:8px 10px; border-radius:6px; border:1px solid #dcdcdc; background:#fff;">
                <option value="all" <?= isset($timeframe) && $timeframe === 'all' ? 'selected' : '' ?>>Semua</option>
                <option value="1day" <?= isset($timeframe) && $timeframe === '1day' ? 'selected' : '' ?>>1 Hari</option>
                <option value="7days" <?= isset($timeframe) && $timeframe === '7days' ? 'selected' : '' ?>>1 Minggu</option>
                <option value="1month" <?= isset($timeframe) && $timeframe === '1month' ? 'selected' : '' ?>>1 Bulan</option>
                <option value="1year" <?= isset($timeframe) && $timeframe === '1year' ? 'selected' : '' ?>>1 Tahun</option>
                
            </select>
        </div>
    </div>

    <div class="chart-wrapper" style="background-color: #fff; border: 1px solid #dcdcdc; border-radius: 12px; padding: 20px 20px 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); min-height:260px; max-height:320px;">
        <canvas id="dashboardChart" style="display:block; width:100%; height:260px; min-height:240px;"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('dashboardChart');
            const timeframeSelect = document.getElementById('timeframe');
            let chartInstance = null;

            function loadChart() {
                const timeframe = timeframeSelect ? timeframeSelect.value : 'all';
                const url = '<?= site_url('dashboard/ajax_summary_counts') ?>?timeframe=' + encodeURIComponent(timeframe);

                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        const labels = json.labels || [];
                        const data = json.data || [];
                        renderChart(labels, data);
                    })
                    .catch(err => {
                        console.error('Error memuat chart:', err);
                    });
            }

            function renderChart(labels, data) {
                if (!chartCanvas || typeof Chart === 'undefined') return;
                const maxVal = data.length ? Math.max.apply(null, data) : 1;

                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }

                chartInstance = new Chart(chartCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Arsip',
                            data: data,
                            backgroundColor: ['#0c7a5c', '#198754', '#20c997', '#2ecc71'],
                            borderRadius: 0,
                            borderSkipped: false,
                            barThickness: 40,
                            maxBarThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 8,
                                right: 8,
                                top: 8,
                                bottom: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#333',
                                    stepSize: 1,
                                    callback: function(value) { return Number.isInteger(value) ? value : ''; }
                                },
                                suggestedMax: Math.max(1, maxVal),
                                grid: { color: '#f0f0f0' }
                            },
                            x: {
                                ticks: { color: '#333' },
                                grid: { display: false },
                                maxRotation: 0,
                                minRotation: 0,
                                maxBarThickness: 28,
                                barPercentage: 0.25,
                                categoryPercentage: 0.35
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true }
                        }
                    }
                });
            }

            if (timeframeSelect) {
                timeframeSelect.addEventListener('change', loadChart);
            }

            loadChart();
        });
    </script>
</div>
<?php endif; ?>