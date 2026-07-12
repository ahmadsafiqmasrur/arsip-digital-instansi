<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {
    public function latest() {
        $this->load->library('migration');
        if ($this->migration->latest() === FALSE) {
            echo "Migration failed: " . $this->migration->error_string();
            return;
        }
        echo "Migration successful.";
    }
}
