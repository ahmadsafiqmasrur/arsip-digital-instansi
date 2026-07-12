        </main>
    </div> <!-- Penutup .app-container -->

<!-- Toast container -->
<div id="toast-container" style="position:fixed; top:20px; right:20px; z-index:1200;"></div>

<script>
  const base_url = "<?= rtrim(site_url(''), '/') . '/' ?>";
  window.user_role = "<?= $this->session->userdata('role') ?>";
</script>
<script src="<?= base_url('assets/js/edit_request.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/edit_request.js') ?>"></script>

</body>

</html>
