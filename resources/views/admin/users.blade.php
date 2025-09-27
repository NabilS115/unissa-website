@extends('layouts.app')

@section('title', 'User Management - Admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                    <p class="text-gray-600 mt-1">Manage platform users and their permissions</p>
                </div>
                <div class="flex gap-3">
                    <button id="add-user-btn" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add User
                    </button>
                    <button id="export-users-btn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Users
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-semibold text-gray-900" id="total-users">{{ $totalUsers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-semibold text-gray-900" id="active-users">{{ $activeUsers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Admins</dt>
                            <dd class="text-lg font-semibold text-gray-900" id="admin-users">{{ $adminUsers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New This Month</dt>
                            <dd class="text-lg font-semibold text-gray-900" id="new-users">{{ $newUsers ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                    <div class="relative">
                        <input type="text" id="user-search" placeholder="Search by name or email..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Filter</label>
                    <select id="role-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                    <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="clear-filters" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Users List</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviews</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Users will be loaded here -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mb-4"></div>
                                    <p class="text-gray-600">Loading users...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div id="user-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 id="modal-title" class="text-2xl font-bold text-gray-900">Add New User</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="user-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
                
                <div id="password-section">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password (when editing)</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center pt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" checked
                                   class="rounded border-gray-300 text-teal-600">
                            <span class="ml-2 text-sm text-gray-700">Active Account</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeUserModal()" 
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-medium transition-colors">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentSearch = '';
let currentRoleFilter = '';
let currentStatusFilter = '';
let isEditing = false;
let editingUserId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    setupEventListeners();
});

function setupEventListeners() {
    // Search functionality
    document.getElementById('user-search').addEventListener('input', debounce(function(e) {
        currentSearch = e.target.value;
        currentPage = 1;
        loadUsers();
    }, 300));
    
    // Filter functionality
    document.getElementById('role-filter').addEventListener('change', function(e) {
        currentRoleFilter = e.target.value;
        currentPage = 1;
        loadUsers();
    });
    
    document.getElementById('status-filter').addEventListener('change', function(e) {
        currentStatusFilter = e.target.value;
        currentPage = 1;
        loadUsers();
    });
    
    // Clear filters
    document.getElementById('clear-filters').addEventListener('click', function() {
        document.getElementById('user-search').value = '';
        document.getElementById('role-filter').value = '';
        document.getElementById('status-filter').value = '';
        currentSearch = '';
        currentRoleFilter = '';
        currentStatusFilter = '';
        currentPage = 1;
        loadUsers();
    });
    
    // Add user button
    document.getElementById('add-user-btn').addEventListener('click', function() {
        showUserModal();
    });
    
    // User form submission
    document.getElementById('user-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveUser();
    });
    
    // Export users
    document.getElementById('export-users-btn').addEventListener('click', function() {
        exportUsers();
    });
}

async function loadUsers() {
    const tableBody = document.getElementById('users-table-body');
    
    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="6" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mb-4"></div>
                    <p class="text-gray-600">Loading users...</p>
                </div>
            </td>
        </tr>
    `;
    
    try {
        const params = new URLSearchParams({
            page: currentPage,
            search: currentSearch,
            role: currentRoleFilter,
            status: currentStatusFilter
        });
        
        const response = await fetch(`/admin/users/api?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            displayUsers(data.users);
            updatePagination(data.pagination);
            updateStats(data.stats);
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-red-600">
                        Failed to load users. Please try again.
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error loading users:', error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-red-600">
                    Network error occurred. Please try again.
                </td>
            </tr>
        `;
    }
}

function displayUsers(users) {
    const tableBody = document.getElementById('users-table-body');
    
    if (users.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No users found</h3>
                        <p class="text-gray-600">Try adjusting your search criteria</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    const usersHtml = users.map(user => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <img src="${user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=14b8a6&color=fff'}" 
                         alt="${user.name}" class="w-10 h-10 rounded-full object-cover">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        <div class="text-sm text-gray-500">${user.email}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'}">
                    ${user.role === 'admin' ? 'Admin' : 'User'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${user.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(user.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${user.reviews_count || 0}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center gap-2">
                    <button onclick="editUser(${user.id})" class="text-teal-600 hover:text-teal-900 transition-colors">
                        Edit
                    </button>
                    <button onclick="toggleUserStatus(${user.id})" class="text-blue-600 hover:text-blue-900 transition-colors">
                        ${user.is_active ? 'Deactivate' : 'Activate'}
                    </button>
                    ${user.id !== {{ auth()->id() }} ? `
                        <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900 transition-colors">
                            Delete
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
    
    tableBody.innerHTML = usersHtml;
}

function updatePagination(pagination) {
    const container = document.getElementById('pagination-container');
    
    if (pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let paginationHtml = '<div class="flex items-center justify-between">';
    paginationHtml += `<div class="text-sm text-gray-700">Showing ${pagination.from} to ${pagination.to} of ${pagination.total} results</div>`;
    paginationHtml += '<div class="flex items-center gap-2">';
    
    // Previous button
    if (pagination.current_page > 1) {
        paginationHtml += `<button onclick="changePage(${pagination.current_page - 1})" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>`;
    }
    
    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.total_pages, pagination.current_page + 2); i++) {
        const isActive = i === pagination.current_page;
        paginationHtml += `<button onclick="changePage(${i})" class="px-3 py-2 text-sm ${isActive ? 'bg-teal-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-300 rounded-md">${i}</button>`;
    }
    
    // Next button
    if (pagination.current_page < pagination.total_pages) {
        paginationHtml += `<button onclick="changePage(${pagination.current_page + 1})" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</button>`;
    }
    
    paginationHtml += '</div></div>';
    container.innerHTML = paginationHtml;
}

function updateStats(stats) {
    document.getElementById('total-users').textContent = stats.total || 0;
    document.getElementById('active-users').textContent = stats.active || 0;
    document.getElementById('admin-users').textContent = stats.admins || 0;
    document.getElementById('new-users').textContent = stats.new_this_month || 0;
}

function changePage(page) {
    currentPage = page;
    loadUsers();
}

function showUserModal(user = null) {
    isEditing = user !== null;
    editingUserId = user ? user.id : null;
    
    const modal = document.getElementById('user-modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('user-form');
    const passwordSection = document.getElementById('password-section');
    
    title.textContent = isEditing ? 'Edit User' : 'Add New User';
    
    if (isEditing) {
        form.name.value = user.name;
        form.email.value = user.email;
        form.role.value = user.role;
        form.is_active.checked = user.is_active;
        form.password.required = false;
        passwordSection.querySelector('p').style.display = 'block';
    } else {
        form.reset();
        form.password.required = true;
        passwordSection.querySelector('p').style.display = 'none';
    }
    
    modal.classList.remove('hidden');
}

function closeUserModal() {
    document.getElementById('user-modal').classList.add('hidden');
}

async function saveUser() {
    const form = document.getElementById('user-form');
    const formData = new FormData(form);
    
    // Convert checkbox to boolean
    formData.set('is_active', formData.get('is_active') ? '1' : '0');
    
    try {
        const url = isEditing ? `/admin/users/${editingUserId}` : '/admin/users';
        const method = 'POST';
        
        if (isEditing) {
            formData.append('_method', 'PUT');
        }
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert(result.message || 'User saved successfully!');
            closeUserModal();
            loadUsers();
        } else {
            alert(result.message || 'Failed to save user.');
        }
    } catch (error) {
        alert('Network error occurred.');
    }
}

async function editUser(userId) {
    try {
        const response = await fetch(`/admin/users/${userId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            const user = await response.json();
            showUserModal(user);
        } else {
            alert('Failed to load user data.');
        }
    } catch (error) {
        alert('Network error occurred.');
    }
}

async function toggleUserStatus(userId) {
    if (!confirm('Are you sure you want to change this user\'s status?')) return;
    
    try {
        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert(result.message || 'User status updated successfully!');
            loadUsers();
        } else {
            alert(result.message || 'Failed to update user status.');
        }
    } catch (error) {
        alert('Network error occurred.');
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert(result.message || 'User deleted successfully!');
            loadUsers();
        } else {
            alert(result.message || 'Failed to delete user.');
        }
    } catch (error) {
        alert('Network error occurred.');
    }
}

async function exportUsers() {
    try {
        const params = new URLSearchParams({
            search: currentSearch,
            role: currentRoleFilter,
            status: currentStatusFilter
        });
        
        const response = await fetch(`/admin/users/export?${params}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'users_export.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        } else {
            alert('Failed to export users.');
        }
    } catch (error) {
        alert('Network error occurred.');
    }
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
