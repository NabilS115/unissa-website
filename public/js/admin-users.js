(() => {
  function safe(fn){ try{ fn(); } catch(e){ console.error(e); } }
  const bs = window.__adminUsers || {};
  const getCsrf = () => bs.csrf || (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) || '';
  const authId = typeof bs.authId !== 'undefined' ? bs.authId : null;

  let currentPage = 1;
  let currentSearch = '';
  let currentAdminLevelFilter = '';
  let currentStatusFilter = '';
  let includeDeleted = false;
  let isEditing = false;
  let editingUserId = null;

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => { clearTimeout(timeout); func(...args); };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  async function loadUsers(){
    const tableBody = document.getElementById('users-table-body');
    if(!tableBody) return;
    tableBody.innerHTML = `\n        <tr>\n            <td colspan="6" class="px-6 py-12 text-center">\n                <div class="flex flex-col items-center">\n                    <div class="animate-spin rounded-full h-8 w-8 border-4 border-teal-200 border-t-teal-600 mb-4"></div>\n                    <p class="text-gray-600">Loading users...</p>\n                </div>\n            </td>\n        </tr>\n    `;

    try{
      const params = new URLSearchParams({ page: currentPage, search: currentSearch, admin_level: currentAdminLevelFilter, status: currentStatusFilter, include_deleted: includeDeleted });
      
      // Add timeout to the fetch request
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
      
      const response = await fetch(`/admin/users/api?${params}`, { 
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': getCsrf() }, 
        signal: controller.signal 
      });
      
      clearTimeout(timeoutId);
      
      if(response.ok){ 
        const data = await response.json(); 
        console.log('API Response:', data); // Debug log
        displayUsers(data.users||[]); 
        updatePagination(data.pagination||{}); 
        updateStats(data.stats||{}); 
      } else { 
        console.error('API Error:', response.status, response.statusText);
        tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-600">Failed to load users (${response.status}). Please try again.</td></tr>`; 
      }
    }catch(e){ 
      console.error('Error loading users', e); 
      if(e.name === 'AbortError') {
        tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-600">Request timed out. Please check your connection and try again.</td></tr>`;
      } else {
        tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-600">Network error: ${e.message}. Please try again.</td></tr>`; 
      }
    }
  }

  function displayUsers(users){
    const tableBody = document.getElementById('users-table-body'); if(!tableBody) return;
    if(!users || users.length===0){ tableBody.innerHTML = `\n            <tr>\n                <td colspan="6" class="px-6 py-12 text-center">\n                    <div class="flex flex-col items-center">\n                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">\n                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"/>\n                        </svg>\n                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No users found</h3>\n                        <p class="text-gray-600">Try adjusting your search criteria</p>\n                    </div>\n                </td>\n            </tr>\n        `; return; }

    const usersHtml = users.map(user => {
      const avatar = user.profile_photo_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=14b8a6&color=fff');
      const adminLevelColors = {
        'super_admin': 'bg-red-100 text-red-800',
        'admin': 'bg-purple-100 text-purple-800',
        'moderator': 'bg-blue-100 text-blue-800',
        'user': 'bg-gray-100 text-gray-800'
      };
      const roleBadge = adminLevelColors[user.admin_level] || 'bg-gray-100 text-gray-800';
      const statusBadge = user.deleted_at ? 'bg-gray-100 text-gray-800' : (user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
      const statusText = user.deleted_at ? 'Deleted' : (user.is_active ? 'Active' : 'Inactive');
      
      let actionButtons = '';
      if (user.deleted_at) {
        // Deleted user actions
        actionButtons = `
          <button onclick="restoreUser(${user.id})" class="text-green-600 hover:text-green-900 transition-colors">Restore</button>
          <button onclick="forceDeleteUser(${user.id})" class="text-red-600 hover:text-red-900 transition-colors">Permanent Delete</button>
        `;
      } else {
        // Active/inactive user actions
        const editBtn = `<button onclick="editUser(${user.id})" class="text-teal-600 hover:text-teal-900 transition-colors">Edit</button>`;
        const toggleBtn = `<button onclick="toggleUserStatus(${user.id})" class="text-blue-600 hover:text-blue-900 transition-colors">${user.is_active ? 'Deactivate' : 'Activate'}</button>`;
        const deleteBtn = (authId && user.id !== authId) ? `<button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>` : '';
        actionButtons = `${editBtn} ${toggleBtn} ${deleteBtn}`;
      }

      return `\n        <tr class="hover:bg-gray-50 ${user.deleted_at ? 'opacity-60' : ''}">\n            <td class="px-6 py-4 whitespace-nowrap">\n                <div class="flex items-center">\n                    <img src="${avatar}" alt="${user.name}" class="w-10 h-10 rounded-full object-cover">\n                    <div class="ml-4">\n                        <div class="text-sm font-medium text-gray-900">${user.name}</div>\n                        <div class="text-sm text-gray-500">${user.email}</div>\n                    </div>\n                </div>\n            </td>\n            <td class="px-6 py-4 whitespace-nowrap">\n                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${roleBadge}">${user.admin_level ? user.admin_level.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'User'}</span>\n            </td>\n            <td class="px-6 py-4 whitespace-nowrap">\n                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusBadge}">${statusText}</span>\n            </td>\n            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">\n                ${new Date(user.created_at).toLocaleDateString()}\n            </td>\n            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">\n                ${user.reviews_count ?? 0}\n            </td>\n            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">\n                <div class="flex items-center gap-2">\n                    ${actionButtons}\n                </div>\n            </td>\n        </tr>\n      `;
    }).join('');

    tableBody.innerHTML = usersHtml;
  }

  function updatePagination(pagination){
    const container = document.getElementById('pagination-container'); if(!container) return;
    if(!pagination || pagination.total_pages <= 1){ container.innerHTML = ''; return; }
    let paginationHtml = '<div class="flex items-center justify-between">';
    paginationHtml += `<div class="text-sm text-gray-700">Showing ${pagination.from} to ${pagination.to} of ${pagination.total} results</div>`;
    paginationHtml += '<div class="flex items-center gap-2">';
    if(pagination.current_page > 1) paginationHtml += `<button onclick="changePage(${pagination.current_page - 1})" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>`;
    for(let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.total_pages, pagination.current_page + 2); i++){ const isActive = i === pagination.current_page; paginationHtml += `<button onclick="changePage(${i})" class="px-3 py-2 text-sm ${isActive ? 'bg-teal-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-300 rounded-md">${i}</button>`; }
    if(pagination.current_page < pagination.total_pages) paginationHtml += `<button onclick="changePage(${pagination.current_page + 1})" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</button>`;
    paginationHtml += '</div></div>';
    container.innerHTML = paginationHtml;
  }

  function updateStats(stats){ if(!stats) return; const set = (id, v)=>{ const el=document.getElementById(id); if(el) el.textContent = v||0; }; set('total-users', stats.total); set('active-users', stats.active); set('admin-users', stats.admins); set('new-users', stats.new_this_month); }

  function changePage(page){ currentPage = page; loadUsers(); }

  function showUserModal(user=null){
    isEditing = user !== null; editingUserId = user ? user.id : null;
    const modal = document.getElementById('user-modal'); const title = document.getElementById('modal-title'); const form = document.getElementById('user-form');
    if(!modal || !form) return;
    title.textContent = isEditing ? 'Edit User' : 'Add New User';
    if(isEditing){ 
      form.name.value = user.name; form.email.value = user.email; form.admin_level.value = user.admin_level; form.is_active.checked = !!user.is_active; 
    }
    else { 
      form.reset(); 
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeUserModal(){ const modal = document.getElementById('user-modal'); if(modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } }

  async function saveUser(){
    const form = document.getElementById('user-form'); if(!form) return; const formData = new FormData(form); formData.set('is_active', formData.get('is_active') ? '1' : '0');
    try{
      const url = isEditing ? `/admin/users/${editingUserId}` : '/admin/users'; if(isEditing) formData.append('_method','PUT');
      const response = await fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' }, body: formData });
      const result = await response.json().catch(()=>({}));
      if(response.ok){ alert(result.message || 'User saved successfully!'); closeUserModal(); loadUsers(); } else { alert(result.message || 'Failed to save user.'); }
    }catch(e){ console.error(e); alert('Network error occurred.'); }
  }

  async function editUser(userId){ try{ const response = await fetch(`/admin/users/${userId}`, { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': getCsrf() } }); if(response.ok){ const user = await response.json(); showUserModal(user); } else { alert('Failed to load user data.'); } } catch(e){ console.error(e); alert('Network error occurred.'); } }

  async function toggleUserStatus(userId){ if(!confirm('Are you sure you want to change this user\'s status?')) return; try{ const response = await fetch(`/admin/users/${userId}/toggle-status`, { method:'PATCH', headers:{ 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' } }); const result = await response.json().catch(()=>({})); if(response.ok){ alert(result.message || 'User status updated successfully!'); loadUsers(); } else { alert(result.message || 'Failed to update user status.'); } } catch(e){ console.error(e); alert('Network error occurred.'); } }

  async function deleteUser(userId){ if(!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return; try{ const response = await fetch(`/admin/users/${userId}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' } }); const result = await response.json().catch(()=>({})); if(response.ok){ alert(result.message || 'User deleted successfully!'); loadUsers(); } else { alert(result.message || 'Failed to delete user.'); } } catch(e){ console.error(e); alert('Network error occurred.'); } }

  async function exportUsers(){ try{ const params = new URLSearchParams({ search: currentSearch, admin_level: currentAdminLevelFilter, status: currentStatusFilter }); const response = await fetch(`/admin/users/export?${params}`, { headers:{ 'X-CSRF-TOKEN': getCsrf() } }); if(response.ok){ const blob = await response.blob(); const url = window.URL.createObjectURL(blob); const a = document.createElement('a'); a.style.display='none'; a.href = url; a.download = 'users_export.csv'; document.body.appendChild(a); a.click(); window.URL.revokeObjectURL(url); } else { alert('Failed to export users.'); } } catch(e){ console.error('Export error:', e); alert('Export failed.'); } }

  async function restoreUser(userId){ if(!confirm('Are you sure you want to restore this user?')) return; try{ const response = await fetch(`/admin/users/${userId}/restore`, { method:'POST', headers:{ 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' } }); const result = await response.json().catch(()=>({})); if(response.ok){ alert(result.message || 'User restored successfully!'); loadUsers(); } else { alert(result.message || 'Failed to restore user.'); } } catch(e){ console.error(e); alert('Network error occurred.'); } }

  async function forceDeleteUser(userId){ if(!confirm('Are you sure you want to permanently delete this user? This action cannot be undone and will remove all their data permanently!')) return; try{ const response = await fetch(`/admin/users/${userId}/force-delete`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' } }); const result = await response.json().catch(()=>({})); if(response.ok){ alert(result.message || 'User permanently deleted!'); loadUsers(); } else { alert(result.message || 'Failed to permanently delete user.'); } } catch(e){ console.error(e); alert('Network error occurred.'); } }

  // Event wiring
  document.addEventListener('DOMContentLoaded', function(){
    const searchEl = document.getElementById('user-search'); if(searchEl) searchEl.addEventListener('input', debounce(function(e){ currentSearch = e.target.value; currentPage = 1; loadUsers(); },300));
    const adminLevelEl = document.getElementById('admin-level-filter'); if(adminLevelEl) adminLevelEl.addEventListener('change', function(e){ currentAdminLevelFilter = e.target.value; currentPage = 1; loadUsers(); });
    const statusEl = document.getElementById('status-filter'); if(statusEl) statusEl.addEventListener('change', function(e){ currentStatusFilter = e.target.value; currentPage = 1; loadUsers(); });
    const includeDeletedEl = document.getElementById('include-deleted'); if(includeDeletedEl) includeDeletedEl.addEventListener('change', function(e){ includeDeleted = e.target.checked; currentPage = 1; loadUsers(); });
    const clearBtn = document.getElementById('clear-filters'); if(clearBtn) clearBtn.addEventListener('click', function(){ const us = document.getElementById('user-search'); if(us) us.value=''; if(adminLevelEl) adminLevelEl.value=''; if(statusEl) statusEl.value=''; const incDel = document.getElementById('include-deleted'); if(incDel) incDel.checked=false; currentSearch=''; currentAdminLevelFilter=''; currentStatusFilter=''; includeDeleted=false; currentPage=1; loadUsers(); });
    const addBtn = document.getElementById('add-user-btn'); if(addBtn) addBtn.addEventListener('click', function(){ showUserModal(); });
    const form = document.getElementById('user-form'); if(form) form.addEventListener('submit', function(e){ e.preventDefault(); saveUser(); });
    const exportBtn = document.getElementById('export-users-btn'); if(exportBtn) exportBtn.addEventListener('click', function(){ exportUsers(); });
    loadUsers();
  });

  // Expose some functions globally (used by inline onclicks in the rendered table)
  window.editUser = editUser;
  window.toggleUserStatus = toggleUserStatus;
  window.deleteUser = deleteUser;
  window.changePage = changePage;
  window.closeUserModal = closeUserModal;
  window.showUserModal = showUserModal;
  window.restoreUser = restoreUser;
  window.forceDeleteUser = forceDeleteUser;

})();
