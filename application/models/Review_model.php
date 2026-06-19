<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // Auto-create table if not exists
        if (!$this->db->table_exists('reviews')) {
            $this->load->dbforge();
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'product_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'rating' => [
                    'type' => 'INT',
                    'constraint' => 1,
                ],
                'comment' => [
                    'type' => 'TEXT',
                    'null' => TRUE,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('reviews', TRUE);
        }
    }

    public function get_by_product($product_id)
    {
        $this->db->select('reviews.*, users.name as user_name');
        $this->db->from('reviews');
        $this->db->join('users', 'users.id = reviews.user_id', 'left');
        $this->db->where('product_id', $product_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function add($product_id, $user_id, $rating, $comment)
    {
        $data = [
            'product_id' => $product_id,
            'user_id'    => $user_id,
            'rating'     => $rating,
            'comment'    => $comment,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('reviews', $data);
        
        $this->update_product_rating($product_id);
        
        return $this->db->insert_id();
    }

    private function update_product_rating($product_id)
    {
        $this->db->select_avg('rating', 'avg_rating');
        $this->db->select('COUNT(*) as total_reviews');
        $this->db->where('product_id', $product_id);
        $result = $this->db->get('reviews')->row();

        $avg_rating = round($result->avg_rating, 1);
        $total_reviews = $result->total_reviews;

        $this->db->where('id', $product_id);
        $this->db->update('products', [
            'rating_rate' => $avg_rating,
            'rating_count' => $total_reviews
        ]);
    }
}
