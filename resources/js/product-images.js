// resources/js/product-images.js
(function () {
  'use strict';

  // Hindari init ganda (Vite HMR/duplikat include)
  if (window.__bbProductImagesInit) return;
  window.__bbProductImagesInit = true;

  // ---- Flags ----
  var flags   = document.getElementById('bb-form-flags') || null;
  var isEdit  = !!(flags && flags.getAttribute('data-is-edit') === '1');
  var minSel  = parseInt((flags && flags.getAttribute('data-min')) || '3', 10);
  var maxSel  = parseInt((flags && flags.getAttribute('data-max')) || '8', 10);

  // ---- Elemen ----
  var form        = document.getElementById('product-form');
  var input       = document.getElementById('images-input');
  var previewWrap = document.getElementById('new-preview');
  var grid        = document.getElementById('preview-grid');
  var counterEl   = document.getElementById('file-counter');

  if (!form || !input || !grid) return;

  // Matikan bubble native; minimal & max kita cek manual
  form.setAttribute('novalidate', 'novalidate');
  input.removeAttribute('required');

  // ---- Keranjang DataTransfer untuk pilih bertahap + hapus ----
  var supportsDT = false;
  var bag;
  try { bag = new DataTransfer(); supportsDT = !!bag && typeof bag.items !== 'undefined'; }
  catch (e) { supportsDT = false; }

  // Jika saat load input.files sudah terisi (beberapa browser), sinkronkan ke bag
  if (supportsDT && input.files && input.files.length) {
    Array.prototype.forEach.call(input.files, function (f) { bag.items.add(f); });
  }

  function isImageFile(f) { return f && typeof f.type === 'string' && f.type.indexOf('image/') === 0; }
  function clear(node){ while(node.firstChild) node.removeChild(node.firstChild); }
  function filesArray(){ return supportsDT ? Array.prototype.slice.call(bag.files || []) : Array.prototype.slice.call(input.files || []); }

  function updateCounter(n){
    var count = (typeof n === 'number') ? n : filesArray().length;
    if (counterEl) counterEl.textContent = count + ' / ' + maxSel;
  }

  function renderPreview(){
    var files = filesArray();
    clear(grid);

    if (!files.length){
      if (previewWrap) previewWrap.classList.add('d-none');
      updateCounter(0);
      return;
    }
    if (previewWrap) previewWrap.classList.remove('d-none');

    files.forEach(function(f, idx){
      var url = URL.createObjectURL(f);

      var wrap = document.createElement('div');
      wrap.className = 'position-relative border rounded overflow-hidden';

      var img = document.createElement('img');
      img.src = url; img.alt = f.name || 'preview';
      img.style.width = '100%';
      img.style.objectFit = 'cover';
      img.style.aspectRatio = '1 / 1';

      var label = document.createElement('div');
      label.className = 'p-1 small ' + (idx === 0 ? 'bg-primary text-white' : 'bg-light');
      label.textContent = idx === 0 ? 'Cover' : 'Galeri';

      wrap.appendChild(img);
      wrap.appendChild(label);

      if (supportsDT){
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1';
        btn.setAttribute('data-remove', String(idx));
        btn.setAttribute('aria-label', 'Hapus gambar');
        btn.appendChild(document.createTextNode('×'));
        wrap.appendChild(btn);
      }

      grid.appendChild(wrap);
    });

    updateCounter(files.length);
  }

  function addFiles(list){
    if (!supportsDT) return; // fallback: pilih sekali saja
    var arr = Array.prototype.slice.call(list || []);
    for (var i = 0; i < arr.length; i++){
      var f = arr[i];
      if (!isImageFile(f)) continue;
      if (bag.files.length >= maxSel){ alert('Maksimal ' + maxSel + ' file.'); break; }
      bag.items.add(f);
    }
    renderPreview();
  }

  function removeAt(index){
    if (!supportsDT) return;
    var next = new DataTransfer();
    Array.prototype.slice.call(bag.files).forEach(function(f,i){ if (i !== index) next.items.add(f); });
    bag = next;
    renderPreview();
  }

  // ==== EVENTS ====
  input.addEventListener('change', function(){
    if (!this.files || !this.files.length) return;

    if (supportsDT){
      var available = maxSel - bag.files.length;
      if (available <= 0){ alert('Maksimal ' + maxSel + ' file.'); this.value=''; return; }
      addFiles(Array.prototype.slice.call(this.files, 0, available));
      this.value = ''; // biar bisa pilih ulang file yang sama
    }else{
      if (this.files.length > maxSel){
        alert('Maksimal ' + maxSel + ' file. Hanya ' + maxSel + ' pertama yang dipakai.');
      }
      renderPreview();
    }
  });

  grid.addEventListener('click', function(e){
    var t = e.target || e.srcElement;
    while (t && t !== grid && !(t.tagName === 'BUTTON' && t.getAttribute('data-remove') !== null)) t = t.parentNode;
    if (!t || t === grid) return;
    var idx = parseInt(t.getAttribute('data-remove'), 10);
    if (!isNaN(idx)) removeAt(idx);
  });

  // ==== SUBMIT NORMAL HTML ====
  form.addEventListener('submit', function(ev){
    var n = filesArray().length;

    if (!isEdit && n < minSel){
      ev.preventDefault();
      alert('Minimal ' + minSel + ' gambar.');
      input.focus();
      return;
    }
    if (n > maxSel){
      ev.preventDefault();
      alert('Maksimal ' + maxSel + ' gambar.');
      return;
    }

    // Suntik balik ke input.files agar browser kirim multipart standar
    if (supportsDT){
      try { input.files = bag.files; } catch(e){}
    }
    // lalu biarkan submit normal (NO fetch). Laravel akan redirect seperti biasa.
  });

  // initial render
  renderPreview();
})();
