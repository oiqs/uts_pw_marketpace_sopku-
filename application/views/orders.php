<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SopKu - Pesanan Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.development.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.development.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/7.23.6/babel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/iqon/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css') ?>">
</head>
<body class="bg-slate-50">
<div id="root"></div>

<script type="text/babel" data-presets="react,env">
const { useEffect, useState } = React;
const API_BASE = "<?= base_url('api/') ?>";

function Navbar() {
  return (
    <nav className="bg-white navbar-shadow sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="<?= base_url('home') ?>" className="flex items-center gap-2 text-2xl font-bold text-slate-800" style={{fontFamily:'Playfair Display, serif'}}>
          <img src="<?= base_url('assets/iqon/logo.png') ?>" alt="Logo SopKu" className="h-8 w-auto object-contain" />
          <span>Sop<span className="text-blue-600">Ku</span></span>
        </a>
        <div className="flex gap-5 text-sm font-semibold text-slate-600">
          <a href="<?= base_url('katalog') ?>" className="hover:text-blue-600">Katalog</a>
          <a href="<?= base_url('cart') ?>" className="hover:text-blue-600">Keranjang</a>
          <a href="<?= base_url('login') ?>" className="hover:text-blue-600">Akun</a>
        </div>
      </div>
    </nav>
  );
}

function ReturnForm({ order, onClose, onSuccess }) {
  const [selected, setSelected] = useState({});
  const [reason, setReason] = useState('');
  const [description, setDescription] = useState('');
  const [evidence, setEvidence] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);

  const toggleItem = (item, checked) => {
    setSelected(prev => {
      const next = { ...prev };
      if (checked) next[item.id] = { order_item_id: item.id, qty: 1 };
      else delete next[item.id];
      return next;
    });
  };

  const submit = () => {
    const items = Object.values(selected);
    if (!reason || items.length === 0) {
      setMessage({ ok: false, text: 'Pilih barang dan isi alasan return.' });
      return;
    }

    setLoading(true);
    axios.post(API_BASE + 'returns/create', {
      order_id: order.id,
      reason,
      description,
      evidence_image: evidence,
      items
    }).then(res => {
      setMessage({ ok: true, text: `Return ${res.data.return_code} berhasil diajukan.` });
      setTimeout(onSuccess, 900);
    }).catch(err => {
      setMessage({ ok: false, text: err.response?.data?.message || 'Gagal mengajukan return.' });
    }).finally(() => setLoading(false));
  };

  return (
    <div className="rounded-lg border border-blue-100 bg-blue-50 p-4 mt-4">
      <div className="flex items-center justify-between mb-3">
        <h4 className="font-bold text-slate-800">Ajukan Return</h4>
        <button onClick={onClose} className="text-sm font-bold text-slate-500 hover:text-red-600">Tutup</button>
      </div>
      <div className="space-y-3">
        {order.items.map(item => (
          <label key={item.id} className="flex gap-3 rounded-lg bg-white p-3 border border-slate-100">
            <input type="checkbox" onChange={e => toggleItem(item, e.target.checked)} className="mt-1 h-4 w-4 accent-blue-600" />
            <img src={item.image} alt="" className="h-12 w-12 object-contain rounded border border-slate-100" />
            <div className="flex-1">
              <p className="text-sm font-semibold text-slate-800">{item.title}</p>
              <p className="text-xs text-slate-500">{item.qty} item - Rp {(item.price * item.qty).toLocaleString('id-ID')}</p>
            </div>
          </label>
        ))}
        <select value={reason} onChange={e => setReason(e.target.value)} className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
          <option value="">Pilih alasan</option>
          <option>Barang rusak</option>
          <option>Barang tidak sesuai</option>
          <option>Ukuran/varian salah</option>
          <option>Barang tidak lengkap</option>
          <option>Lainnya</option>
        </select>
        <textarea value={description} onChange={e => setDescription(e.target.value)} rows="3" placeholder="Ceritakan detail masalahnya" className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
        <input value={evidence} onChange={e => setEvidence(e.target.value)} placeholder="URL foto bukti, opsional" className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
        {message && <p className={`text-sm font-semibold ${message.ok ? 'text-green-700' : 'text-red-600'}`}>{message.text}</p>}
        <button disabled={loading} onClick={submit} className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700 disabled:bg-slate-300">
          {loading ? 'Mengirim...' : 'Kirim Pengajuan'}
        </button>
      </div>
    </div>
  );
}

