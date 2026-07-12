<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="editRequestOverlay" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <div class="modal-header">
      <h5 class="modal-title">Permintaan Edit Arsip</h5>
      <span class="modal-close" id="closeModalBtn">&times;</span>
    </div>
    <div class="modal-body">
      <form id="editRequestForm">
        <input type="hidden" name="arsip_id" id="modalArsipId" />
        <div class="form-group">
          <label for="modalNote">Catatan (opsional)</label>
          <textarea id="modalNote" name="note" rows="3" placeholder="Berikan alasan atau komentar Anda"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" id="cancelModalBtn">Batal</button>
      <button type="button" class="btn btn-primary" id="submitEditRequest">Kirim Permintaan</button>
    </div>
  </div>
</div>
