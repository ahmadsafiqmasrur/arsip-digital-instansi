<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Dashboard
 * Menampilkan ringkasan statistik dan daftar arsip terbaru
 *
 * @property CI_Input $input
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property CI_Output $output
 * @property Arsip_model $Arsip_model
 * @property Editrequest_model $Editrequest_model
 */
class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model Arsip_model untuk menghitung statistik data
        $this->load->model('Arsip_model');
        $this->load->model('Editrequest_model');
    }

    /**
     * Menampilkan halaman dashboard utama
     */
    public function index() {
        $search = $this->input->get('search');
        $filter = $this->input->get('filter') ?? 'all';
        
        // Daftar kelurahan untuk dihitung statistiknya
        $kelurahans = ['Canden', 'Patalan', 'Sumberagung', 'Trimulyo'];
        $counts = [];

        // compute range from filter param
        $filterStart = null;
        $filterEnd = null;
        if ($filter == '1day') {
            $filterStart = date('Y-m-d H:i:s', strtotime('-1 day'));
            $filterEnd = date('Y-m-d H:i:s');
        } elseif ($filter == '7days') {
            $filterStart = date('Y-m-d H:i:s', strtotime('-7 days'));
            $filterEnd = date('Y-m-d H:i:s');
        } elseif ($filter == '1month') {
            $filterStart = date('Y-m-d H:i:s', strtotime('-1 month'));
            $filterEnd = date('Y-m-d H:i:s');
        }

        // available years for dropdown (range from earliest to latest record)
        $yearBoundsQ = $this->db->select('MIN(YEAR(created_at)) as min_y, MAX(YEAR(created_at)) as max_y')->get('arsip');
        $yearBounds = $yearBoundsQ->row_array();
        $minYear = !empty($yearBounds['min_y']) ? intval($yearBounds['min_y']) : intval(date('Y'));
        $maxYear = !empty($yearBounds['max_y']) ? intval($yearBounds['max_y']) : intval(date('Y'));
        $available_years = [];
        if ($minYear <= $maxYear) {
            for ($y = $minYear; $y <= $maxYear; $y++) {
                $available_years[] = $y;
            }
        }

        // timeframe filter for chart: '1day', '7days', '1month', 'all', 'year'
        // NOTE: kartu kelurahan akan selalu menampilkan TOTAL (tanpa filter)
        $timeframe = $this->input->get('timeframe') ?? 'all';
        $selectedYear = $this->input->get('year') ?? 'all';

        $finalStart = null;
        $finalEnd = null;
        if ($timeframe === '1day') {
            $finalStart = date('Y-m-d H:i:s', strtotime('-1 day'));
            $finalEnd = date('Y-m-d H:i:s');
        } elseif ($timeframe === '7days') {
            $finalStart = date('Y-m-d H:i:s', strtotime('-7 days'));
            $finalEnd = date('Y-m-d H:i:s');
        } elseif ($timeframe === '1month') {
            $finalStart = date('Y-m-d H:i:s', strtotime('-1 month'));
            $finalEnd = date('Y-m-d H:i:s');
        } elseif ($timeframe === '1year') {
            $finalStart = date('Y-m-d H:i:s', strtotime('-1 year'));
            $finalEnd = date('Y-m-d H:i:s');
        } else {
            $finalStart = null;
            $finalEnd = null;
        }

        // Hitung jumlah arsip per kelurahan untuk KARTU (TOTAL tanpa filter)
        $counts_total = [];
        foreach ($kelurahans as $kel) {
            try {
                $counts_total[$kel] = $this->Arsip_model->get_count_by_kelurahan($kel);
            } catch (Exception $e) {
                log_message('error', 'Dashboard::index get_count_by_kelurahan total error: ' . $e->getMessage());
                $counts_total[$kel] = 0;
            }
        }

        // Hitung jumlah arsip per kelurahan dengan filter waktu untuk CHART
        $counts_filtered = [];
        foreach ($kelurahans as $kel) {
            try {
                $counts_filtered[$kel] = $this->Arsip_model->get_count_by_kelurahan($kel, $finalStart, $finalEnd);
            } catch (Exception $e) {
                log_message('error', 'Dashboard::index get_count_by_kelurahan filtered error: ' . $e->getMessage());
                $counts_filtered[$kel] = 0;
            }
        }

        // Pengurutan data terbaru berdasarkan tanggal dibuat
        $this->db->order_by('created_at', 'DESC');
        
        // Logika pencarian global di dashboard (mencari ke berbagai kolom)
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('no_arsip', $search);
            $this->db->or_like('no_akta', $search);
            $this->db->or_like('nama_suami', $search);
            $this->db->or_like('nama_istri', $search);
            $this->db->or_like('nama_saksi', $search);
            $this->db->or_like('nik_suami', $search);
            $this->db->or_like('nik_istri', $search);
            $this->db->or_like('nama_penghulu', $search);
            $this->db->or_like('alamat_suami', $search);
            $this->db->or_like('alamat_istri', $search);
            $this->db->group_end();
        }

        // Logika filter rentang waktu
        if ($filter == '1day') {
            $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 day')));
        } elseif ($filter == '7days') {
            $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')));
        } elseif ($filter == '1month') {
            $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 month')));
        }

        // Apply finalStart/finalEnd to results query as well
        if (!empty($finalStart)) {
            $this->db->where('created_at >=', $finalStart);
        }
        if (!empty($finalEnd)) {
            $this->db->where('created_at <', $finalEnd);
        }

        // Eksekusi query untuk mendapatkan hasil daftar terbaru
        $results = $this->db->get('arsip')->result_array();

        $data = [
            'title' => 'Dashboard',
            'search' => $search,
            'filter' => $filter,
            'kelurahans' => $kelurahans,
            'counts' => $counts_total,
            'results' => $results,
            'chart_labels' => $kelurahans,
            'chart_values' => array_values($counts_total),
            'available_years' => $available_years,
            'timeframe' => $timeframe,
            'selected_year' => $selectedYear
        ];

        if ($this->session->userdata('role') !== 'koor') {
            $user_id = $this->session->userdata('user_id');
            $data['my_requests'] = $this->Editrequest_model->get_requests_by_petugas($user_id);
        }

        $this->render('dashboard/index', $data);
    }

    /**
     * Endpoint JSON untuk Chart data
     * Params: timeframe (1day|7days|1month|all|year), granularity (day|week|month|year), year (optional)
     */
    public function ajax_chart_data() {
        $timeframe = $this->input->get('timeframe') ?? '1month';
        $granularity = $this->input->get('granularity') ?? 'day';
        $year = $this->input->get('year');

        $start = null;
        $end = date('Y-m-d H:i:s');
        if ($timeframe === '1day') {
            $start = date('Y-m-d H:i:s', strtotime('-1 day'));
        } elseif ($timeframe === '7days') {
            $start = date('Y-m-d H:i:s', strtotime('-6 days'));
        } elseif ($timeframe === '1month') {
            $start = date('Y-m-d H:i:s', strtotime('-1 month'));
        } elseif ($timeframe === 'year' && !empty($year)) {
            $start = $year . '-01-01 00:00:00';
            $end = ($year + 1) . '-01-01 00:00:00';
        } else {
            // all: compute min/max from DB
            $bounds = $this->db->select('MIN(created_at) as min_d, MAX(created_at) as max_d')->get('arsip')->row_array();
            if (!empty($bounds['min_d'])) {
                $start = $bounds['min_d'];
            } else {
                $start = date('Y-01-01 00:00:00');
            }
            if (!empty($bounds['max_d'])) {
                $end = date('Y-m-d H:i:s', strtotime($bounds['max_d'] . ' +1 day'));
            }
        }

        // Determine group key for model
        $group = $granularity;

        $series = $this->Arsip_model->get_time_series_counts(null, $start, $end, $group);

        // Build contiguous labels between start and end based on granularity
        $labels = [];
        $data = [];

        $dtStart = new DateTime($start);
        $dtEnd = new DateTime($end);

        $intervalSpec = 'P1D';
        if ($group === 'week') {
            $intervalSpec = 'P7D';
        } elseif ($group === 'month') {
            $intervalSpec = 'P1M';
        } elseif ($group === 'year') {
            $intervalSpec = 'P1Y';
        }

        $period = new DatePeriod($dtStart, new DateInterval($intervalSpec), $dtEnd);
        foreach ($period as $dt) {
            if ($group === 'day') {
                $key = $dt->format('Y-m-d');
                $label = $dt->format('d M');
            } elseif ($group === 'week') {
                $weekNum = $dt->format('o') . '-W' . $dt->format('W');
                $key = $weekNum;
                $label = $dt->format('d M');
            } elseif ($group === 'month') {
                $key = $dt->format('Y-m');
                $label = $dt->format('M Y');
            } else { // year
                $key = $dt->format('Y');
                $label = $dt->format('Y');
            }
            $labels[] = $label;
            $data[] = isset($series[$key]) ? intval($series[$key]) : 0;
        }

        $out = ['labels' => $labels, 'data' => $data];
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    /**
     * Endpoint JSON ringkasan: jumlah arsip per kelurahan untuk timeframe tertentu.
     * Params: timeframe (1day|7days|1month|all|year), year (optional)
     */
    public function ajax_summary_counts() {
        $timeframe = $this->input->get('timeframe') ?? '1month';
        $kelurahanParam = $this->input->get('kelurahan');

        $rangeCondition = null;
        if ($timeframe === '1day') {
            $rangeCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        } elseif ($timeframe === '7days') {
            $rangeCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($timeframe === '1month') {
            $rangeCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        } elseif ($timeframe === '1year') {
            $rangeCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        }

        $kelurahans = ['Canden', 'Patalan', 'Sumberagung', 'Trimulyo'];
        $labels = [];
        $data = [];
        // If specific kelurahan requested, return only that
        if (!empty($kelurahanParam) && $kelurahanParam !== 'all') {
            $this->db->where('kelurahan', $kelurahanParam);
            if ($rangeCondition) {
                $this->db->where($rangeCondition, null, false);
            }
            $labels[] = $kelurahanParam;
            $data[] = intval($this->db->count_all_results('arsip'));
        } else {
            foreach ($kelurahans as $kel) {
                $this->db->where('kelurahan', $kel);
                if ($rangeCondition) {
                    $this->db->where($rangeCondition, null, false);
                }
                $labels[] = $kel;
                $data[] = intval($this->db->count_all_results('arsip'));
                $this->db->reset_query();
            }
        }

        $out = ['labels' => $labels, 'data' => $data];
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }
}
