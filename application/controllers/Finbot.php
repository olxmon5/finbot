<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller FinBot — AI Finance Assistant untuk UMKM
 * Framework: CodeIgniter 3
 * 
 * Fitur:
 * - Dashboard Hasil Isian Realtime & Data Sampel (Token-Based / Multi-Tenant)
 * - Natural Language Transaction Parser (Telegram & Web)
 * - Telegram Webhook & Magic Token Access
 * - Monetisasi Kuota & Upgrade Paket
 * - Soft Delete (deleted_at) pada seluruh mutasi
 */
class Finbot extends CI_Controller
{
    private $default_business_id = 1;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session']);
    }

    /**
     * Halaman Showcase / Landing Page Produk FinBot
     */
    public function index()
    {
        $data['title'] = "FinBot — Kelola Keuangan Lebih Mudah dengan AI Assistant";
        $this->load->view('finbot/showcase', $data);
    }

    /**
     * Halaman Utama / Dashboard Hasil Isian FinBot
     * Jika token ada: load data user/bisnis tersebut.
     * Jika tanpa token: tampilkan data sampel (Demo).
     */
    public function dashboard()
    {
        $token_input = $this->input->get('token');
        if ($token_input === 'sample' || $token_input === 'demo' || $token_input === 'clear') {
            $this->session->unset_userdata('finbot_view_token');
            $token = null;
        } else {
            $token = $token_input ?: $this->session->userdata('finbot_view_token');
        }

        $business = $this->resolve_business_by_token($token);

        if ($business) {
            $is_sample = false;
            $active_token = $business->view_token ?: ('FB-' . $business->id);
            $business_id = (int) $business->id;
            $this->session->set_userdata('finbot_view_token', $active_token);
            $plan_info = $this->check_transaction_quota($business_id);
            $accounts = $this->db->where('business_id', $business_id)->where('deleted_at', NULL)->get('finbot_accounts')->result();
        } else {
            $is_sample = true;
            $active_token = '';
            $this->session->unset_userdata('finbot_view_token');
            $business = (object) [
                'id' => 0,
                'name' => 'Warung Berkah Nusantara (Sampel Demo)',
                'business_type' => 'UMKM Retail / Sembako',
                'view_token' => ''
            ];
            $plan_info = [
                'plan' => 'free',
                'plan_name' => 'FinBot Free (Demo)',
                'limit' => 50,
                'used' => 18,
                'remaining' => 32,
                'allowed' => true,
                'percentage' => 36,
                'expires_at' => null
            ];
            $accounts = [
                (object) ['id' => 1, 'name' => 'Kas Tunai (Cash)', 'balance' => 8450000],
                (object) ['id' => 2, 'name' => 'Bank BCA Bisnis', 'balance' => 4050000]
            ];
        }

        $data['title'] = "FinBot — Dashboard Hasil Isian";
        $data['is_sample'] = $is_sample;
        $data['token'] = $active_token;
        $data['business'] = $business;
        $data['accounts'] = $accounts;
        $data['plan_info'] = $plan_info;

        $this->load->view('finbot/index', $data);
    }

    /**
     * Helper untuk memetakan token ke data bisnis di database
     */
    private function resolve_business_by_token($token)
    {
        if (empty($token)) {
            return null;
        }

        $token = trim((string) $token);
        if ($token === '' || in_array(strtolower($token), ['sample', 'demo', 'null', 'undefined', 'clear'])) {
            return null;
        }

        // 1. Cek berdasarkan view_token pada finbot_businesses (Case-insensitive)
        $biz = $this->db->where('LOWER(view_token)', strtolower($token))
            ->where('deleted_at', NULL)
            ->get('finbot_businesses')
            ->row();

        if ($biz) {
            return $biz;
        }

        // 2. Cek apakah format ID numerik (misal token=1 atau token=2)
        if (is_numeric($token)) {
            $biz = $this->db->where('id', (int) $token)
                ->where('deleted_at', NULL)
                ->get('finbot_businesses')
                ->row();
            if ($biz) {
                return $biz;
            }
        }

        // 3. Cek format prefix BIZ-X, FB-X, TK-X jika X numerik (misal token=BIZ-1 atau FB-2)
        if (preg_match('/^(?:BIZ|FB|TK|USER)-([0-9]+)$/i', $token, $matches)) {
            $biz = $this->db->where('id', (int) $matches[1])
                ->where('deleted_at', NULL)
                ->get('finbot_businesses')
                ->row();
            if ($biz) {
                return $biz;
            }
        }

        // 4. Cek auth magic token pada finbot_auth_tokens
        $auth = $this->db->where('token', $token)
            ->where('deleted_at', NULL)
            ->get('finbot_auth_tokens')
            ->row();

        if ($auth) {
            $biz = $this->db->where('id', $auth->business_id)
                ->where('deleted_at', NULL)
                ->get('finbot_businesses')
                ->row();
            if ($biz) {
                return $biz;
            }
        }

        return null;
    }

    /**
     * Helper: Dapatkan ID Akun Kas Default yang Valid untuk Bisnis
     */
    private function get_business_default_account_id($business_id, $preferred_id = null)
    {
        if (!empty($preferred_id)) {
            $acc = $this->db->where('id', (int) $preferred_id)
                ->where('business_id', $business_id)
                ->where('deleted_at', NULL)
                ->get('finbot_accounts')
                ->row();
            if ($acc) {
                return (int) $acc->id;
            }
        }

        $def_acc = $this->db->where('business_id', $business_id)
            ->where('is_default', 1)
            ->where('deleted_at', NULL)
            ->get('finbot_accounts')
            ->row();

        if ($def_acc) {
            return (int) $def_acc->id;
        }

        $any_acc = $this->db->where('business_id', $business_id)
            ->where('deleted_at', NULL)
            ->order_by('id', 'ASC')
            ->get('finbot_accounts')
            ->row();

        if ($any_acc) {
            return (int) $any_acc->id;
        }

        // Jika bisnis belum memiliki akun kas sama sekali, buatkan akun kas default
        $this->db->insert('finbot_accounts', [
            'business_id' => $business_id,
            'name' => 'Kas Tunai (Cash)',
            'type' => 'cash',
            'balance' => 0.00,
            'is_default' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return (int) $this->db->insert_id();
    }

    /**
     * One-Time Magic Login Token dari Bot Telegram
     */
    public function connect()
    {
        $token = $this->input->get('token');
        if (empty($token)) {
            redirect(base_url('finbot/dashboard'));
            return;
        }

        $auth = $this->db->where('token', $token)
            ->where('used_at', NULL)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->get('finbot_auth_tokens')
            ->row();

        if (!$auth) {
            $this->session->set_flashdata('error', 'Tautan login tidak valid atau sudah kedaluwarsa.');
            redirect(base_url('finbot/dashboard'));
            return;
        }

        // Tandai token used
        $this->db->where('id', $auth->id)->update('finbot_auth_tokens', [
            'used_at' => date('Y-m-d H:i:s')
        ]);

        $biz = $this->db->where('id', $auth->business_id)->get('finbot_businesses')->row();
        $view_token = $biz && !empty($biz->view_token) ? $biz->view_token : $auth->business_id;

        $this->session->set_userdata([
            'user_id' => $auth->user_id,
            'business_id' => $auth->business_id,
            'finbot_view_token' => $view_token,
            'telegram_chat_id' => $auth->telegram_chat_id,
            'logged_in' => TRUE
        ]);

        redirect(base_url('finbot/dashboard?token=' . urlencode($view_token)));
    }

    /**
     * Endpoint API: Status Kuota & Paket
     */
    public function get_plan_info()
    {
        $token = $this->input->get('token') ?: ($this->input->post('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);

        if ($biz) {
            $quota = $this->check_transaction_quota($biz->id);
        } else {
            $quota = [
                'plan' => 'free',
                'plan_name' => 'FinBot Free (Demo)',
                'limit' => 50,
                'used' => 18,
                'remaining' => 32,
                'allowed' => true,
                'percentage' => 36,
                'expires_at' => null
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $quota
            ]));
    }

    /**
     * Endpoint API: Upgrade Paket FinBot
     */
    public function upgrade_plan()
    {
        $token = $this->input->post('token') ?: $this->session->userdata('finbot_view_token');
        $biz = $this->resolve_business_by_token($token);
        $business_id = $biz ? (int) $biz->id : 1;

        $plan = $this->input->post('plan'); // 'pro', 'pro_plus'
        $cycle = $this->input->post('cycle') ?: 'monthly';

        if (!in_array($plan, ['pro', 'pro_plus', 'business'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Pilihan paket tidak valid']));
        }

        $prices = [
            'pro' => ['monthly' => 9900, 'yearly' => 99000, 'limit' => 500, 'name' => 'FinBot Pro (Warung)'],
            'pro_plus' => ['monthly' => 19900, 'yearly' => 199000, 'limit' => 2000, 'name' => 'FinBot Pro+ (Usaha)'],
            'business' => ['monthly' => 49900, 'yearly' => 499000, 'limit' => 5000, 'name' => 'FinBot Business']
        ];

        $amount = $prices[$plan][$cycle] ?? $prices[$plan]['monthly'];
        $limit = $prices[$plan]['limit'];
        $days = ($cycle === 'yearly') ? 365 : 30;
        $expires = date('Y-m-d H:i:s', strtotime("+$days days"));

        if ($biz) {
            $this->db->where('id', $business_id)->update('finbot_businesses', [
                'plan' => $plan,
                'plan_billing' => $cycle,
                'monthly_limit' => $limit,
                'plan_expires_at' => $expires
            ]);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => "Paket berhasil ditingkatkan ke " . $prices[$plan]['name'] . "!",
                'plan' => $plan,
                'limit' => $limit,
                'expires_at' => $expires
            ]));
    }

    /**
     * Helper: Cek Batas Kuota Transaksi Bulanan
     */
    private function check_transaction_quota($business_id)
    {
        $biz = $this->db->where('id', $business_id)->get('finbot_businesses')->row();
        $plan = $biz && !empty($biz->plan) ? $biz->plan : 'free';
        $limit = $biz && !empty($biz->monthly_limit) ? (int) $biz->monthly_limit : 50;

        if ($plan === 'free') {
            $limit = 50;
        } elseif ($plan === 'pro') {
            $limit = 500;
        } elseif ($plan === 'pro_plus') {
            $limit = 2000;
        } elseif ($plan === 'business') {
            $limit = 5000;
        }

        $start_month = date('Y-m-01');
        $end_month = date('Y-m-t');

        $used = $this->db->where('business_id', $business_id)
            ->where('transaction_date >=', $start_month)
            ->where('transaction_date <=', $end_month)
            ->where('deleted_at', NULL)
            ->count_all_results('finbot_transactions');

        $remaining = max(0, $limit - $used);
        $allowed = ($used < $limit);

        return [
            'plan' => $plan,
            'plan_name' => ($plan === 'pro') ? 'FinBot Pro (Rp9.900/bln)' : (($plan === 'pro_plus') ? 'FinBot Pro+ (Rp19.900/bln)' : (($plan === 'business') ? 'FinBot Business' : 'FinBot Free (Rp0)')),
            'limit' => $limit,
            'used' => $used,
            'remaining' => $remaining,
            'allowed' => $allowed,
            'percentage' => min(100, round(($used / $limit) * 100)),
            'expires_at' => $biz->plan_expires_at ?? null
        ];
    }

    /**
     * Endpoint API: Ringkasan Finansial Dashboard (Summary Cards, Donut Category, Cashflow Trend)
     */
    public function get_summary()
    {
        $token = $this->input->get('token') ?: ($this->input->post('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);
        $filter_period = $this->input->get('period') ?: 'this_month';

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        if ($filter_period === 'today') {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        } elseif ($filter_period === 'this_week') {
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($filter_period === 'last_month') {
            $start_date = date('Y-m-01', strtotime('first day of last month'));
            $end_date = date('Y-m-t', strtotime('last day of last month'));
        } elseif ($filter_period === 'this_year') {
            $start_date = date('Y-01-01');
            $end_date = date('Y-12-31');
        } elseif ($filter_period === 'all' || $filter_period === 'all_time') {
            $start_date = '2000-01-01';
            $end_date = date('Y-m-d');
        } elseif ($filter_period === 'custom') {
            $start_date = $this->input->get('start_date') ?: $start_date;
            $end_date = $this->input->get('end_date') ?: $end_date;
        }

        // JIKA TANPA TOKEN / DATA SAMPEL DEMO
        if (!$biz) {
            $sample_data = $this->generate_sample_summary($filter_period, $start_date, $end_date);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($sample_data));
        }

        $business_id = (int) $biz->id;

        // 1. Total Pemasukan
        $income_row = $this->db->select_sum('amount', 'total')
            ->where('business_id', $business_id)
            ->where('type', 'income')
            ->where('transaction_date >=', $start_date)
            ->where('transaction_date <=', $end_date)
            ->where('deleted_at', NULL)
            ->get('finbot_transactions')
            ->row();
        $total_income = $income_row ? (float) $income_row->total : 0;

        // 2. Total Pengeluaran
        $expense_row = $this->db->select_sum('amount', 'total')
            ->where('business_id', $business_id)
            ->where('type', 'expense')
            ->where('transaction_date >=', $start_date)
            ->where('transaction_date <=', $end_date)
            ->where('deleted_at', NULL)
            ->get('finbot_transactions')
            ->row();
        $total_expense = $expense_row ? (float) $expense_row->total : 0;

        // 3. Net Cashflow
        $net_cashflow = $total_income - $total_expense;

        // 4. Saldo Semua Akun
        $accounts = $this->db->where('business_id', $business_id)
            ->where('deleted_at', NULL)
            ->get('finbot_accounts')
            ->result();
        $total_balance = 0;
        foreach ($accounts as $acc) {
            $total_balance += (float) $acc->balance;
        }

        // 5. Kategori Pengeluaran (Donut Chart)
        $expense_categories = $this->db->select('category_name, SUM(amount) as total')
            ->where('business_id', $business_id)
            ->where('type', 'expense')
            ->where('transaction_date >=', $start_date)
            ->where('transaction_date <=', $end_date)
            ->where('deleted_at', NULL)
            ->group_by('category_name')
            ->order_by('total', 'DESC')
            ->get('finbot_transactions')
            ->result();

        // 6. Transaksi Terbaru
        $recent_transactions = $this->db->where('business_id', $business_id)
            ->where('deleted_at', NULL)
            ->order_by('transaction_date', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(10)
            ->get('finbot_transactions')
            ->result();

        // 7. Cashflow Trend
        $cashflow_trend = $this->get_cashflow_chart_data($business_id, $start_date, $end_date);

        $response = [
            'status' => 'success',
            'is_sample' => false,
            'business_name' => $biz->name,
            'period' => [
                'type' => $filter_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'label' => date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date))
            ],
            'summary' => [
                'total_income' => $total_income,
                'total_expense' => $total_expense,
                'net_cashflow' => $net_cashflow,
                'total_balance' => $total_balance
            ],
            'accounts' => $accounts,
            'expense_categories' => $expense_categories,
            'recent_transactions' => $recent_transactions,
            'cashflow_trend' => $cashflow_trend
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Helper Generator Data Sampel Demo
     */
    private function generate_sample_summary($filter_period, $start_date, $end_date)
    {
        $today = date('Y-m-d');
        $y1 = date('Y-m-d', strtotime('-1 day'));
        $y2 = date('Y-m-d', strtotime('-2 days'));
        $y3 = date('Y-m-d', strtotime('-3 days'));

        $sample_categories = [
            (object) ['category_name' => 'Stok Barang & Kulakan', 'total' => 4750000],
            (object) ['category_name' => 'Operasional & Plastik', 'total' => 1350000],
            (object) ['category_name' => 'Makan & Minum Tim', 'total' => 980000],
            (object) ['category_name' => 'Transportasi & Bensin', 'total' => 640000],
            (object) ['category_name' => 'Listrik PLN & Wifi', 'total' => 450000],
            (object) ['category_name' => 'Pengeluaran Lainnya', 'total' => 250000]
        ];

        $sample_recent = [
            (object) [
                'id' => 101,
                'transaction_date' => $today,
                'type' => 'income',
                'amount' => 1450000,
                'category_name' => 'Penjualan Toko',
                'description' => 'Omset penjualan sembako & retail siang',
                'source' => 'telegram',
                'raw_message' => 'masuk 1.45jt jualan siang ini'
            ],
            (object) [
                'id' => 102,
                'transaction_date' => $today,
                'type' => 'expense',
                'amount' => 750000,
                'category_name' => 'Stok Barang & Kulakan',
                'description' => 'Kulakan beras 2 karung & minyak goreng',
                'source' => 'telegram',
                'raw_message' => 'keluar 750rb buat kulak beras minyak'
            ],
            (object) [
                'id' => 103,
                'transaction_date' => $today,
                'type' => 'expense',
                'amount' => 50000,
                'category_name' => 'Transportasi & Bensin',
                'description' => 'Bensin motor operasional toko',
                'source' => 'telegram',
                'raw_message' => '50rb bensin motor'
            ],
            (object) [
                'id' => 104,
                'transaction_date' => $y1,
                'type' => 'income',
                'amount' => 2800000,
                'category_name' => 'Penjualan Toko',
                'description' => 'Pesanan katering kantor & retail',
                'source' => 'telegram',
                'raw_message' => 'masuk 2.8jt orderan catering'
            ],
            (object) [
                'id' => 105,
                'transaction_date' => $y1,
                'type' => 'expense',
                'amount' => 120000,
                'category_name' => 'Makan & Minum Tim',
                'description' => 'Makan siang karyawan toko',
                'source' => 'web',
                'raw_message' => null
            ],
            (object) [
                'id' => 106,
                'transaction_date' => $y2,
                'type' => 'expense',
                'amount' => 450000,
                'category_name' => 'Listrik PLN & Wifi',
                'description' => 'Beli token listrik toko 450rb',
                'source' => 'telegram',
                'raw_message' => 'listrik 450rb'
            ],
            (object) [
                'id' => 107,
                'transaction_date' => $y2,
                'type' => 'income',
                'amount' => 3100000,
                'category_name' => 'Penjualan Toko',
                'description' => 'Penjualan grosir sembako pelanggan tetap',
                'source' => 'telegram',
                'raw_message' => 'masuk 3.1jt grosir sembako'
            ]
        ];

        // 7 Days Chart Data
        $chart_labels = [];
        $chart_income = [];
        $chart_expense = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('d M', strtotime("-$i days"));
            $chart_labels[] = $d;
            $chart_income[] = rand(12, 35) * 100000;
            $chart_expense[] = rand(5, 20) * 100000;
        }

        return [
            'status' => 'success',
            'is_sample' => true,
            'business_name' => 'Warung Berkah Nusantara (Sampel Demo)',
            'period' => [
                'type' => $filter_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'label' => date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date))
            ],
            'summary' => [
                'total_income' => 14850000,
                'total_expense' => 8420000,
                'net_cashflow' => 6430000,
                'total_balance' => 12500000
            ],
            'accounts' => [
                (object) ['id' => 1, 'name' => 'Kas Tunai (Cash)', 'balance' => 8450000],
                (object) ['id' => 2, 'name' => 'Bank BCA Bisnis', 'balance' => 4050000]
            ],
            'expense_categories' => $sample_categories,
            'recent_transactions' => $sample_recent,
            'cashflow_trend' => [
                'labels' => $chart_labels,
                'income' => $chart_income,
                'expense' => $chart_expense
            ]
        ];
    }

    private function get_cashflow_chart_data($business_id, $start_date, $end_date)
    {
        $labels = [];
        $income_map = [];
        $expense_map = [];

        // Rekap pemasukan per tanggal dalam range
        $income_rows = $this->db->select('transaction_date, SUM(amount) as total')
            ->where('business_id', $business_id)
            ->where('type', 'income')
            ->where('transaction_date >=', $start_date)
            ->where('transaction_date <=', $end_date)
            ->where('deleted_at', NULL)
            ->group_by('transaction_date')
            ->get('finbot_transactions')
            ->result();

        foreach ($income_rows as $r) {
            $income_map[$r->transaction_date] = (float) $r->total;
        }

        // Rekap pengeluaran per tanggal dalam range
        $expense_rows = $this->db->select('transaction_date, SUM(amount) as total')
            ->where('business_id', $business_id)
            ->where('type', 'expense')
            ->where('transaction_date >=', $start_date)
            ->where('transaction_date <=', $end_date)
            ->where('deleted_at', NULL)
            ->group_by('transaction_date')
            ->get('finbot_transactions')
            ->result();

        foreach ($expense_rows as $r) {
            $expense_map[$r->transaction_date] = (float) $r->total;
        }

        $income_data = [];
        $expense_data = [];

        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            (new DateTime($end_date))->modify('+1 day')
        );

        foreach ($period as $date) {
            $d_str = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $income_data[] = isset($income_map[$d_str]) ? $income_map[$d_str] : 0;
            $expense_data[] = isset($expense_map[$d_str]) ? $expense_map[$d_str] : 0;
        }

        return [
            'labels' => $labels,
            'income' => $income_data,
            'expense' => $expense_data
        ];
    }

    /**
     * Endpoint API: Ambil List Seluruh Transaksi
     */
    public function get_transactions()
    {
        $token = $this->input->get('token') ?: ($this->input->post('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);

        $type = $this->input->get('type');
        $category_name = $this->input->get('category');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $search = $this->input->get('search');
        $limit = $this->input->get('limit') ? (int) $this->input->get('limit') : 50;
        $offset = $this->input->get('offset') ? (int) $this->input->get('offset') : 0;

        // JIKA DATA SAMPEL DEMO
        if (!$biz) {
            $sample_list = [
                (object) ['id' => 1, 'transaction_date' => date('Y-m-d'), 'type' => 'income', 'amount' => 1450000, 'category_name' => 'Penjualan', 'description' => 'Penjualan sembako & retail kasir', 'source' => 'telegram', 'raw_message' => 'masuk 1.45jt jualan siang ini'],
                (object) ['id' => 2, 'transaction_date' => date('Y-m-d'), 'type' => 'expense', 'amount' => 750000, 'category_name' => 'Stok Barang', 'description' => 'Kulakan beras 2 karung & minyak', 'source' => 'telegram', 'raw_message' => 'keluar 750rb buat kulak beras minyak'],
                (object) ['id' => 3, 'transaction_date' => date('Y-m-d'), 'type' => 'expense', 'amount' => 50000, 'category_name' => 'Transportasi', 'description' => 'Bensin motor operasional toko', 'source' => 'telegram', 'raw_message' => '50rb bensin motor'],
                (object) ['id' => 4, 'transaction_date' => date('Y-m-d', strtotime('-1 day')), 'type' => 'income', 'amount' => 2800000, 'category_name' => 'Penjualan', 'description' => 'Pesanan katering & supply toko', 'source' => 'telegram', 'raw_message' => 'masuk 2.8jt orderan catering'],
                (object) ['id' => 5, 'transaction_date' => date('Y-m-d', strtotime('-1 day')), 'type' => 'expense', 'amount' => 120000, 'category_name' => 'Makan & Minum', 'description' => 'Makan siang karyawan toko', 'source' => 'web', 'raw_message' => null],
                (object) ['id' => 6, 'transaction_date' => date('Y-m-d', strtotime('-2 days')), 'type' => 'expense', 'amount' => 450000, 'category_name' => 'Listrik & Air', 'description' => 'Beli token listrik PLN toko', 'source' => 'telegram', 'raw_message' => 'listrik 450rb'],
                (object) ['id' => 7, 'transaction_date' => date('Y-m-d', strtotime('-2 days')), 'type' => 'income', 'amount' => 3100000, 'category_name' => 'Penjualan', 'description' => 'Penjualan grosir sembako', 'source' => 'telegram', 'raw_message' => 'masuk 3.1jt grosir sembako'],
                (object) ['id' => 8, 'transaction_date' => date('Y-m-d', strtotime('-3 days')), 'type' => 'expense', 'amount' => 1800000, 'category_name' => 'Stok Barang', 'description' => 'Restock aneka minuman dingin & snack', 'source' => 'telegram', 'raw_message' => 'kulak snack minuman 1.8jt'],
                (object) ['id' => 9, 'transaction_date' => date('Y-m-d', strtotime('-4 days')), 'type' => 'income', 'amount' => 2250000, 'category_name' => 'Penjualan', 'description' => 'Omset penjualan harian', 'source' => 'telegram', 'raw_message' => 'jual 2.25jt'],
                (object) ['id' => 10, 'transaction_date' => date('Y-m-d', strtotime('-5 days')), 'type' => 'expense', 'amount' => 1500000, 'category_name' => 'Gaji', 'description' => 'Uang makan & upah mingguan kasir', 'source' => 'web', 'raw_message' => null]
            ];

            // Filter sample in-memory
            $filtered = [];
            foreach ($sample_list as $row) {
                if (!empty($type) && $row->type !== $type)
                    continue;
                if (!empty($category_name) && $row->category_name !== $category_name)
                    continue;
                if (!empty($search) && stripos($row->description, $search) === false && stripos($row->category_name, $search) === false)
                    continue;
                $filtered[] = $row;
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'is_sample' => true,
                    'total' => count($filtered),
                    'data' => $filtered
                ]));
        }

        // DATABASE LIVE QUERY
        $business_id = (int) $biz->id;
        $this->db->where('business_id', $business_id)
            ->where('deleted_at', NULL);

        if (!empty($type) && in_array($type, ['income', 'expense'])) {
            $this->db->where('type', $type);
        }
        if (!empty($category_name)) {
            $this->db->where('category_name', $category_name);
        }
        if (!empty($start_date)) {
            $this->db->where('transaction_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('transaction_date <=', $end_date);
        }
        if (!empty($search)) {
            $this->db->group_start()
                ->like('description', $search)
                ->or_like('category_name', $search)
                ->or_like('raw_message', $search)
                ->group_end();
        }

        $clone_db = clone $this->db;
        $total_rows = $clone_db->count_all_results('finbot_transactions');

        $this->db->order_by('transaction_date', 'DESC')
            ->order_by('id', 'DESC')
            ->limit($limit, $offset);

        $transactions = $this->db->get('finbot_transactions')->result();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'is_sample' => false,
                'total' => $total_rows,
                'data' => $transactions
            ]));
    }

    /**
     * Endpoint API: Detail Transaksi By ID
     */
    public function get_transaction_by_id($id)
    {
        $token = $this->input->get('token') ?: ($this->input->post('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);

        if (!$biz) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'data' => (object) [
                        'id' => $id,
                        'type' => 'expense',
                        'amount' => 750000,
                        'category_name' => 'Stok Barang',
                        'description' => 'Kulakan beras 2 karung & minyak',
                        'transaction_date' => date('Y-m-d'),
                        'account_id' => 1
                    ]
                ]));
        }

        $tx = $this->db->where('id', $id)
            ->where('business_id', $biz->id)
            ->where('deleted_at', NULL)
            ->get('finbot_transactions')
            ->row();

        if (!$tx) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success', 'data' => $tx]));
    }

    /**
     * Endpoint API: Simpan Transaksi Baru
     */
    public function save_transactions()
    {
        $raw_json = file_get_contents('php://input');
        $payload = json_decode($raw_json, true);

        if (!$payload) {
            $payload = $this->input->post();
        }

        $token = isset($payload['token']) ? $payload['token'] : ($this->input->get('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);

        $transactions = isset($payload['transactions']) ? $payload['transactions'] : [$payload];
        $source = isset($payload['source']) ? $payload['source'] : 'web';
        $raw_message = isset($payload['raw_message']) ? $payload['raw_message'] : null;

        if (empty($transactions)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Tidak ada data transaksi yang dikirim']));
        }

        // Mode Demo (Tanpa Token): Berikan respon simulasi sukses
        if (!$biz) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'is_sample' => true,
                    'message' => 'Transaksi berhasil dicatat dalam Mode Simulasi Sampel! (Gunakan token untuk menyimpan ke database tokomu)'
                ]));
        }

        $business_id = (int) $biz->id;

        // Cek Kuota
        $quota = $this->check_transaction_quota($business_id);
        if (!$quota['allowed']) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'quota_exceeded',
                    'message' => "Batas kuota bulanan (" . $quota['limit'] . " transaksi) untuk paket " . $quota['plan_name'] . " telah tercapai. Silakan upgrade paket untuk melanjutkan.",
                    'quota' => $quota
                ]));
        }

        $this->db->trans_start();

        $saved_ids = [];
        foreach ($transactions as $tx) {
            if (empty($tx['amount']) || empty($tx['type']))
                continue;

            $amount = (float) $tx['amount'];
            $type = in_array($tx['type'], ['income', 'expense']) ? $tx['type'] : 'expense';
            $cat_name = !empty($tx['category_name']) ? trim($tx['category_name']) : 'Lainnya';
            $desc = !empty($tx['description']) ? trim($tx['description']) : $cat_name;
            $acc_id = $this->get_business_default_account_id($business_id, !empty($tx['account_id']) ? $tx['account_id'] : null);
            $tx_date = !empty($tx['transaction_date']) ? $tx['transaction_date'] : date('Y-m-d');
            $confidence = isset($tx['confidence']) ? (float) $tx['confidence'] : 1.00;

            $insert_data = [
                'business_id' => $business_id,
                'account_id' => $acc_id,
                'type' => $type,
                'amount' => $amount,
                'category_name' => $cat_name,
                'description' => $desc,
                'transaction_date' => $tx_date,
                'source' => $source,
                'raw_message' => $raw_message,
                'confidence' => $confidence,
                'is_confirmed' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('finbot_transactions', $insert_data);
            $new_id = $this->db->insert_id();
            $saved_ids[] = $new_id;

            // Mutasi saldo akun
            if ($acc_id) {
                if ($type === 'income') {
                    $this->db->set('balance', 'balance + ' . $amount, FALSE);
                } else {
                    $this->db->set('balance', 'balance - ' . $amount, FALSE);
                }
                $this->db->where('id', $acc_id)->where('business_id', $business_id)->update('finbot_accounts');
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal menyimpan transaksi ke database']));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => count($saved_ids) . ' transaksi berhasil disimpan!',
                'saved_ids' => $saved_ids
            ]));
    }

    /**
     * Endpoint API: Update Transaksi
     */
    public function update_transaction()
    {
        $id = $this->input->post('id');
        $token = $this->input->post('token') ?: ($this->input->get('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);

        if (!$biz) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Perubahan transaksi berhasil disimpan (Mode Simulasi Sampel)!']));
        }

        $business_id = (int) $biz->id;
        $old_tx = $this->db->where('id', $id)
            ->where('business_id', $business_id)
            ->where('deleted_at', NULL)
            ->get('finbot_transactions')
            ->row();

        if (!$old_tx) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']));
        }

        $type = $this->input->post('type');
        $amount = (float) $this->input->post('amount');
        $category_name = $this->input->post('category_name');
        $description = $this->input->post('description');
        $tx_date = $this->input->post('transaction_date');
        $account_id = $this->get_business_default_account_id($business_id, $this->input->post('account_id') ?: $old_tx->account_id);

        $update_data = [
            'type' => $type,
            'amount' => $amount,
            'category_name' => $category_name,
            'description' => $description,
            'transaction_date' => $tx_date,
            'account_id' => $account_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_start();

        // Rollback saldo lama
        if ($old_tx->account_id) {
            if ($old_tx->type === 'income') {
                $this->db->set('balance', 'balance - ' . $old_tx->amount, FALSE);
            } else {
                $this->db->set('balance', 'balance + ' . $old_tx->amount, FALSE);
            }
            $this->db->where('id', $old_tx->account_id)->where('business_id', $business_id)->update('finbot_accounts');
        }

        // Apply saldo baru
        if ($account_id) {
            if ($type === 'income') {
                $this->db->set('balance', 'balance + ' . $amount, FALSE);
            } else {
                $this->db->set('balance', 'balance - ' . $amount, FALSE);
            }
            $this->db->where('id', $account_id)->where('business_id', $business_id)->update('finbot_accounts');
        }

        $this->db->where('id', $id)->where('business_id', $business_id)->update('finbot_transactions', $update_data);
        $this->db->trans_complete();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success', 'message' => 'Transaksi berhasil diperbarui!']));
    }

    /**
     * Endpoint API: Soft Delete Transaksi (deleted_at)
     */
    public function delete_transaction()
    {
        $id = $this->input->post('id');
        $token = $this->input->post('token') ?: ($this->input->get('token') ?: $this->session->userdata('finbot_view_token'));
        $biz = $this->resolve_business_by_token($token);

        if (!$biz) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Transaksi berhasil dihapus (Mode Simulasi Sampel)!']));
        }

        $business_id = (int) $biz->id;
        $tx = $this->db->where('id', $id)
            ->where('business_id', $business_id)
            ->where('deleted_at', NULL)
            ->get('finbot_transactions')
            ->row();

        if (!$tx) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']));
        }

        $this->db->trans_start();

        // Rollback saldo akun
        if ($tx->account_id) {
            if ($tx->type === 'income') {
                $this->db->set('balance', 'balance - ' . $tx->amount, FALSE);
            } else {
                $this->db->set('balance', 'balance + ' . $tx->amount, FALSE);
            }
            $this->db->where('id', $tx->account_id)->where('business_id', $business_id)->update('finbot_accounts');
        }

        // Soft delete: tandai deleted_at
        $this->db->where('id', $id)->where('business_id', $business_id)->update('finbot_transactions', [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success', 'message' => 'Transaksi berhasil dihapus!']));
    }

    /**
     * Webhook Telegram API
     */
    public function telegram_webhook()
    {
        $content = file_get_contents("php://input");
        $update = json_decode($content, true);

        if (!$update) {
            echo "FinBot Webhook Active (Bot: @erka_finbot)";
            return;
        }

        if (isset($update["callback_query"])) {
            $this->handle_telegram_callback($update["callback_query"]);
            return;
        }

        if (isset($update["message"])) {
            $this->handle_telegram_message($update["message"]);
            return;
        }
    }

    private function handle_telegram_message($msg)
    {
        $chat_id = (string) $msg["chat"]["id"];
        $text = isset($msg["text"]) ? trim($msg["text"]) : "";
        $message_id = $msg["message_id"];
        $first_name = $msg["from"]["first_name"] ?? 'Pemilik Usaha';
        $username = $msg["from"]["username"] ?? '';

        $tg_account = $this->db->where('telegram_chat_id', $chat_id)
            ->where('deleted_at', NULL)
            ->get('finbot_telegram_accounts')
            ->row();

        if (!$tg_account) {
            $this->handle_onboarding_step($chat_id, $text, $first_name, $username);
            return;
        }

        $business_id = (int) $tg_account->business_id;
        $user_id = (int) $tg_account->user_id;

        $biz = $this->db->where('id', $business_id)->get('finbot_businesses')->row();
        $b_name = $biz ? $biz->name : 'Usaha Anda';
        $view_token = $biz && !empty($biz->view_token) ? $biz->view_token : ('FB-' . $business_id);

        $lowered = strtolower($text);

        if ($lowered === '/start') {
            $quota = $this->check_transaction_quota($business_id);
            $reply = "👋 <b>Halo, " . htmlspecialchars($first_name) . "!</b>\n\n"
                . "FinBot siap mencatat keuangan untuk <b>" . htmlspecialchars($b_name) . "</b>.\n\n"
                . "🔑 <b>Kode Token Dashboard:</b> <code>" . $view_token . "</code>\n"
                . "📊 <b>Status Kuota:</b> " . $quota['used'] . "/" . $quota['limit'] . " transaksi (" . $quota['plan_name'] . ")\n\n"
                . "<b>Cara Pakai:</b> Nggak perlu format. Chat aja!\n"
                . "• <code>masuk 350rb dari jualan</code>\n"
                . "• <code>bensin 50rb</code>\n"
                . "• <code>keluar 750 buat kulak, bensin 50rb</code>";

            $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));
            $markup = [
                'inline_keyboard' => [
                    [
                        ['text' => '📊 Buka Dashboard Hasil Isian', 'url' => $login_url]
                    ],
                    [
                        ['text' => '💰 Cek Saldo', 'callback_data' => 'cb_saldo'],
                        ['text' => '📈 Laporan Bulan Ini', 'callback_data' => 'cb_laporan']
                    ],
                    [
                        ['text' => '🔑 Lihat Token', 'callback_data' => 'cb_token'],
                        ['text' => '⭐ Upgrade Kuota', 'callback_data' => 'cb_paket']
                    ]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if ($lowered === '/token') {
            $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));
            $reply = "🔑 <b>Kode Token Dashboard FinBot Anda:</b>\n\n"
                . "<code>" . $view_token . "</code>\n\n"
                . "Gunakan kode token ini di web dashboard untuk langsung membuka data pembukuan <b>" . htmlspecialchars($b_name) . "</b>.\n\n"
                . "Atau klik tautan langsung di bawah:";
            $markup = [
                'inline_keyboard' => [
                    [['text' => '🚀 Buka Web Dashboard', 'url' => $login_url]]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if ($lowered === '/dashboard' || $lowered === '/login') {
            $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));
            $reply = "📊 <b>Dashboard Hasil Isian FinBot</b>\n\n"
                . "🏢 Usaha: <b>" . htmlspecialchars($b_name) . "</b>\n"
                . "🔑 Token: <code>" . $view_token . "</code>\n\n"
                . "Klik tombol di bawah untuk membuka dashboard:";
            $markup = [
                'inline_keyboard' => [
                    [['text' => '🚀 Buka Dashboard Web', 'url' => $login_url]]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if ($lowered === '/saldo') {
            $reply = $this->generate_telegram_saldo_report($business_id);
            $this->send_telegram_reply($chat_id, $reply);
            return;
        }

        if ($lowered === '/laporan') {
            $reply = $this->generate_telegram_monthly_report($business_id);
            $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));
            $markup = [
                'inline_keyboard' => [
                    [['text' => '📊 Lihat Grafik & Detail di Web', 'url' => $login_url]]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if ($lowered === '/bantuan' || $lowered === '/help') {
            $reply = "❓ <b>Panduan Penggunaan FinBot</b>\n\n"
                . "Kirim chat keuangan sehari-hari:\n"
                . "• <code>masuk 350k toko</code>\n"
                . "• <code>bensin 50rb</code>\n"
                . "• <code>keluar 750 buat kulak, bensin 50rb</code>\n\n"
                . "Perintah bot:\n"
                . "/dashboard — Buka web dashboard\n"
                . "/token — Lihat kode token tokomu\n"
                . "/saldo — Cek saldo kas saat ini\n"
                . "/laporan — Rekap bulan ini\n"
                . "/upgrade — Info paket langganan";
            $this->send_telegram_reply($chat_id, $reply);
            return;
        }

        // Parse Transaksi NLP
        $quota = $this->check_transaction_quota($business_id);
        if (!$quota['allowed']) {
            $reply = "⚠️ <b>Batas Kuota Transaksi Tercapai!</b>\n\n"
                . "Usahamu telah menggunakan kuota <b>" . $quota['used'] . "/" . $quota['limit'] . " transaksi</b>.\n"
                . "Upgrade ke FinBot Pro (Rp9.900/bln) untuk 500 transaksi!";
            $this->send_telegram_reply($chat_id, $reply);
            return;
        }

        $parsed = $this->parse_natural_language($text);

        if ($parsed['is_query']) {
            $this->send_telegram_reply($chat_id, $parsed['query_reply']);
        } elseif (!empty($parsed['transactions'])) {
            $tx_count = count($parsed['transactions']);

            if ($parsed['confidence'] >= 0.90 && $tx_count === 1) {
                $tx = $parsed['transactions'][0];
                $acc_id = $this->get_business_default_account_id($business_id);
                $this->db->insert('finbot_transactions', [
                    'business_id' => $business_id,
                    'account_id' => $acc_id,
                    'type' => $tx['type'],
                    'amount' => $tx['amount'],
                    'category_name' => $tx['category_name'],
                    'description' => $tx['description'],
                    'transaction_date' => date('Y-m-d'),
                    'source' => 'telegram',
                    'raw_message' => $text,
                    'telegram_message_id' => (string) $message_id,
                    'confidence' => $tx['confidence'],
                    'is_confirmed' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // Update saldo akun kas bisnis
                if ($acc_id) {
                    if ($tx['type'] === 'income') {
                        $this->db->set('balance', 'balance + ' . $tx['amount'], FALSE);
                    } else {
                        $this->db->set('balance', 'balance - ' . $tx['amount'], FALSE);
                    }
                    $this->db->where('id', $acc_id)->where('business_id', $business_id)->update('finbot_accounts');
                }

                $icon = $tx['type'] === 'income' ? '🟢' : '🔴';
                $type_label = $tx['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran';

                $reply = "$icon <b>$type_label</b>\n"
                    . $tx['formatted_amount'] . " — " . htmlspecialchars($tx['description']) . "\n\n"
                    . "✅ <b>Tersimpan di dashboard.</b>";

                $this->send_telegram_reply($chat_id, $reply);
            } else {
                // Multi transaksi
                $reply = "Saya memahami <b>" . $tx_count . " transaksi</b>:\n\n";
                $tot_inc = 0;
                $tot_exp = 0;
                foreach ($parsed['transactions'] as $t) {
                    $icon = $t['type'] === 'income' ? '🟢' : '🔴';
                    $reply .= "$icon " . htmlspecialchars($t['description']) . " — " . $t['formatted_amount'] . "\n";
                    if ($t['type'] === 'income')
                        $tot_inc += $t['amount'];
                    else
                        $tot_exp += $t['amount'];
                }
                $net = $tot_inc - $tot_exp;
                $reply .= "\n🟢 Pemasukan: Rp " . number_format($tot_inc, 0, ',', '.') . "\n";
                $reply .= "🔴 Pengeluaran: Rp " . number_format($tot_exp, 0, ',', '.') . "\n";
                $reply .= "💰 Net: <b>" . ($net >= 0 ? '+Rp ' : '-Rp ') . number_format(abs($net), 0, ',', '.') . "</b>\n\n";
                $reply .= "Simpan semua transaksi ini?";

                $session_id = 'tx_' . time() . '_' . rand(100, 999);
                $this->db->replace('finbot_onboarding_sessions', [
                    'telegram_chat_id' => 'temp_' . $session_id,
                    'step' => 'pending_multi_tx',
                    'temp_data' => json_encode($parsed['transactions']),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $markup = [
                    'inline_keyboard' => [
                        [
                            ['text' => '✅ Simpan Semua', 'callback_data' => 'save_multi:' . $session_id],
                            ['text' => '❌ Batal', 'callback_data' => 'cancel_tx']
                        ]
                    ]
                ];
                $this->send_telegram_reply($chat_id, $reply, $markup);
            }
        } else {
            $reply = "FinBot belum mengenali nominal transaksi.\n\nContoh ketik:\n• <code>bensin 50rb</code>\n• <code>masuk 350k jualan</code>";
            $this->send_telegram_reply($chat_id, $reply);
        }
    }

    private function handle_onboarding_step($chat_id, $text, $first_name, $username)
    {
        $session = $this->db->where('telegram_chat_id', $chat_id)->get('finbot_onboarding_sessions')->row();
        $step = $session ? $session->step : 'initial';
        $lowered = strtolower(trim($text));

        // Jika user langsung mengirim transaksi finansial
        $direct_tx = $this->parse_natural_language($text);
        if (!empty($direct_tx['transactions'])) {
            $auto_name = ($first_name ?: 'Usaha') . ' Store';
            $this->finish_onboarding($chat_id, $first_name, $username, $auto_name, 'UMKM Retail');

            // Ambil akun yang baru dibuat untuk mencatat transaksi
            $tg_acc = $this->db->where('telegram_chat_id', $chat_id)->where('deleted_at', NULL)->get('finbot_telegram_accounts')->row();
            $biz_id = $tg_acc ? (int) $tg_acc->business_id : 1;
            $tx = $direct_tx['transactions'][0];
            $acc_id = $this->get_business_default_account_id($biz_id);

            $this->db->insert('finbot_transactions', [
                'business_id' => $biz_id,
                'account_id' => $acc_id,
                'type' => $tx['type'],
                'amount' => $tx['amount'],
                'category_name' => $tx['category_name'],
                'description' => $tx['description'],
                'transaction_date' => date('Y-m-d'),
                'source' => 'telegram',
                'raw_message' => $text,
                'confidence' => $tx['confidence'],
                'is_confirmed' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if ($acc_id) {
                if ($tx['type'] === 'income') {
                    $this->db->set('balance', 'balance + ' . $tx['amount'], FALSE);
                } else {
                    $this->db->set('balance', 'balance - ' . $tx['amount'], FALSE);
                }
                $this->db->where('id', $acc_id)->where('business_id', $biz_id)->update('finbot_accounts');
            }

            $icon = $tx['type'] === 'income' ? '🟢' : '🔴';
            $type_label = $tx['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran';
            $reply = "🎉 <b>Akun usahamu otomatis disiapkan!</b>\n\n"
                . "$icon <b>$type_label</b>\n"
                . $tx['formatted_amount'] . " — " . htmlspecialchars($tx['description']) . "\n\n"
                . "✅ <b>Tersimpan di pembukuan $auto_name.</b>\n\n"
                . "Ketik transaksi berikutnya kapan saja atau /start untuk menu.";
            $this->send_telegram_reply($chat_id, $reply);
            return;
        }

        if ($step === 'initial' || $lowered === '/start') {
            $this->db->replace('finbot_onboarding_sessions', [
                'telegram_chat_id' => $chat_id,
                'step' => 'awaiting_name',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $reply = "👋 <b>Halo, " . htmlspecialchars($first_name) . "! Saya FinBot.</b>\n\n"
                . "Saya asisten keuangan pintar untuk usahamu.\n"
                . "<b>“Nggak perlu format rumit. Chat aja.”</b>\n\n"
                . "Mau kita siapkan pembukuan usahamu sekarang?";

            $markup = [
                'inline_keyboard' => [
                    [['text' => '🚀 Mulai FinBot', 'callback_data' => 'cb_start_reg']]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if ($step === 'awaiting_name') {
            $affirmative = ['ya', 'iya', 'mau', 'siap', 'oke', 'ok', 'lanjut', 'mulai', 'gas'];
            if (in_array($lowered, $affirmative)) {
                $this->send_telegram_reply($chat_id, "Boleh tahu apa <b>nama usahamu / tokomu</b>?\n\nContoh ketik: <code>Warung Berkah</code> atau <code>Toko Maju Jaya</code>");
                return;
            }

            $business_name = trim($text);
            if (empty($business_name) || strlen($business_name) < 2) {
                $this->send_telegram_reply($chat_id, "Boleh tahu nama usahamu/tokomu?\n\nContoh: <code>Warung Berkah</code> atau <code>Toko Maju Jaya</code>");
                return;
            }

            $this->finish_onboarding($chat_id, $first_name, $username, $business_name, 'UMKM Retail');
            return;
        }
    }

    private function finish_onboarding($chat_id, $first_name, $username, $b_name, $b_type)
    {
        $view_token = 'FB-' . strtoupper(substr(md5(uniqid($chat_id, true)), 0, 8));

        // 1. Buat User
        $this->db->insert('finbot_users', [
            'name' => $first_name ?: 'Pengguna FinBot',
            'email' => 'user_' . $chat_id . '@finbot.id',
            'password' => password_hash(uniqid('finbot_'), PASSWORD_DEFAULT),
            'role' => 'owner',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $new_user_id = $this->db->insert_id();

        // 2. Buat Business dengan Token
        $this->db->insert('finbot_businesses', [
            'user_id' => $new_user_id,
            'name' => $b_name,
            'business_type' => $b_type,
            'view_token' => $view_token,
            'currency' => 'IDR',
            'plan' => 'free',
            'monthly_limit' => 50,
            'initial_balance' => 0.00,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $new_biz_id = $this->db->insert_id();

        // 3. Akun Kas Default
        $this->db->insert('finbot_accounts', [
            'business_id' => $new_biz_id,
            'name' => 'Kas Tunai (Cash)',
            'type' => 'cash',
            'balance' => 0.00,
            'is_default' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 4. Hubungkan Telegram
        $this->db->insert('finbot_telegram_accounts', [
            'business_id' => $new_biz_id,
            'user_id' => $new_user_id,
            'telegram_chat_id' => $chat_id,
            'telegram_username' => $username,
            'first_name' => $first_name,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->where('telegram_chat_id', $chat_id)->delete('finbot_onboarding_sessions');

        $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));

        $reply = "🎉 <b>FinBot siap digunakan!</b>\n\n"
            . "🏢 Usaha: <b>" . htmlspecialchars($b_name) . "</b>\n"
            . "🔑 <b>Kode Token Dashboard:</b> <code>" . $view_token . "</code>\n\n"
            . "Ketik langsung pengeluaran / pemasukan kapan saja:\n"
            . "• <code>masuk 350k jualan</code>\n"
            . "• <code>bensin 50rb</code>\n"
            . "• <code>keluar 750 buat kulak, bensin 50rb</code>";

        $markup = [
            'inline_keyboard' => [
                [['text' => '📊 Buka Dashboard Hasil Isian', 'url' => $login_url]]
            ]
        ];

        $this->send_telegram_reply($chat_id, $reply, $markup);
    }

    private function handle_telegram_callback($cb)
    {
        $chat_id = (string) $cb["message"]["chat"]["id"];
        $data = $cb["data"];
        $cb_id = $cb["id"];

        $this->answer_telegram_callback($cb_id);

        if ($data === 'cb_start_reg') {
            $this->db->replace('finbot_onboarding_sessions', [
                'telegram_chat_id' => $chat_id,
                'step' => 'awaiting_name',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $this->send_telegram_reply($chat_id, "Boleh tahu apa nama usahamu / tokomu?\n\nContoh:\n• <code>Warung Berkah</code>\n• <code>Toko Maju Jaya</code>\n• <code>Laundry Cepat</code>");
            return;
        }

        $tg_account = $this->db->where('telegram_chat_id', $chat_id)->where('deleted_at', NULL)->get('finbot_telegram_accounts')->row();
        $business_id = $tg_account ? (int) $tg_account->business_id : 1;

        $biz = $this->db->where('id', $business_id)->get('finbot_businesses')->row();
        $view_token = $biz && !empty($biz->view_token) ? $biz->view_token : ('FB-' . $business_id);

        if ($data === 'cb_token') {
            $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));
            $reply = "🔑 <b>Kode Token Dashboard Anda:</b>\n\n<code>" . $view_token . "</code>\n\nMasukkan di menu web dashboard untuk melihat data pembukuanmu.";
            $markup = [
                'inline_keyboard' => [
                    [['text' => '🚀 Buka Web Dashboard', 'url' => $login_url]]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if ($data === 'cb_saldo') {
            $reply = $this->generate_telegram_saldo_report($business_id);
            $this->send_telegram_reply($chat_id, $reply);
            return;
        }

        if ($data === 'cb_laporan') {
            $reply = $this->generate_telegram_monthly_report($business_id);
            $this->send_telegram_reply($chat_id, $reply);
            return;
        }

        if ($data === 'cb_paket') {
            $quota = $this->check_transaction_quota($business_id);
            $reply = "⭐ <b>Paket FinBot</b>\n\nPaket: <b>" . $quota['plan_name'] . "</b>\nKuota: <b>" . $quota['used'] . "/" . $quota['limit'] . " transaksi</b>\n\n• <b>FinBot Pro</b> (Rp9.900/bln): 500 transaksi/bln, laporan & grafik lengkap.\n• <b>FinBot Pro+</b> (Rp19.900/bln): 2.000 transaksi/bln, analisis bisnis lanjutan.";
            $login_url = site_url('finbot/dashboard?token=' . urlencode($view_token));
            $markup = [
                'inline_keyboard' => [
                    [['text' => '💎 Upgrade di Web Dashboard', 'url' => $login_url]]
                ]
            ];
            $this->send_telegram_reply($chat_id, $reply, $markup);
            return;
        }

        if (strpos($data, 'save_multi:') === 0) {
            $session_id = str_replace('save_multi:', '', $data);
            $sess = $this->db->where('telegram_chat_id', 'temp_' . $session_id)->get('finbot_onboarding_sessions')->row();

            if ($sess && !empty($sess->temp_data)) {
                $transactions = json_decode($sess->temp_data, true);
                $saved_count = 0;
                $acc_id = $this->get_business_default_account_id($business_id);

                foreach ($transactions as $tx) {
                    $this->db->insert('finbot_transactions', [
                        'business_id' => $business_id,
                        'account_id' => $acc_id,
                        'type' => $tx['type'],
                        'amount' => $tx['amount'],
                        'category_name' => $tx['category_name'] ?? 'Lainnya',
                        'description' => $tx['description'] ?? 'Catatan',
                        'transaction_date' => date('Y-m-d'),
                        'source' => 'telegram',
                        'confidence' => $tx['confidence'] ?? 1.00,
                        'is_confirmed' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    if ($acc_id) {
                        if ($tx['type'] === 'income') {
                            $this->db->set('balance', 'balance + ' . (float) $tx['amount'], FALSE);
                        } else {
                            $this->db->set('balance', 'balance - ' . (float) $tx['amount'], FALSE);
                        }
                        $this->db->where('id', $acc_id)->where('business_id', $business_id)->update('finbot_accounts');
                    }

                    $saved_count++;
                }

                $this->db->where('telegram_chat_id', 'temp_' . $session_id)->delete('finbot_onboarding_sessions');
                $reply = "✅ <b>$saved_count transaksi berhasil disimpan!</b>\n\nKetik /saldo untuk cek saldo.";
                $this->send_telegram_reply($chat_id, $reply);
            }
            return;
        }

        if ($data === 'cancel_tx') {
            $this->send_telegram_reply($chat_id, "❌ Transaksi dibatalkan.");
            return;
        }
    }

    private function parse_natural_language($text)
    {
        $clean_text = trim($text);

        if ($this->is_financial_question($clean_text)) {
            $answer = $this->answer_financial_question($clean_text);
            return [
                'is_query' => true,
                'query_reply' => $answer,
                'transactions' => [],
                'confidence' => 0.99
            ];
        }

        $delimiters = ["\n", "\r\n", ",", ";", " terus ", " lalu ", " kemudian ", " sama tadi ", " sama ", " serta "];
        $standardized = str_ireplace($delimiters, "|SPLIT|", $clean_text);
        $standardized = preg_replace('/([\+\-])\s*([0-9])/i', '|SPLIT|$1$2', $standardized);

        $chunks = explode('|SPLIT|', $standardized);
        $transactions = [];
        $total_confidence = 0;

        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            if (empty($chunk))
                continue;

            $item = $this->extract_single_transaction($chunk);
            if ($item) {
                $transactions[] = $item;
                $total_confidence += $item['confidence'];
            }
        }

        if (empty($transactions)) {
            $fallback = $this->extract_single_transaction($clean_text);
            if ($fallback) {
                $transactions[] = $fallback;
                $total_confidence += $fallback['confidence'];
            }
        }

        $avg_confidence = count($transactions) > 0 ? round($total_confidence / count($transactions), 2) : 0.50;

        return [
            'is_query' => false,
            'transactions' => $transactions,
            'confidence' => $avg_confidence
        ];
    }

    private function extract_single_transaction($chunk)
    {
        $is_explicit_plus = (strpos($chunk, '+') === 0 || strpos($chunk, ' +') !== false);
        $is_explicit_minus = (strpos($chunk, '-') === 0 || strpos($chunk, ' -') !== false);

        $number_pattern = '/(\+|-)?\s*([0-9]+(?:[\.,][0-9]+)?)\s*(k|rb|ribu|jt|juta|kilo|rupiah|rp)?/i';
        preg_match_all($number_pattern, $chunk, $matches, PREG_SET_ORDER);

        $found_amount = 0;
        $matched_string = '';

        foreach ($matches as $m) {
            $num_str = $m[2];
            $unit = isset($m[3]) ? strtolower($m[3]) : '';
            $normalized = $this->normalize_amount($num_str, $unit);
            if ($normalized > 0) {
                $found_amount = $normalized;
                $matched_string = $m[0];
                break;
            }
        }

        if ($found_amount <= 0)
            return null;

        $raw_desc = trim(str_ireplace($matched_string, '', $chunk));
        $raw_desc = preg_replace('/^(tadi|buat|untuk|beli|masuk|keluar|dari|ada|bayar|ongkos|biaya)\s+/i', '', $raw_desc);
        $raw_desc = trim($raw_desc, " \t\n\r\0\x0B,.-+");

        $type = 'expense';
        $confidence = 0.95;
        $income_keywords = ['masuk', 'jual', 'penjualan', 'toko', 'laba', 'dapat', 'terima', 'transferan', 'omset', 'omzet', 'laku', 'income', '+'];
        $lowered_chunk = strtolower($chunk);

        if ($is_explicit_plus) {
            $type = 'income';
            $confidence = 0.98;
        } elseif ($is_explicit_minus) {
            $type = 'expense';
            $confidence = 0.98;
        } else {
            foreach ($income_keywords as $ik) {
                if (stripos($lowered_chunk, $ik) !== false) {
                    $type = 'income';
                    break;
                }
            }
        }

        $category_name = $this->determine_category_name($lowered_chunk, $type);
        $description = !empty($raw_desc) ? ucfirst($raw_desc) : $category_name;

        return [
            'type' => $type,
            'amount' => $found_amount,
            'formatted_amount' => 'Rp ' . number_format($found_amount, 0, ',', '.'),
            'category_name' => $category_name,
            'description' => $description,
            'transaction_date' => date('Y-m-d'),
            'confidence' => $confidence,
            'account_id' => 1
        ];
    }

    private function normalize_amount($num_str, $unit)
    {
        $num_str = str_replace(',', '.', $num_str);
        $val = (float) $num_str;
        $unit = strtolower(trim($unit));

        if ($unit === 'k' || $unit === 'rb' || $unit === 'ribu') {
            return $val * 1000;
        } elseif ($unit === 'jt' || $unit === 'juta') {
            return $val * 1000000;
        } else {
            if ($val > 0 && $val < 1000 && !strpos($num_str, '.')) {
                return $val * 1000;
            }
            return $val;
        }
    }

    private function determine_category_name($text, $type)
    {
        if ($type === 'income') {
            if (preg_match('/(toko|jual|penjualan|kasir|pelanggan|customer|laku|omzet)/i', $text)) {
                return 'Penjualan';
            }
            return 'Pendapatan Lain';
        }

        if (preg_match('/(kulak|kulakan|stok|barang|sembako|bahan|supplier|aqua|dus|beras)/i', $text)) {
            return 'Stok Barang';
        }
        if (preg_match('/(bensin|parkir|tol|ojol|grab|gojek|transport|solar|pertalite|bbm)/i', $text)) {
            return 'Transportasi';
        }
        if (preg_match('/(makan|minum|kopi|lunch|sarapan|snack|konsumsi|warteg|resto)/i', $text)) {
            return 'Makan & Minum';
        }
        if (preg_match('/(listrik|pln|air|pdam|wifi|internet|indihome|pulsa)/i', $text)) {
            return 'Listrik & Air';
        }
        if (preg_match('/(gaji|upah|karyawan|bonus|thr|kasir)/i', $text)) {
            return 'Gaji';
        }
        if (preg_match('/(sewa|ruko|lapak|kios|kontrakan)/i', $text)) {
            return 'Sewa';
        }
        if (preg_match('/(plastik|lakban|atk|kresek|nota|kertas|pack|operasional)/i', $text)) {
            return 'Operasional';
        }

        return 'Lainnya';
    }

    private function is_financial_question($text)
    {
        $q = strtolower(trim($text));
        return (preg_match('/^(berapa|cek saldo|\/saldo|\/laporan|\/help)/i', $q) || strpos($q, '?') !== false);
    }

    private function answer_financial_question($question)
    {
        return "🤖 <b>FinBot siap membantu!</b>\n\nKetik langsung catatan transaksi seperti:\n• <code>120k bensin</code>\n• <code>masuk 350k jualan</code>\n• <code>keluar 750 buat kulak, bensin 50rb</code>";
    }

    private function generate_telegram_saldo_report($business_id)
    {
        $biz = $this->db->where('id', $business_id)->get('finbot_businesses')->row();
        $b_name = $biz ? $biz->name : 'Usaha';

        $accounts = $this->db->where('business_id', $business_id)->where('deleted_at', NULL)->get('finbot_accounts')->result();
        $total = 0;
        $acc_text = "";
        foreach ($accounts as $acc) {
            $acc_text .= "• " . $acc->name . ": <b>Rp " . number_format($acc->balance, 0, ',', '.') . "</b>\n";
            $total += (float) $acc->balance;
        }

        $start_m = date('Y-m-01');
        $end_m = date('Y-m-t');
        $inc_row = $this->db->select_sum('amount', 'total')->where('business_id', $business_id)->where('type', 'income')->where('transaction_date >=', $start_m)->where('deleted_at', NULL)->get('finbot_transactions')->row();
        $exp_row = $this->db->select_sum('amount', 'total')->where('business_id', $business_id)->where('type', 'expense')->where('transaction_date >=', $start_m)->where('deleted_at', NULL)->get('finbot_transactions')->row();
        $inc = $inc_row && $inc_row->total ? (float) $inc_row->total : 0;
        $exp = $exp_row && $exp_row->total ? (float) $exp_row->total : 0;
        $net = $inc - $exp;

        return "💰 <b>Saldo Keuangan — " . htmlspecialchars($b_name) . "</b>\n\n"
            . $acc_text . "\n"
            . "💵 <b>Total Saldo: Rp " . number_format($total, 0, ',', '.') . "</b>\n\n"
            . "📊 <b>Arus Kas Bulan Ini:</b>\n"
            . "🟢 Masuk: Rp " . number_format($inc, 0, ',', '.') . "\n"
            . "🔴 Keluar: Rp " . number_format($exp, 0, ',', '.') . "\n"
            . "✨ Net: <b>" . ($net >= 0 ? '+Rp ' : '-Rp ') . number_format(abs($net), 0, ',', '.') . "</b>";
    }

    private function generate_telegram_monthly_report($business_id)
    {
        $biz = $this->db->where('id', $business_id)->get('finbot_businesses')->row();
        $b_name = $biz ? $biz->name : 'Usaha';

        $start_m = date('Y-m-01');
        $end_m = date('Y-m-t');
        $inc_row = $this->db->select_sum('amount', 'total')->where('business_id', $business_id)->where('type', 'income')->where('transaction_date >=', $start_m)->where('deleted_at', NULL)->get('finbot_transactions')->row();
        $exp_row = $this->db->select_sum('amount', 'total')->where('business_id', $business_id)->where('type', 'expense')->where('transaction_date >=', $start_m)->where('deleted_at', NULL)->get('finbot_transactions')->row();
        $inc = $inc_row && $inc_row->total ? (float) $inc_row->total : 0;
        $exp = $exp_row && $exp_row->total ? (float) $exp_row->total : 0;
        $net = $inc - $exp;

        return "📊 <b>Laporan Keuangan " . date('F Y') . "</b>\n"
            . "🏢 <b>" . htmlspecialchars($b_name) . "</b>\n\n"
            . "🟢 Total Masuk: Rp " . number_format($inc, 0, ',', '.') . "\n"
            . "🔴 Total Keluar: Rp " . number_format($exp, 0, ',', '.') . "\n"
            . "💰 Laba Bersih: <b>" . ($net >= 0 ? '+Rp ' : '-Rp ') . number_format(abs($net), 0, ',', '.') . "</b>";
    }

    private function send_telegram_reply($chat_id, $text, $reply_markup = null)
    {
        $token = getenv('TELEGRAM_BOT_TOKEN') ?: '8861032168:AAGD10JV4coTw_XKolXk9TBgZ4Ok6kttBz4';
        if (empty($token) || $token === 'YOUR_BOT_TOKEN')
            return;

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
        $post_fields = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        if ($reply_markup) {
            $post_fields['reply_markup'] = json_encode($reply_markup);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function answer_telegram_callback($callback_id)
    {
        $token = getenv('TELEGRAM_BOT_TOKEN') ?: '8861032168:AAGD10JV4coTw_XKolXk9TBgZ4Ok6kttBz4';
        if (empty($token) || $token === 'YOUR_BOT_TOKEN')
            return;

        $url = "https://api.telegram.org/bot" . $token . "/answerCallbackQuery";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['callback_query_id' => $callback_id]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}
