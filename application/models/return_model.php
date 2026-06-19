<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_model extends CI_Model
{
    public function create($data, $items)
    {
        $this->db->trans_start();

        $this->db->insert('returns', [
            'return_code'    => 'RTN-' . strtoupper(base_convert((string) time(), 10, 36)),
            'order_id'       => (int) $data['order_id'],
            'user_id'        => $data['user_id'] ?: null,
            'reason'         => trim($data['reason']),
            'description'    => trim($data['description'] ?? ''),
            'evidence_image' => trim($data['evidence_image'] ?? ''),
            'refund_amount'  => 0,
            'status'         => 'diajukan',
        ]);

        $return_id = $this->db->insert_id();
        $refund = 0;

        foreach ($items as $item) {
            $order_item = $this->db->get_where('order_items', ['id' => (int) $item['order_item_id']])->row_array();
            if (!$order_item) continue;

            $available = max(0, (int) $order_item['qty'] - (int) ($order_item['returned_qty'] ?? 0));
            $qty = min(max(1, (int) $item['qty']), $available);
            if ($qty <= 0) continue;

            $subtotal = (float) $order_item['price'] * $qty;
            $refund += $subtotal;

            $this->db->insert('return_items', [
                'return_id'     => $return_id,
                'order_item_id' => $order_item['id'],
                'product_id'    => $order_item['product_id'],
                'qty'           => $qty,
                'price'         => $order_item['price'],
                'subtotal'      => $subtotal,
            ]);
        }

        $this->db->where('id', $return_id)->update('returns', ['refund_amount' => $refund]);

        $this->db->trans_complete();
        return $this->db->trans_status() ? $return_id : false;
    }

    public function all()
    {
        $this->db->select('returns.*, orders.order_code, users.name as user_name, users.email as user_email');
        $this->db->from('returns');
        $this->db->join('orders', 'orders.id = returns.order_id');
        $this->db->join('users', 'users.id = returns.user_id', 'left');
        $this->db->order_by('returns.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function find($id)
    {
        $this->db->select('returns.*, orders.order_code, orders.status as order_status, users.name as user_name, users.email as user_email');
        $this->db->from('returns');
        $this->db->join('orders', 'orders.id = returns.order_id');
        $this->db->join('users', 'users.id = returns.user_id', 'left');
        $this->db->where('returns.id', $id);
        return $this->db->get()->row_array();
    }

    public function items($return_id)
    {
        $this->db->select('return_items.*, order_items.title, order_items.image');
        $this->db->from('return_items');
        $this->db->join('order_items', 'order_items.id = return_items.order_item_id');
        $this->db->where('return_items.return_id', $return_id);
        return $this->db->get()->result_array();
    }

    public function update_status($id, $status, $note = '')
    {
        $allowed = ['diajukan', 'disetujui', 'ditolak', 'barang_dikirim', 'barang_diterima', 'refund_diproses', 'selesai', 'dibatalkan'];
        if (!in_array($status, $allowed, true)) return false;

        $this->db->trans_start();
        $this->db->where('id', $id)->update('returns', [
            'status' => $status,
            'admin_note' => trim($note),
        ]);

        if ($status === 'selesai') {
            $items = $this->items($id);
            foreach ($items as $item) {
                $this->db->set('returned_qty', 'returned_qty + ' . (int) $item['qty'], false)
                    ->where('id', $item['order_item_id'])
                    ->update('order_items');
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
