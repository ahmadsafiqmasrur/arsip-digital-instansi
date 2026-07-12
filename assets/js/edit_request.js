// assets/js/edit_request.js
// Handles edit request modal, AJAX submission, and badge refresh

function initEditRequest() {
  const overlay = document.getElementById('editRequestOverlay');
  const submitBtn = document.getElementById('submitEditRequest');
  const closeBtn = document.getElementById('closeModalBtn');
  const cancelBtn = document.getElementById('cancelModalBtn');
  const userRole = window.user_role || '';

  function showModal() {
    if (!overlay) return;
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
  }

  function hideModal() {
    if (!overlay) return;
    overlay.style.display = 'none';
    overlay.style.visibility = 'visible';
  }

  if (overlay) {
    document.querySelectorAll('.request-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const arsipId = this.getAttribute('data-id');
        document.getElementById('modalArsipId').value = arsipId;
        document.getElementById('modalNote').value = '';
        showModal();
      });
    });

    if (closeBtn) closeBtn.addEventListener('click', hideModal);
    if (cancelBtn) cancelBtn.addEventListener('click', hideModal);
  }

  if (submitBtn) {
    submitBtn.addEventListener('click', function () {
      const form = document.getElementById('editRequestForm');
      const formData = new FormData(form);
      const arsipId = formData.get('arsip_id');
      if (!arsipId) {
        showToast('Silakan pilih data arsip terlebih dahulu', 'danger');
        return;
      }

      if (submitBtn.disabled) return;
      submitBtn.disabled = true;
      submitBtn.setAttribute('disabled', 'disabled');
      console.debug('edit_request: submitting request for arsipId=', arsipId);
      fetch(base_url + 'editrequest/request_edit', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      })
        .then(res => {
          if (!res.ok) {
            throw new Error('HTTP error ' + res.status);
          }
          return res.json();
        })
        .then(data => {
          if (data.status === 'pending') {
            hideModal();
            refreshPendingBadge();
            fetchNotifications();
            // update row status in the table to 'Menunggu Persetujuan'
            try {
              updateRowStatus(arsipId, 'pending');
            } catch (err) {
              console.error('Update row status error:', err);
            }
            try {
              showToast('Permintaan berhasil dikirim', 'success');
            } catch (toastErr) {
              console.error('Toast error:', toastErr);
            }
          } else {
            throw new Error(data.message || 'Terjadi kesalahan');
          }
        })
        .catch(err => {
          console.error(err);
          showToast('Gagal mengirim permintaan', 'danger');
        })
        .finally(() => {
          submitBtn.disabled = false;
        });
    });
  }

  // Auto‑refresh badge every 30 seconds
  function refreshPendingBadge() {
    fetch(base_url + 'editrequest/pending_count', {
      method: 'GET',
      credentials: 'same-origin',
    })
      .then(res => res.json())
      .then(data => {
        if (notificationBadge) {
          if (data.count > 0) {
            notificationBadge.textContent = data.count;
            notificationBadge.style.display = 'inline-block';
          } else {
            notificationBadge.style.display = 'none';
          }
        }
      })
      .catch(console.error);
  }

  // Notification helpers for koordinator
  const notificationToggle = document.getElementById('notificationToggle');
  const notificationDropdown = document.getElementById('notificationDropdown');
  const notificationBadge = document.getElementById('notificationBadge');

  function renderNotifications(items) {
    if (!notificationDropdown) return;
    if (items.length === 0) {
      notificationDropdown.innerHTML = '<div class="notification-empty">Tidak ada permintaan edit baru.</div>';
      notificationBadge.style.display = 'none';
      return;
    }

    notificationDropdown.innerHTML = items.map(item => {
      if (userRole === 'koor') {
        return `
          <div class="notification-item">
            <h4>Permintaan Edit: ${item.no_arsip}</h4>
            <p><strong>Petugas:</strong> ${item.petugas_name}</p>
            <p><strong>Kelurahan:</strong> ${item.kelurahan}</p>
            <small>${item.note ? item.note : 'Tanpa catatan'}</small>
            <div class="notification-action">
              <button type="button" class="notif-action-btn approve-btn" data-request-id="${item.id}" data-arsip-id="${item.arsip_id}">Setuju</button>
              <button type="button" class="notif-action-btn reject-btn" data-request-id="${item.id}" data-arsip-id="${item.arsip_id}">Tolak</button>
            </div>
          </div>
        `;
      }
      const statusLabel = item.status === 'approved' ? 'Disetujui' : item.status === 'pending' ? 'Menunggu' : 'Ditolak';
      const statusStyle = item.status === 'approved' ? 'status-approved' : item.status === 'pending' ? 'status-pending' : 'status-rejected';
      return `
        <div class="notification-item">
          <h4>Permintaan Edit: ${item.no_arsip}</h4>
          <p><strong>Kelurahan:</strong> ${item.kelurahan}</p>
          <small>Status: <span class="status-dot ${statusStyle}"></span> ${statusLabel}</small>
          <div class="notification-action">
            ${item.status === 'approved' ? `<a href="${base_url}arsip/edit/${item.arsip_id}" class="btn-action-edit">Edit</a>` : '<span style="padding:8px 12px; border-radius:8px; background:#f8f9fa; color:#475569; font-size:0.88rem;">Menunggu Persetujuan</span>'}
          </div>
        </div>
      `;
    }).join('');
    if (notificationBadge) {
      notificationBadge.textContent = items.length;
      notificationBadge.style.display = 'inline-block';
    }
  }

  function fetchNotifications() {
    if (!notificationToggle) return;
    const targetUrl = base_url + 'editrequest/notifications';
    console.debug('edit_request: fetching notifications from', targetUrl, 'userRole=', userRole);
    fetch(targetUrl, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(res => {
        if (!res.ok) {
          throw new Error('HTTP error ' + res.status);
        }
        return res.text();
      })
      .then(text => {
        try {
          const data = JSON.parse(text);
          renderNotifications(data.requests || []);
        } catch (parseErr) {
          throw new Error('Invalid JSON response: ' + text);
        }
      })
      .catch(err => {
        console.warn('edit_request: notifications fetch failed, trying fallback. Error:', err);
        if (userRole === 'koor') {
          fetchFallbackNotifications('pending_requests');
        } else if (userRole === 'petugas') {
          fetchFallbackNotifications('user_requests');
        } else {
          console.error('edit_request: No valid role for fetching notifications', userRole);
          if (notificationDropdown) {
            notificationDropdown.innerHTML = '<div class="notification-empty">Tidak dapat memuat permintaan.</div>';
          }
        }
      });
  }

  function fetchFallbackNotifications(endpoint) {
    const fallbackUrl = base_url + 'editrequest/' + endpoint;
    console.debug('edit_request: fetching fallback notifications from', fallbackUrl);
    fetch(fallbackUrl, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(res => {
        if (!res.ok) {
          throw new Error('Fallback HTTP error ' + res.status);
        }
        return res.text();
      })
      .then(text => {
        try {
          const data = JSON.parse(text);
          renderNotifications(data.requests || []);
        } catch (parseErr) {
          throw new Error('Fallback invalid JSON response: ' + text);
        }
      })
      .catch(err => {
        console.error('edit_request: fallback notifications fetch failed', err);
        if (notificationDropdown) {
          notificationDropdown.innerHTML = '<div class="notification-empty">Tidak dapat memuat permintaan.</div>';
        }
      });
  }

  function approveRequest(requestId, button) {
    if (!requestId) return;
    button.disabled = true;
    fetch(base_url + 'editrequest/approve/' + requestId, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(res => {
        if (!res.ok) {
          throw new Error('HTTP error ' + res.status);
        }
        return res.json();
      })
      .then(data => {
        if (data.status === 'approved') {
          showToast('Permintaan edit berhasil disetujui', 'success');
          const arsipId = button.getAttribute('data-arsip-id');
          if (arsipId) updateRowStatus(arsipId, 'approved');
          fetchNotifications();
          refreshPendingBadge();
        } else {
          throw new Error('Gagal menyetujui permintaan');
        }
      })
      .catch(err => {
        console.error(err);
        showToast('Gagal menyetujui permintaan', 'danger');
      })
      .finally(() => {
        button.disabled = false;
      });
  }

  function rejectRequest(requestId, button) {
    if (!requestId) return;
    button.disabled = true;
    fetch(base_url + 'editrequest/reject/' + requestId, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(res => {
        if (!res.ok) {
          throw new Error('HTTP error ' + res.status);
        }
        return res.json();
      })
      .then(data => {
        if (data.status === 'rejected') {
          showToast('Permintaan edit ditolak', 'danger');
          const arsipId = button.getAttribute('data-arsip-id');
          if (arsipId) updateRowStatus(arsipId, 'rejected');
          fetchNotifications();
          refreshPendingBadge();
        } else {
          throw new Error('Gagal menolak permintaan');
        }
      })
      .catch(err => {
        console.error(err);
        showToast('Gagal menolak permintaan', 'danger');
      })
      .finally(() => {
        button.disabled = false;
      });
  }

  // Update the status cell for a row identified by arsip id
  function updateRowStatus(arsipId, status) {
    console.debug('edit_request: updateRowStatus called', arsipId, status);
    if (!arsipId) return;
    const row = document.querySelector('tr[data-arsip-id="' + arsipId + '"]');
    if (!row) return;
    const cell = row.querySelector('.status-cell');
    if (!cell) return;
    if (status === 'pending') {
      cell.innerHTML = '<span class="status-dot status-pending" title="Menunggu Persetujuan"></span> Menunggu Persetujuan';
      console.debug('edit_request: row updated to pending for', arsipId);
    } else if (status === 'approved') {
      cell.innerHTML = '<span class="status-dot status-approved" title="Disetujui"></span> Disetujui';
      console.debug('edit_request: row updated to approved for', arsipId);
    } else if (status === 'rejected') {
      cell.innerHTML = '<span class="status-dot status-rejected" title="Ditolak"></span> Ditolak';
      console.debug('edit_request: row updated to rejected for', arsipId);
    } else {
      cell.innerHTML = '<span class="status-text">Belum Diajukan</span>';
      console.debug('edit_request: row updated to neutral for', arsipId);
    }
  }

  // Expose helper for manual testing from browser console
  window.testUpdateRowStatus = updateRowStatus;
  window.refreshPendingBadge = refreshPendingBadge;

  if (notificationToggle && notificationDropdown) {
    notificationToggle.addEventListener('click', function (event) {
      event.stopPropagation();
      notificationDropdown.classList.toggle('open');
      if (notificationDropdown.classList.contains('open')) {
        fetchNotifications();
      }
    });

    document.addEventListener('click', function (event) {
      if (notificationDropdown.classList.contains('open') && !notificationDropdown.contains(event.target) && event.target !== notificationToggle) {
        notificationDropdown.classList.remove('open');
      }
    });

    notificationDropdown.addEventListener('click', function (event) {
      const approveBtn = event.target.closest('.approve-btn');
      const rejectBtn = event.target.closest('.reject-btn');
      if (approveBtn) {
        const requestId = approveBtn.getAttribute('data-request-id');
        approveRequest(requestId, approveBtn);
      }
      if (rejectBtn) {
        const requestId = rejectBtn.getAttribute('data-request-id');
        rejectRequest(requestId, rejectBtn);
      }
    });
  }

  // Initial load and interval
  refreshPendingBadge();
  setInterval(refreshPendingBadge, 30000);
}

function initEditRequestWrapper() {
  if (window.editRequestInitDone) return;
  window.editRequestInitDone = true;
  initEditRequest();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initEditRequestWrapper);
} else {
  initEditRequestWrapper();
}

// Simple toast helper
function showToast(message, type = 'info') {
  const toastContainer = document.getElementById('toast-container');
  if (!toastContainer) return;
  const toastEl = document.createElement('div');
  toastEl.className = `custom-toast custom-toast-${type}`;
  toastEl.textContent = message;
  toastContainer.appendChild(toastEl);
  setTimeout(() => {
    toastEl.classList.add('custom-toast-hide');
  }, 2800);
  toastEl.addEventListener('transitionend', () => {
    if (toastEl.parentNode) {
      toastEl.parentNode.removeChild(toastEl);
    }
  });
}
