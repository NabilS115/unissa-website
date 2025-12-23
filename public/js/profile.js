// Extracted profile page JS
// Expects window.__profile to be set by the Blade view with keys:
// { csrf, routes: { photoUpload, photoDelete, paymentMethod }, defaultProfileUrl, hasCustomPhoto }

(function(){
    const P = window.__profile || {};
    const CSRF = P.csrf || document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const ROUTES = P.routes || {};

    // --- Basic validators ---
    function setMessage(elId, text, ok){
        const el = document.getElementById(elId);
        if (!el) return;
        // If text is empty, hide the validation element for a cleaner initial UI
        if (!text) {
            el.textContent = '';
            el.classList.add('hidden');
            return;
        }
        el.classList.remove('hidden');
        el.textContent = text;
        el.className = ok ? 'mt-2 text-sm text-green-600' : 'mt-2 text-sm text-red-600';
    }

    function validateName(){
        const name = (document.getElementById('name')||{}).value || '';
        if (!name.trim()) { setMessage('name-validation','',true); return; }
        const ok = /^([A-Za-z\s]{2,})$/.test(name);
        setMessage('name-validation', ok ? 'Valid name!' : 'Name must be at least 2 letters.', ok);
    }
    function validateEmail(){
        const email = (document.getElementById('email')||{}).value || '';
        if (!email.trim()) { setMessage('email-validation','',true); return; }
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const ok = re.test(email);
        setMessage('email-validation', ok ? 'Valid email!' : 'Please enter a valid email address.', ok);
    }
    function validatePhone(){
        const phone = (document.getElementById('phone')||{}).value || '';
        const re = /^(\+673[- ]?)?\d{7}$/;
        if (phone.length === 0) { setMessage('phone-validation','',true); return; }
        setMessage('phone-validation', re.test(phone) ? 'Valid phone number!' : 'Please enter a valid Brunei phone number.', re.test(phone));
    }
    function validateDepartment(){
        const department = (document.getElementById('department')||{}).value || '';
        if (!department.trim()) { setMessage('department-validation','',true); return; }
        const ok = /^([A-Za-z\s]{2,})$/.test(department);
        setMessage('department-validation', ok ? 'Valid department!' : 'Department must be at least 2 letters.', ok);
    }

    // --- Card validators ---
    function validateCardholderName(){
        const nm = (document.getElementById('cardholder_name')||{}).value || '';
        if (!nm.trim()) { setMessage('cardholder-name-validation','',true); return; }
        const ok = nm.trim().length >= 2;
        setMessage('cardholder-name-validation', ok ? 'Looks good.' : 'Name must be at least 2 characters.', ok);
    }

    function validateCardNumber(){
        const num = ((document.getElementById('card_number')||{}).value || '').replace(/\s/g,'');
        const msgId = 'card-number-validation';
        if (!num) { setMessage(msgId,'',true); return; }
        const re = /^\d{16}$/;
        const ok = re.test(num);
        setMessage(msgId, ok ? 'Valid card number.' : 'Card number must be 16 digits.', ok);
    }

    function validateCardExpiry(){
        const expiry = (document.getElementById('card_expiry')||{}).value || '';
        const msgId = 'card-expiry-validation';
        if (!expiry) { setMessage(msgId,'',true); return; }
        const re = /^(0[1-9]|1[0-2])\/(\d{2})$/;
        if (!re.test(expiry)) { setMessage(msgId,'Expiry must be MM/YY.',false); return; }
        const [mm, yy] = expiry.split('/');
        const yyyy = parseInt(yy,10) + 2000;
        const expDate = new Date(yyyy, parseInt(mm,10)-1, 1);
        const now = new Date();
        const ok = expDate >= new Date(now.getFullYear(), now.getMonth(), 1);
        setMessage(msgId, ok ? 'Valid expiry date.' : 'Card is expired.', ok);
    }

    function validateCardCCV(){
        const ccv = (document.getElementById('card_ccv')||{}).value || '';
        if (!ccv) { setMessage('card-ccv-validation','',true); return; }
        // Enforce exactly 3 digits for CCV (3 is standard). If 4-digit CCV support is ever required
        // (e.g., for specific card schemes), update this regex accordingly.
        const ok = (/^\d{3}$/).test(ccv);
        setMessage('card-ccv-validation', ok ? 'Valid CCV.' : 'CCV must be 3 digits.', ok);
    }

    function validateBankAccount(input){
        if (!input) return;
        let value = input.value || '';
        const msg = document.getElementById('bank-account-validation');
        value = value.replace(/[^0-9]/g,'');
        input.value = value;
        if (!/^[0-9]{16}$/.test(value)) {
            if (msg) { msg.textContent = 'Account number must be exactly 16 digits, no spaces.'; msg.className='mt-2 text-sm text-red-600'; }
            input.classList.remove('border-[#0d9488]','focus:ring-[#0d9488]');
            input.classList.add('border-red-500','focus:ring-red-500');
        } else {
            if (msg) { msg.textContent = 'Valid account number.'; msg.className='mt-2 text-sm text-green-600'; }
            input.classList.remove('border-red-500','focus:ring-red-500');
            input.classList.add('border-[#0d9488]','focus:ring-[#0d9488]');
        }
    }

    // --- Tabs ---
    // Global tab switcher (exposed for debugging/fallbacks)
    function showProfileTab(tab){
        console.log('showProfileTab called with:', tab);
        try{ const tabContents = document.querySelectorAll('.tab-content'); console.log('Found tab contents:', tabContents.length); tabContents.forEach(c=>{ c.classList.add('hidden'); c.classList.remove('tab-visible'); }); }catch(e){console.error('Error hiding tabs:', e);}
        const el = document.getElementById('tab-content-' + tab);
        console.log('Target tab element:', el);
        if (el){ el.classList.remove('hidden'); el.classList.add('tab-visible'); console.log('Showed tab:', tab); }
        try{ const tabBtns = document.querySelectorAll('.tab-btn'); tabBtns.forEach(b=>b.classList.remove('border-teal-500','text-teal-900')); }catch(e){console.error('Error with tab buttons:', e);}
        const btn = document.querySelector('.tab-btn[data-tab="'+tab+'"]');
        if (btn) btn.classList.add('border-teal-500','text-teal-900');
    }
    // (no globals exposed) keep showProfileTab internal for production
    
    // Temporarily expose for debugging
    window.showProfileTab = showProfileTab;

    function initTabs(){
        console.log('initTabs called');
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        console.log('Found tab buttons:', tabBtns.length);
        console.log('Found tab contents:', tabContents.length);
        try{}catch(e){}
        if (!tabBtns.length) return;
        tabBtns.forEach(btn=>btn.addEventListener('click', function(e){ e.preventDefault(); const t = this.dataset && this.dataset.tab; console.log('Tab clicked:', t); if (t) showProfileTab(t); }));
        // show default
        console.log('Showing default profile tab');
        showProfileTab('profile');
    }

    // --- Payment fields toggle & AJAX save ---
    function togglePaymentFields(){
        const method = (document.getElementById('payment_method')||{}).value;
        const card = document.getElementById('card-fields');
        const bank = document.getElementById('bank-fields');
        if (card) card.style.display = (method === 'credit_card') ? 'block' : 'none';
        if (bank) bank.style.display = (method === 'bank_transfer') ? 'block' : 'none';
    }

    function initPaymentAjax(){
        const form = document.getElementById('payment-method-form');
        const btn = document.getElementById('save-payment-btn');
        if (!form || !btn) return;
        btn.disabled = false; btn.style.pointerEvents = 'auto';
        btn.addEventListener('click', ()=> showProfileToast('Saving payment method...', false));
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const bank = document.getElementById('bank_name');
            const account = document.getElementById('bank_account');
            if (bank && account && (!bank.value || !account.value)) { showProfileToast('Please fill in both bank and account number.', true); return; }
            const formData = new FormData(form); formData.append('_method','PUT');
            fetch(form.action, { method: 'POST', headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }, body: formData })
                .then(async response => {
                    let data = null; try { data = await response.json(); } catch(e){}
                    if (response.ok) showProfileToast('Payment method updated!', false);
                    else showProfileToast((data && data.message) ? data.message : 'Failed to update payment method.', true);
                }).catch(()=> showProfileToast('Failed to update payment method.', true));
        });
    }

    // --- Profile form AJAX ---
    function getFirstErrorMessage(data){
        if (!data) return null;
        if (data.message) return data.message;
        if (data.errors){
            const keys = Object.keys(data.errors);
            if (keys.length){
                const first = data.errors[keys[0]];
                if (Array.isArray(first)) return first.join(' ');
                return String(first);
            }
        }
        return null;
    }

    // Clear previous inline field errors inside a form
    function clearFieldErrors(form){
        if (!form) return;
        // remove generated ajax-field-error elements
        form.querySelectorAll('.ajax-field-error').forEach(el => el.remove());
        // restore input border classes where we added error styles
        form.querySelectorAll('input, select, textarea').forEach(inp => {
            try{
                inp.classList.remove('border-red-300','focus:ring-red-500');
            }catch(e){}
        });
        // clear any named validation placeholders (e.g. name-validation) and hide them
        form.querySelectorAll('[id$="-validation"]').forEach(el => { el.textContent = ''; try{ el.classList.add('hidden'); }catch(e){} });
    }

    // Render server-side field errors inline inside the form
    function renderFieldErrors(form, errors){
        if (!form || !errors) return;
        Object.keys(errors).forEach(field => {
            const messages = errors[field];
            const msg = Array.isArray(messages) ? messages.join(' ') : String(messages);
            // try to find input by name
            let input = form.querySelector('[name="' + field + '"]');
            if (!input){
                // maybe dotted names, try replacing [] or . with _ or direct id
                input = document.getElementById(field) || form.querySelector('[name="' + field.replace('.','\.') + '"]');
            }
            // find existing validation element
            let validationEl = document.getElementById(field + '-validation');
            if (!validationEl && input){
                // create a new p after the input
                validationEl = document.createElement('p');
                validationEl.className = 'ajax-field-error mt-2 text-sm text-red-600';
                validationEl.id = field + '-validation';
                if (input.parentNode){
                    // place after input or after wrapper
                    if (input.nextSibling) input.parentNode.insertBefore(validationEl, input.nextSibling);
                    else input.parentNode.appendChild(validationEl);
                }
            }
            if (validationEl) {
                try{ validationEl.classList.remove('hidden'); }catch(e){}
                validationEl.textContent = msg;
            }
            if (input){
                try{ input.classList.add('border-red-300','focus:ring-red-500'); }catch(e){}
            }
        });
    }

    function initProfileAjax(){
        const form = document.getElementById('profile-form');
        if (!form) return;
        const submitBtn = form.querySelector('[type="submit"]');
        form.addEventListener('submit', function(e){
            e.preventDefault();
            // clear any previous inline errors
            clearFieldErrors(form);
            if (submitBtn) submitBtn.disabled = true;
            const fd = new FormData(form);
            // include method override if present
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput && methodInput.value) fd.append('_method', methodInput.value);
            fetch(form.action, { method: 'POST', headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }, body: fd })
                .then(async res => {
                    const contentType = res.headers.get('content-type') || '';
                    let data = null;
                    if (contentType.indexOf('application/json') !== -1) {
                        try { data = await res.json(); } catch(e){ data = null; }
                    } else {
                        const text = await res.text();
                        console.warn('[profile.js] Profile update returned non-JSON response', text);
                        throw new Error('Unexpected server response');
                    }

                    if (data && data.errors){
                        // show inline field errors
                        renderFieldErrors(form, data.errors);
                        const msg = getFirstErrorMessage(data) || 'Failed to update profile.';
                        showProfileToast(msg, true);
                        return;
                    }

                    if (data && (data.success === false || data.status === 'error' || data.status === 'failed')){
                        const msg = data.message || 'Failed to update profile.';
                        showProfileToast(msg, true);
                        return;
                    }

                    if (res.ok){
                        // clear any inline errors
                        clearFieldErrors(form);
                        showProfileToast((data && data.message) ? data.message : 'Profile updated successfully!', false);
                        // If server returned updated user data, update visible UI without reload
                        try{
                            const user = data && (data.data || data.user) ? (data.data || data.user) : null;
                            if (user) {
                                // header dropdown
                                const hdrName = document.querySelector('#profileDropdown .font-bold');
                                if (hdrName) hdrName.textContent = user.name || '';
                                const hdrEmail = document.querySelector('#profileDropdown .text-sm');
                                if (hdrEmail) hdrEmail.textContent = user.email || '';
                                // profile page heading (edit-profile)
                                    // Try several places where the large profile name might appear
                                    const profileHeadingSelectors = [
                                        '#tab-content-profile h1.text-3xl.font-bold',
                                        'h1.text-3xl.font-bold.text-gray-900',
                                        '.profile-page-heading',
                                    ];
                                    let profileHeading = null;
                                    for (let s of profileHeadingSelectors) {
                                        profileHeading = document.querySelector(s);
                                        if (profileHeading) break;
                                    }
                                    if (profileHeading) profileHeading.textContent = user.name || '';
                                // generic data-user-name attributes
                                document.querySelectorAll('[data-user-name]').forEach(el=>{ el.textContent = user.name || ''; });
                            }
                        }catch(e){ console.error('Failed to update UI after profile save', e); }
                    } else {
                        const msg = getFirstErrorMessage(data) || 'Failed to update profile.';
                        showProfileToast(msg, true);
                    }
                }).catch(err => { showProfileToast(typeof err === 'string' ? err : (err && err.message) ? err.message : 'Network error while saving profile.', true); })
                .finally(()=>{ if (submitBtn) submitBtn.disabled = false; });
        });
    }

    // --- Password form AJAX ---
    function initPasswordAjax(){
        const form = document.getElementById('password-form');
        if (!form) return;
        const submitBtn = form.querySelector('[type="submit"]');
        form.addEventListener('submit', function(e){
            e.preventDefault();
            // client-side validation: ensure new password matches confirmation and is different from current
            const current = (form.querySelector('#current_password') || form.querySelector('input[name="current_password"]') || {}).value || '';
            const nw = (form.querySelector('#password') || form.querySelector('input[name="password"]') || {}).value || '';
            const conf = (form.querySelector('#password_confirmation') || form.querySelector('input[name="password_confirmation"]') || {}).value || '';

            if (nw.length < 8){ showProfileToast('New password must be at least 8 characters.', true); return; }
            if (nw !== conf){ showProfileToast('New password and confirmation do not match.', true); return; }
            if (current && current === nw){ showProfileToast('New password must be different from the current password.', true); return; }

            // clear any previous inline errors
            clearFieldErrors(form);
            if (submitBtn) submitBtn.disabled = true;
            const fd = new FormData(form);
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput && methodInput.value) fd.append('_method', methodInput.value);
            fetch(form.action, { method: 'POST', headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }, body: fd })
                .then(async res => {
                    const contentType = res.headers.get('content-type') || '';
                    let data = null;
                    if (contentType.indexOf('application/json') !== -1) {
                        try { data = await res.json(); } catch(e){ data = null; }
                    } else {
                        // Non-JSON response; treat as failure to avoid falsely reporting success
                        const text = await res.text();
                        console.warn('[profile.js] Password change returned non-JSON response', text);
                        throw new Error('Unexpected server response');
                    }

                    // If server returned validation errors, show them inline
                    if (data && data.errors) {
                        renderFieldErrors(form, data.errors);
                        const msg = getFirstErrorMessage(data) || 'Failed to change password.';
                        showProfileToast(msg, true);
                        return;
                    }

                    // server may set a failure flag/message
                    if (data && (data.success === false || data.status === 'error' || data.status === 'failed')){
                        const msg = data.message || 'Failed to change password.';
                        showProfileToast(msg, true);
                        return;
                    }

                    if (res.ok) {
                        // clear inline errors
                        clearFieldErrors(form);
                        showProfileToast((data && data.message) ? data.message : 'Password changed successfully!', false);
                        // clear password fields
                        form.querySelectorAll('input[type="password"], input[type="text"]').forEach(i=>{ i.value = ''; i.type = 'password'; });
                        // re-initialize icons to show open-eye
                        document.querySelectorAll('button.password-toggle[data-target]').forEach(btn => {
                            try{ const t = btn.dataset && btn.dataset.target; const input = t && document.getElementById(t); if (input && btn.querySelector('svg')){ const svg = btn.querySelector('svg'); const eyeOpen = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" /><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />'; svg.innerHTML = eyeOpen; btn.title = 'Show password'; btn.setAttribute('aria-pressed','false'); }
                            }catch(e){}
                        });
                    } else {
                        const msg = getFirstErrorMessage(data) || 'Failed to change password.';
                        showProfileToast(msg, true);
                    }
                }).catch(err => { showProfileToast(typeof err === 'string' ? err : (err && err.message) ? err.message : 'Network error while changing password.', true); })
                .finally(()=>{ if (submitBtn) submitBtn.disabled = false; });
        });
    }

    // --- Toast ---
    function showProfileToast(msg, isErr){
        const toast = document.getElementById('profile-toast'); if (!toast) return;
        toast.querySelector('.toast-message').textContent = msg;
        toast.classList.remove('bg-green-500','bg-red-500','opacity-0','hidden');
        toast.classList.add(isErr? 'bg-red-500':'bg-green-500');
        toast.style.display = 'flex'; toast.style.opacity = '1'; toast.style.pointerEvents='none';
        setTimeout(()=>{ toast.classList.add('opacity-0'); toast.style.opacity='0'; setTimeout(()=>{ toast.style.display='none'; document.dispatchEvent(new Event('profile-toast-hide')); },300); }, 2500);
    }

    // --- Photo modal & cropper ---
    let cropperInstance = null;
    function openPhotoModal(){
        const modal = document.getElementById('photo-modal'); if (!modal) return;
        modal.classList.remove('hidden'); modal.classList.add('flex'); modal.setAttribute('aria-hidden','false');
        document.documentElement.style.overflow='hidden'; document.body.style.overflow='hidden';
        document.addEventListener('keydown', photoModalKeyHandler);
        modal.addEventListener('click', photoModalBackdropHandler);
        setTimeout(()=>{ const closeBtn = document.getElementById('photo-modal-close'); if (closeBtn) closeBtn.focus(); },50);
    }
    function closePhotoModal(){
        const modal = document.getElementById('photo-modal'); if (!modal) return;
        modal.classList.add('hidden'); modal.classList.remove('flex'); modal.setAttribute('aria-hidden','true');
        document.documentElement.style.overflow=''; document.body.style.overflow='';
        document.removeEventListener('keydown', photoModalKeyHandler);
        modal.removeEventListener('click', photoModalBackdropHandler);
    }
    function photoModalBackdropHandler(e){ const modal = document.getElementById('photo-modal'); if (!modal) return; if (e.target === modal) closePhotoModal(); }
    function photoModalKeyHandler(e){ if (e.key === 'Escape' || e.key === 'Esc') closePhotoModal(); }
    function showPhotoModalSpinner(msg){ const s = document.getElementById('photo-modal-spinner'); if (!s) return; const msgEl = s.querySelector('.spinner-message'); if (msgEl) { if (msg!==null) msgEl.textContent = msg; else if (!msgEl.textContent) msgEl.textContent='Uploading...'; } s.classList.remove('hidden'); s.classList.add('flex'); }
    function hidePhotoModalSpinner(){ const s = document.getElementById('photo-modal-spinner'); if (!s) return; s.classList.add('hidden'); s.classList.remove('flex'); }

    async function handlePhotoUpload(event){
        const file = event.target.files && event.target.files[0]; if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e){ const preview = document.getElementById('modal-photo-preview'); if (preview) preview.src = e.target.result; const overlay = document.getElementById('profile-photo'); if (overlay) overlay.src = e.target.result; const headerImg = document.querySelector('#profileMenuButton img'); if (headerImg) headerImg.src = e.target.result; };
        reader.readAsDataURL(file);
        const fd = new FormData(); fd.append('profile_photo', file);
        showPhotoModalSpinner();
        try{
            const res = await fetch(ROUTES.photoUpload || P.routes && P.routes.photoUpload || '', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
            hidePhotoModalSpinner();
            if (res.ok){ closePhotoModal(); try{ const data = await res.json(); const url = data.url || (data.data && data.data.url) || null; if (url){ const overlay = document.getElementById('profile-photo'); const preview = document.getElementById('modal-photo-preview'); const headerImg = document.querySelector('#profileMenuButton img'); if (overlay) overlay.src = url; if (preview) preview.src = url; if (headerImg) headerImg.src = url; const uploadBtn = document.getElementById('upload-btn'); const changeBtn = document.getElementById('change-btn'); const deleteBtn = document.getElementById('delete-btn'); if (uploadBtn) uploadBtn.classList.add('hidden'); if (changeBtn) changeBtn.classList.remove('hidden'); if (deleteBtn) deleteBtn.classList.remove('hidden'); if (typeof Swal !== 'undefined') { Swal.fire({ icon: 'success', title: 'Success!', text: 'Profile photo updated successfully!', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' }); } } } catch(e){} }
            else{ let msg = 'Failed to upload image.'; try{ const err = await res.json(); if (err && err.message) msg = err.message; else if (err && err.errors) msg = Object.values(err.errors).flat().join(' '); }catch(e){} if (typeof Swal !== 'undefined') { Swal.fire({ icon: 'error', title: 'Upload Failed', text: msg || 'Failed to upload profile photo. Please try again.', confirmButtonColor: '#0d9488' }); } else { alert(msg || 'Failed to upload profile photo. Please try again.'); } }
        }catch(err){ hidePhotoModalSpinner(); if (typeof Swal !== 'undefined') { Swal.fire({ icon: 'error', title: 'Network Error', text: 'Unable to upload profile photo. Please check your connection and try again.', confirmButtonColor: '#0d9488' }); } else { alert('Unable to upload profile photo. Please check your connection and try again.'); } console.error('Upload error', err); }
    }

    async function deleteProfilePhoto(){ if (!confirm('Are you sure you want to delete your profile photo?')) return; showPhotoModalSpinner('Deleting...'); try{ const res = await fetch(ROUTES.photoDelete || P.routes && P.routes.photoDelete || '', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } }); hidePhotoModalSpinner(); const defaultPhoto = P.defaultProfileUrl || '{{ ASSET_PLACEHOLDER }}'; const modalPreview = document.getElementById('modal-photo-preview'); if (modalPreview) modalPreview.src = defaultPhoto; const overlay = document.getElementById('profile-photo'); if (overlay) overlay.src = defaultPhoto; const headerImg = document.querySelector('#profileMenuButton img'); if (headerImg) headerImg.src = defaultPhoto; document.querySelectorAll('img[data-profile-photo], img.profile-photo, img[src*="profile-photos/"]').forEach(img=>img.src = defaultPhoto); closePhotoModal(); }catch(err){ hidePhotoModalSpinner(); const defaultPhoto = P.defaultProfileUrl || '{{ ASSET_PLACEHOLDER }}'; const modalPreview = document.getElementById('modal-photo-preview'); if (modalPreview) modalPreview.src = defaultPhoto; const overlay = document.getElementById('profile-photo'); if (overlay) overlay.src = defaultPhoto; const headerImg = document.querySelector('#profileMenuButton img'); if (headerImg) headerImg.src = defaultPhoto; document.querySelectorAll('img[data-profile-photo], img.profile-photo, img[src*="profile-photos/"]').forEach(img=>img.src = defaultPhoto); closePhotoModal(); }
    }

    // Cropper helpers (uses global Cropper)
    function openCropperFromFile(e){ const input = e.target; const file = input && input.files && input.files[0]; if (!file) return; const url = URL.createObjectURL(file); const img = document.getElementById('cropper-image'); if (!img) return; img.src = url; showCropperArea(); if (cropperInstance){ try{ cropperInstance.destroy(); }catch(e){} cropperInstance = null; } img.onload = function(){ cropperInstance = new Cropper(img, { aspectRatio:1, viewMode:0, dragMode:'move', autoCropArea:0.9, restore:false, modal:true, guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, wheelZoomRatio:0.1, background:true }); }; }
    // If Cropper.js is not available, fallback to direct upload behavior
    function openCropperFromFile(e){ const input = e.target; const file = input && input.files && input.files[0]; if (!file) return; // fallback if Cropper not loaded
        if (typeof Cropper === 'undefined') { console.warn('Cropper.js not available; uploading directly'); return handlePhotoUpload(e); }
        const url = URL.createObjectURL(file); const img = document.getElementById('cropper-image'); if (!img) return; img.src = url; showCropperArea(); if (cropperInstance){ try{ cropperInstance.destroy(); }catch(e){} cropperInstance = null; } img.onload = function(){ try{ cropperInstance = new Cropper(img, { aspectRatio:1, viewMode:0, dragMode:'move', autoCropArea:0.9, restore:false, modal:true, guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, wheelZoomRatio:0.1, background:true }); }catch(err){ console.error('Failed to initialize cropper', err); } }; }
    function showCropperArea(){ const area = document.getElementById('cropper-area'); if (!area) return; area.classList.remove('hidden'); const modal = document.getElementById('photo-modal'); if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); } }
    function hideCropperArea(){ const area = document.getElementById('cropper-area'); if (!area) return; area.classList.add('hidden'); if (cropperInstance){ try{ cropperInstance.destroy(); }catch(e){} cropperInstance = null; } const fileInput = document.getElementById('photo-file-input'); if (fileInput) fileInput.value = ''; }
    function resetCropper(){ if (!cropperInstance) return; try{ cropperInstance.reset(); }catch(e){ console.warn('Reset failed', e); } }
    function cropAndUpload(){ if (!cropperInstance) return; cropperInstance.getCroppedCanvas({ width:600, height:600, fillColor:'#fff' }).toBlob(function(blob){ if (!blob) return; const fd = new FormData(); fd.append('profile_photo', blob, 'profile.jpg'); showPhotoModalSpinner(); fetch(ROUTES.photoUpload || P.routes && P.routes.photoUpload || '', { method:'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }, body: fd }).then(async res=>{ hidePhotoModalSpinner(); hideCropperArea(); if (res.ok){ closePhotoModal(); try{ const data = await res.json(); if (data && data.url){ const overlay = document.getElementById('profile-photo'); const preview = document.getElementById('modal-photo-preview'); const headerImg = document.querySelector('#profileMenuButton img'); if (overlay) overlay.src = data.url; if (preview) preview.src = data.url; if (headerImg) headerImg.src = data.url; const uploadBtn = document.getElementById('upload-btn'); const changeBtn = document.getElementById('change-btn'); const deleteBtn = document.getElementById('delete-btn'); if (uploadBtn) uploadBtn.classList.add('hidden'); if (changeBtn) changeBtn.classList.remove('hidden'); if (deleteBtn) deleteBtn.classList.remove('hidden'); } }catch(e){} } else { let msg = 'Failed to upload cropped image.'; try{ const err = await res.json(); if (err && err.message) msg = err.message; else if (err && err.errors) msg = Object.values(err.errors).flat().join(' '); }catch(e){} alert(msg); } }).catch(err=>{ hidePhotoModalSpinner(); hideCropperArea(); console.error('Upload error', err); alert('Failed to upload cropped image. Please check your connection and try again.'); }); }, 'image/jpeg', 0.9); }

    // --- Password toggle ---
    function togglePassword(id, btn){
        const input = document.getElementById(id);
        if (!input || !btn) return;
        const svg = btn.querySelector('svg');
        if (!svg) return;

        // SVG states: eyeOpen (visible), eyeClosed (hidden with slash)
        const eyeOpen = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" /><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />';
        const eyeClosed = eyeOpen + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';

        if (input.type === 'password'){
            // reveal -> show CLOSED (slashed) eye meaning "click to hide"
            input.type = 'text';
            svg.innerHTML = eyeClosed;
            btn.title = 'Hide password';
            btn.setAttribute('aria-label','Hide password');
            btn.setAttribute('aria-pressed','true');
        } else {
            // hide -> show OPEN eye meaning "click to show"
            input.type = 'password';
            svg.innerHTML = eyeOpen;
            btn.title = 'Show password';
            btn.setAttribute('aria-label','Show password');
            btn.setAttribute('aria-pressed','false');
        }
    }

    // Wire everything when the DOM is ready. Support scripts loaded after DOMContentLoaded.
    function onReady(fn){
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
        else fn();
    }

    onReady(function(){
        initTabs();
        togglePaymentFields();
        initPaymentAjax();
        initProfileAjax();
        initPasswordAjax();
        // validations
        ['name','email','phone','department','cardholder_name','card_number','card_expiry','card_ccv'].forEach(id => { const el = document.getElementById(id); if (el) el.addEventListener('input', function(){ try{ if (id==='name') validateName(); if (id==='email') validateEmail(); if (id==='phone') validatePhone(); if (id==='department') validateDepartment(); if (id==='cardholder_name') validateCardholderName(); if (id==='card_number') validateCardNumber(); if (id==='card_expiry') validateCardExpiry(); if (id==='card_ccv') validateCardCCV(); }catch(e){} }); });
        // bank account
        const bankInput = document.getElementById('bank_account'); if (bankInput) bankInput.addEventListener('input', function(){ validateBankAccount(this); });
        // photo modal buttons
        const uploadBtn = document.getElementById('upload-btn'); if (uploadBtn) uploadBtn.addEventListener('click', ()=> document.getElementById('photo-file-input').click());
        const changeBtn = document.getElementById('change-btn'); if (changeBtn) changeBtn.addEventListener('click', ()=> document.getElementById('photo-file-input').click());
        const deleteBtn = document.getElementById('delete-btn'); if (deleteBtn) deleteBtn.addEventListener('click', deleteProfilePhoto);
    const fileInput = document.getElementById('photo-file-input'); if (fileInput) fileInput.addEventListener('change', openCropperFromFile);
    // Bind the modal close button (no inline onclick)
    const closeBtn = document.getElementById('photo-modal-close'); if (closeBtn) closeBtn.addEventListener('click', function(e){ e.preventDefault(); try{ closePhotoModal(); }catch(err){ console.error('closePhotoModal not available', err); } });
    // Bind overlay click (avoid inline onclick and ensure handler exists when clicked)
    const photoOverlay = document.getElementById('profile-photo-overlay');
    if (photoOverlay) photoOverlay.addEventListener('click', function(e){ e.preventDefault(); try{ openPhotoModal(); }catch(err){ console.error('openPhotoModal is not available', err); } });
        // cropper bindings
        const cropCancel = document.getElementById('crop-cancel-btn'); if (cropCancel) cropCancel.addEventListener('click', hideCropperArea);
        const cropUpload = document.getElementById('crop-upload-btn'); if (cropUpload) cropUpload.addEventListener('click', cropAndUpload);
        const cropReset = document.getElementById('crop-reset-btn'); if (cropReset) cropReset.addEventListener('click', resetCropper);
        // password toggles - bind once to buttons with data-target to avoid double-calls
        document.querySelectorAll('button.password-toggle[data-target]').forEach(btn => {
            btn.addEventListener('click', function(e){ e.preventDefault(); const target = btn.dataset && btn.dataset.target; if (target) togglePassword(target, btn); });
            // initialize icon state according to current input type
            try{
                const t = btn.dataset && btn.dataset.target;
                const input = t && document.getElementById(t);
                if (input){
                    const svg = btn.querySelector('svg');
                    if (svg){
                        const eyeOpen = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" /><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />';
                        const eyeClosed = eyeOpen + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';
                        // behaviour: when input is type=password (hidden), show OPEN eye (meaning "click to show"); when visible (type=text), show CLOSED (with slash) meaning "click to hide"
                        if (input.type === 'password'){
                            svg.innerHTML = eyeOpen;
                            btn.title = 'Show password';
                            btn.setAttribute('aria-label','Show password');
                            btn.setAttribute('aria-pressed','false');
                        } else {
                            svg.innerHTML = eyeClosed;
                            btn.title = 'Hide password';
                            btn.setAttribute('aria-label','Hide password');
                            btn.setAttribute('aria-pressed','true');
                        }
                    }
                }
            }catch(e){}
        });
    // Don't run initial validation sweep on load — only validate on user input for a cleaner UI
    });

})();
