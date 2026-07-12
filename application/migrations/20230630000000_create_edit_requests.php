<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_edit_requests extends CI_Migration {
    public function up(){
        // Table fields
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'arsip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'petugas_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending','approved','rejected'],
                'default' => 'pending',
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_field("created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("updated_at TIMESTAMP NULL DEFAULT NULL");
        $this->dbforge->add_key('id', TRUE);
        // Add foreign keys
        $this->dbforge->add_field('CONSTRAINT fk_req_arsip FOREIGN KEY (arsip_id) REFERENCES arsip(id) ON DELETE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_req_petugas FOREIGN KEY (petugas_id) REFERENCES pengguna(id) ON DELETE CASCADE');
        $this->dbforge->create_table('edit_requests');
        // Index for quick status lookup
        $this->db->query('CREATE INDEX idx_status ON edit_requests (status)');
    }
    public function down(){
        $this->dbforge->drop_table('edit_requests', TRUE);
    }
}
?>
