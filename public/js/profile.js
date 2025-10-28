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
        el.textContent = text;
        el.className = ok ? 'mt-2 text-sm text-green-600' : 'mt-2 text-sm text-red-600';
    }

    function validateName(){
        const name = (document.getElementById('name')||{}).value || '';
        setMessage('name-validation', /^([A-Za-z\s]{2,})$/.test(name) ? 'Valid name!' : 'Name must be at least 2 letters.', /^([A-Za-z\s]{2,})$/.test(name));
    }
    function validateEmail(){
        const email = (document.getElementById('email')||{}).value || '';
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        setMessage('email-validation', re.test(email) ? 'Valid email!' : 'Please enter a valid email address.', re.test(email));
    }
    function validatePhone(){
        const phone = (document.getElementById('phone')||{}).value || '';
        const re = /^(\+673[- ]?)?\d{7}$/;
        if (phone.length === 0) { setMessage('phone-validation','',true); return; }
        setMessage('phone-validation', re.test(phone) ? 'Valid phone number!' : 'Please enter a valid Brunei phone number.', re.test(phone));
    }
    function validateDepartment(){
        const department = (document.getElementById('department')||{}).value || '';
        setMessage('department-validation', /^([A-Za-z\s]{2,})$/.test(department) ? 'Valid department!' : 'Department must be at least 2 letters.', /^([A-Za-z\s]{2,})$/.test(department));
    }

    // --- Card validators ---
    function validateCardholderName(){
        const nm = (document.getElementById('cardholder_name')||{}).value || '';
        setMessage('cardholder-name-validation', nm.trim().length >= 2 ? 'Looks good.' : 'Name must be at least 2 characters.', nm.trim().length >= 2);
    }
    function validateCardNumber(){
        const num = ((document.getElementById('card_number')||{}).value || '').replace(/\s/g,'');
        const msgId = 'card-number-validation';
        const re = /^\d{16}$/;
        setMessage(msgId, re.test(num) ? 'Valid card number.' : 'Card number must be 16 digits.', re.test(num));
    }
    function validateCardExpiry(){
        const expiry = (document.getElementById('card_expiry')||{}).value || '';
        const msgId = 'card-expiry-validation';
        const re = /^(0[1-9]|1[0-2])\/(\d{2})$/;
        if (!re.test(expiry)) { setMessage(msgId,'Expiry must be MM/YY.',false); return; }
        const [mm, yy] = expiry.split('/');
        const yyyy = parseInt(yy,10) + 2000;
        const expDate = new Date(yyyy, parseInt(mm,10)-1, 1);
        const now = new Date();
        setMessage(msgId, expDate >= new Date(now.getFullYear(), now.getMonth(), 1) ? 'Valid expiry date.' : 'Card is expired.', expDate >= new Date(now.getFullYear(), now.getMonth(), 1));
    }
    function validateCardCCV(){
        const ccv = (document.getElementById('card_ccv')||{}).value || '';
        setMessage('card-ccv-validation', (/^\d{3,4}$/).test(ccv) ? 'Valid CCV.' : 'CCV must be 3 or 4 digits.', (/^\d{3,4}$/).test(ccv));
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
    function initTabs(){
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        if (!tabBtns.length) return;
        function showTab(tab){
            tabContents.forEach(c=>c.classList.add('hidden'));
            const el = document.getElementById('tab-content-' + tab);
            if (el) el.classList.remove('hidden');
            tabBtns.forEach(b=>b.classList.remove('border-teal-500','text-teal-900'));
            const btn = document.querySelector('.tab-btn[data-tab="'+tab+'"]');
            if (btn) btn.classList.add('border-teal-500','text-teal-900');
        }
        tabBtns.forEach(btn=>btn.addEventListener('click', function(){ showTab(this.dataset.tab); }));
        showTab('profile');
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
        setTimeout(()=>{ const closeBtn = modal.querySelector('button[onclick="closePhotoModal()"]'); if (closeBtn) closeBtn.focus(); },50);
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
            if (res.ok){ closePhotoModal(); try{ const data = await res.json(); const url = data.url || (data.data && data.data.url) || null; if (url){ const overlay = document.getElementById('profile-photo'); const preview = document.getElementById('modal-photo-preview'); const headerImg = document.querySelector('#profileMenuButton img'); if (overlay) overlay.src = url; if (preview) preview.src = url; if (headerImg) headerImg.src = url; const uploadBtn = document.getElementById('upload-btn'); const changeBtn = document.getElementById('change-btn'); const deleteBtn = document.getElementById('delete-btn'); if (uploadBtn) uploadBtn.classList.add('hidden'); if (changeBtn) changeBtn.classList.remove('hidden'); if (deleteBtn) deleteBtn.classList.remove('hidden'); } } catch(e){} }
            else{ let msg = 'Failed to upload image.'; try{ const err = await res.json(); if (err && err.message) msg = err.message; else if (err && err.errors) msg = Object.values(err.errors).flat().join(' '); }catch(e){} alert(msg); }
        }catch(err){ hidePhotoModalSpinner(); alert('Failed to upload image. Please check your connection and try again.'); console.error('Upload error', err); }
    }

    async function deleteProfilePhoto(){ if (!confirm('Are you sure you want to delete your profile photo?')) return; showPhotoModalSpinner('Deleting...'); try{ const res = await fetch(ROUTES.photoDelete || P.routes && P.routes.photoDelete || '', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } }); hidePhotoModalSpinner(); const defaultPhoto = P.defaultProfileUrl || '{{ ASSET_PLACEHOLDER }}'; const modalPreview = document.getElementById('modal-photo-preview'); if (modalPreview) modalPreview.src = defaultPhoto; const overlay = document.getElementById('profile-photo'); if (overlay) overlay.src = defaultPhoto; const headerImg = document.querySelector('#profileMenuButton img'); if (headerImg) headerImg.src = defaultPhoto; document.querySelectorAll('img[data-profile-photo], img.profile-photo, img[src*="profile-photos/"]').forEach(img=>img.src = defaultPhoto); closePhotoModal(); }catch(err){ hidePhotoModalSpinner(); const defaultPhoto = P.defaultProfileUrl || '{{ ASSET_PLACEHOLDER }}'; const modalPreview = document.getElementById('modal-photo-preview'); if (modalPreview) modalPreview.src = defaultPhoto; const overlay = document.getElementById('profile-photo'); if (overlay) overlay.src = defaultPhoto; const headerImg = document.querySelector('#profileMenuButton img'); if (headerImg) headerImg.src = defaultPhoto; document.querySelectorAll('img[data-profile-photo], img.profile-photo, img[src*="profile-photos/"]').forEach(img=>img.src = defaultPhoto); closePhotoModal(); }
    }

    // Cropper helpers (uses global Cropper)
    function openCropperFromFile(e){ const input = e.target; const file = input && input.files && input.files[0]; if (!file) return; const url = URL.createObjectURL(file); const img = document.getElementById('cropper-image'); if (!img) return; img.src = url; showCropperArea(); if (cropperInstance){ try{ cropperInstance.destroy(); }catch(e){} cropperInstance = null; } img.onload = function(){ cropperInstance = new Cropper(img, { aspectRatio:1, viewMode:0, dragMode:'move', autoCropArea:0.9, restore:false, modal:true, guides:true, center:true, highlight:true, cropBoxMovable:true, cropBoxResizable:true, toggleDragModeOnDblclick:false, responsive:true, checkOrientation:false, zoomable:true, wheelZoomRatio:0.1, background:true }); }; }
    function showCropperArea(){ const area = document.getElementById('cropper-area'); if (!area) return; area.classList.remove('hidden'); const modal = document.getElementById('photo-modal'); if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); } }
    function hideCropperArea(){ const area = document.getElementById('cropper-area'); if (!area) return; area.classList.add('hidden'); if (cropperInstance){ try{ cropperInstance.destroy(); }catch(e){} cropperInstance = null; } const fileInput = document.getElementById('photo-file-input'); if (fileInput) fileInput.value = ''; }
    function resetCropper(){ if (!cropperInstance) return; try{ cropperInstance.reset(); }catch(e){ console.warn('Reset failed', e); } }
    function cropAndUpload(){ if (!cropperInstance) return; cropperInstance.getCroppedCanvas({ width:600, height:600, fillColor:'#fff' }).toBlob(function(blob){ if (!blob) return; const fd = new FormData(); fd.append('profile_photo', blob, 'profile.jpg'); showPhotoModalSpinner(); fetch(ROUTES.photoUpload || P.routes && P.routes.photoUpload || '', { method:'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }, body: fd }).then(async res=>{ hidePhotoModalSpinner(); hideCropperArea(); if (res.ok){ closePhotoModal(); try{ const data = await res.json(); if (data && data.url){ const overlay = document.getElementById('profile-photo'); const preview = document.getElementById('modal-photo-preview'); const headerImg = document.querySelector('#profileMenuButton img'); if (overlay) overlay.src = data.url; if (preview) preview.src = data.url; if (headerImg) headerImg.src = data.url; const uploadBtn = document.getElementById('upload-btn'); const changeBtn = document.getElementById('change-btn'); const deleteBtn = document.getElementById('delete-btn'); if (uploadBtn) uploadBtn.classList.add('hidden'); if (changeBtn) changeBtn.classList.remove('hidden'); if (deleteBtn) deleteBtn.classList.remove('hidden'); } }catch(e){} } else { let msg = 'Failed to upload cropped image.'; try{ const err = await res.json(); if (err && err.message) msg = err.message; else if (err && err.errors) msg = Object.values(err.errors).flat().join(' '); }catch(e){} alert(msg); } }).catch(err=>{ hidePhotoModalSpinner(); hideCropperArea(); console.error('Upload error', err); alert('Failed to upload cropped image. Please check your connection and try again.'); }); }, 'image/jpeg', 0.9); }

    // --- Password toggle ---
    function togglePassword(id, btn){ const input = document.getElementById(id); if (!input || !btn) return; const svg = btn.querySelector('svg'); if (!svg) return; if (input.type === 'password'){ input.type='text'; svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.411"/>';
        btn.title='Hide password';
    } else { input.type='password'; svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" /><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />'; btn.title='Show password'; }
    }

    // Wire everything on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function(){
        initTabs();
        togglePaymentFields();
        initPaymentAjax();
        // validations
        ['name','email','phone','department','cardholder_name','card_number','card_expiry','card_ccv'].forEach(id => { const el = document.getElementById(id); if (el) el.addEventListener('input', function(){ try{ if (id==='name') validateName(); if (id==='email') validateEmail(); if (id==='phone') validatePhone(); if (id==='department') validateDepartment(); if (id==='cardholder_name') validateCardholderName(); if (id==='card_number') validateCardNumber(); if (id==='card_expiry') validateCardExpiry(); if (id==='card_ccv') validateCardCCV(); }catch(e){} }); });
        // bank account
        const bankInput = document.getElementById('bank_account'); if (bankInput) bankInput.addEventListener('input', function(){ validateBankAccount(this); });
        // photo modal buttons
        const uploadBtn = document.getElementById('upload-btn'); if (uploadBtn) uploadBtn.addEventListener('click', ()=> document.getElementById('photo-file-input').click());
        const changeBtn = document.getElementById('change-btn'); if (changeBtn) changeBtn.addEventListener('click', ()=> document.getElementById('photo-file-input').click());
        const deleteBtn = document.getElementById('delete-btn'); if (deleteBtn) deleteBtn.addEventListener('click', deleteProfilePhoto);
        const fileInput = document.getElementById('photo-file-input'); if (fileInput) fileInput.addEventListener('change', handlePhotoUpload);
        // cropper bindings
        const cropCancel = document.getElementById('crop-cancel-btn'); if (cropCancel) cropCancel.addEventListener('click', hideCropperArea);
        const cropUpload = document.getElementById('crop-upload-btn'); if (cropUpload) cropUpload.addEventListener('click', cropAndUpload);
        const cropReset = document.getElementById('crop-reset-btn'); if (cropReset) cropReset.addEventListener('click', resetCropper);
        // password toggles
        document.querySelectorAll('button[onclick^="togglePassword("]').forEach(btn => btn.addEventListener('click', function(){ const m = btn.getAttribute('onclick'); try{ const parts = m.match(/togglePassword\('([^']+)'/); if(parts && parts[1]) togglePassword(parts[1], btn); }catch(e){} }));
        // initial validation run
        try{ validateName(); validateEmail(); validatePhone(); validateDepartment(); validateCardholderName(); validateCardNumber(); validateCardExpiry(); validateCardCCV(); }catch(e){}
    });

})();