function OrderCard({ order, onRefresh }) {
  const [openReturn, setOpenReturn] = useState(false);
  const canReturn = ['dikirim', 'selesai'].includes(order.status);

  return (
    <div className="rounded-lg border border-slate-200 bg-white p-5">
      <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <p className="font-bold text-blue-600">{order.order_code}</p>
          <p className="text-sm text-slate-500">{new Date(order.created_at).toLocaleString('id-ID')}</p>
        </div>
        <span className="w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">{order.status}</span>
      </div>
      <div className="mt-4 divide-y divide-slate-100">
        {order.items.map(item => (
          <div key={item.id} className="flex gap-3 py-3">
            <img src={item.image} alt="" className="h-14 w-14 object-contain rounded border border-slate-100" />
            <div className="flex-1">
              <p className="text-sm font-semibold text-slate-800">{item.title}</p>
              <p className="text-xs text-slate-500">{item.qty} x Rp {Number(item.price).toLocaleString('id-ID')}</p>
            </div>
            <b className="text-sm">Rp {(item.price * item.qty).toLocaleString('id-ID')}</b>
          </div>
        ))}
      </div>
      <div className="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
        <div className="text-sm text-slate-500">
          Total <b className="text-slate-900">Rp {Number(order.total).toLocaleString('id-ID')}</b>
          {order.promo_code && <span className="ml-2 text-green-600">Promo {order.promo_code}</span>}
        </div>
        {canReturn && <button onClick={() => setOpenReturn(true)} className="rounded-lg border border-blue-200 px-4 py-2 text-sm font-bold text-blue-700 hover:bg-blue-50">Ajukan Return</button>}
      </div>
      {order.returns?.length > 0 && (
        <div className="mt-4 rounded-lg bg-slate-50 p-3 text-sm text-slate-600 space-y-2">
          <b>Riwayat return:</b>
          {order.returns.map(r => (
            <div key={r.id} className="rounded-lg bg-white border border-slate-100 p-3">
              <div className="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <span className="font-bold text-blue-600">{r.return_code}</span>
                <span className="w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">{r.status}</span>
              </div>
              {r.admin_note ? (
                <p className="mt-2 text-xs text-slate-600">
                  <b>Catatan admin:</b> {r.admin_note}
                </p>
              ) : (
                <p className="mt-2 text-xs text-slate-400">Belum ada catatan admin.</p>
              )}
            </div>
          ))}
        </div>
      )}
      {openReturn && <ReturnForm order={order} onClose={() => setOpenReturn(false)} onSuccess={onRefresh} />}
    </div>
  );
}

function App() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const loadOrders = () => {
    setLoading(true);
    axios.get(API_BASE + 'orders')
      .then(res => setOrders(res.data.orders || []))
      .catch(err => setError(err.response?.data?.message || 'Gagal memuat pesanan.'))
      .finally(() => setLoading(false));
  };

  useEffect(loadOrders, []);

  return (
    <div>
      <Navbar />
      <header className="hero-bg text-white py-10 px-4">
        <div className="max-w-7xl mx-auto">
          <p className="text-blue-300 text-sm font-medium mb-1">Pesanan & Return</p>
          <h1 className="text-3xl md:text-4xl font-bold">Pesanan Saya</h1>
        </div>
      </header>
      <main className="max-w-5xl mx-auto px-4 py-8 space-y-5">
        {loading && <div className="rounded-lg bg-white p-8 text-center text-slate-500">Memuat pesanan...</div>}
        {error && <div className="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">{error}</div>}
        {!loading && !error && orders.length === 0 && <div className="rounded-lg bg-white p-8 text-center text-slate-500">Belum ada pesanan.</div>}
        {orders.map(order => <OrderCard key={order.id} order={order} onRefresh={loadOrders} />)}
      </main>
    </div>
  );
}

ReactDOM.render(<App />, document.getElementById('root'));
</script>
<script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
</body>
</html>
