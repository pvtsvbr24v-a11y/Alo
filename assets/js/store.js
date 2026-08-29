/* Alo Story — cart, wishlist, and toast engine.
   Everything persists to localStorage; this is a front-end demo store
   with no backend, so nothing here talks to a server. */

const CART_KEY = 'alo-cart-v1';
const WISHLIST_KEY = 'alo-wishlist-v1';
const PROMO_KEY = 'alo-promo-v1';
const FREE_SHIPPING_THRESHOLD = 50;
const FLAT_SHIPPING = 5;
const PROMO_CODES = { READ10: 0.1 };

/* ---------------- storage helpers ---------------- */
function readJSON(key, fallback) {
  try {
    const raw = localStorage.getItem(key);
    return raw ? JSON.parse(raw) : fallback;
  } catch (e) {
    return fallback;
  }
}
function writeJSON(key, value) {
  try {
    localStorage.setItem(key, JSON.stringify(value));
  } catch (e) {
    /* storage unavailable (private mode, quota) — fail silently, cart just won't persist */
  }
}

/* ---------------- cart ---------------- */
function getCart() {
  return readJSON(CART_KEY, []);
}
function saveCart(items) {
  writeJSON(CART_KEY, items);
  document.dispatchEvent(new CustomEvent('cart:change'));
}
function cartLineKey(id, variant) {
  return id + '::' + (variant || '');
}
function addToCart(id, qty, variant) {
  const items = getCart();
  const key = cartLineKey(id, variant);
  const existing = items.find((i) => cartLineKey(i.id, i.variant) === key);
  if (existing) {
    existing.qty += qty;
  } else {
    items.push({ id, variant: variant || null, qty });
  }
  saveCart(items);
}
function updateCartQty(id, variant, qty) {
  let items = getCart();
  const key = cartLineKey(id, variant);
  if (qty <= 0) {
    items = items.filter((i) => cartLineKey(i.id, i.variant) !== key);
  } else {
    const line = items.find((i) => cartLineKey(i.id, i.variant) === key);
    if (line) line.qty = qty;
  }
  saveCart(items);
}
function removeFromCart(id, variant) {
  updateCartQty(id, variant, 0);
}
function cartCount() {
  return getCart().reduce((sum, i) => sum + i.qty, 0);
}
function cartLinesWithProducts() {
  return getCart()
    .map((line) => {
      const product = getProductById(line.id);
      return product ? Object.assign({}, line, { product }) : null;
    })
    .filter(Boolean);
}
function cartSubtotal() {
  return cartLinesWithProducts().reduce((sum, l) => sum + l.product.price * l.qty, 0);
}
function activePromoRate() {
  const code = readJSON(PROMO_KEY, null);
  return code && PROMO_CODES[code] ? PROMO_CODES[code] : 0;
}
function activePromoCode() {
  const code = readJSON(PROMO_KEY, null);
  return code && PROMO_CODES[code] ? code : null;
}
function setPromoCode(code) {
  const upper = (code || '').trim().toUpperCase();
  if (PROMO_CODES[upper]) {
    writeJSON(PROMO_KEY, upper);
    return true;
  }
  return false;
}
function clearPromoCode() {
  writeJSON(PROMO_KEY, null);
}
function cartTotals() {
  const subtotal = cartSubtotal();
  const discount = subtotal * activePromoRate();
  const discounted = subtotal - discount;
  const shipping = subtotal === 0 || discounted >= FREE_SHIPPING_THRESHOLD ? 0 : FLAT_SHIPPING;
  const total = discounted + shipping;
  return { subtotal, discount, shipping, total };
}
function clearCart() {
  saveCart([]);
  clearPromoCode();
}

/* ---------------- wishlist ---------------- */
function getWishlist() {
  return readJSON(WISHLIST_KEY, []);
}
function isWishlisted(id) {
  return getWishlist().includes(id);
}
function toggleWishlist(id) {
  let list = getWishlist();
  const on = list.includes(id);
  list = on ? list.filter((x) => x !== id) : list.concat(id);
  writeJSON(WISHLIST_KEY, list);
  document.dispatchEvent(new CustomEvent('wishlist:change'));
  return !on;
}

/* ---------------- toast ---------------- */
function ensureToastRegion() {
  let region = document.querySelector('.toast-region');
  if (!region) {
    region = document.createElement('div');
    region.className = 'toast-region';
    region.setAttribute('aria-live', 'polite');
    region.setAttribute('aria-atomic', 'true');
    document.body.appendChild(region);
  }
  return region;
}
function showToast(title, detail, iconName) {
  const region = ensureToastRegion();
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML =
    icon(iconName || 'check') +
    '<span><strong>' + title + '</strong>' + (detail ? '<span class="detail">' + detail + '</span>' : '') + '</span>';
  region.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('is-visible'));
  const remove = () => {
    toast.classList.remove('is-visible');
    setTimeout(() => toast.remove(), 300);
  };
  setTimeout(remove, 3800);
}

/* ---------------- cart badge + drawer wiring (shared across pages) ---------------- */
function updateCartBadges() {
  const count = cartCount();
  document.querySelectorAll('.cart-count').forEach((el) => {
    const prev = el.textContent;
    el.textContent = String(count);
    el.hidden = count === 0;
    if (prev !== String(count) && count > 0) {
      el.classList.remove('bump');
      void el.offsetWidth;
      el.classList.add('bump');
    }
  });
}

function renderCartDrawer() {
  const body = document.getElementById('cart-drawer-body');
  const foot = document.getElementById('cart-drawer-foot');
  if (!body) return;
  const lines = cartLinesWithProducts();
  if (lines.length === 0) {
    body.innerHTML =
      '<div class="empty-cart">' +
      icon('cart') +
      '<p>Your bag is empty.</p>' +
      '<a href="shop.html" class="link-underline mt-8">Start shopping ' + icon('arrow-right', 'icon-sm') + '</a>' +
      '</div>';
    if (foot) foot.style.display = 'none';
    return;
  }
  if (foot) foot.style.display = '';
  body.innerHTML = lines
    .map((l) => {
      const p = l.product;
      return (
        '<div class="cart-line">' +
        '<div class="cart-line-media">' + icon(categoryIconName(p.category)) + '</div>' +
        '<div class="cart-line-info">' +
        '<h4><a href="product.html?id=' + p.id + '">' + p.name + '</a></h4>' +
        (l.variant ? '<div class="variant">' + l.variant + '</div>' : '') +
        '<div class="cart-line-qty-price">' +
        '<div class="stepper" data-line="' + l.id + '" data-variant="' + (l.variant || '') + '">' +
        '<button type="button" data-step="-1" aria-label="Decrease quantity">' + icon('minus', 'icon-sm') + '</button>' +
        '<input type="text" inputmode="numeric" value="' + l.qty + '" aria-label="Quantity" readonly>' +
        '<button type="button" data-step="1" aria-label="Increase quantity">' + icon('plus', 'icon-sm') + '</button>' +
        '</div>' +
        '<span class="price">' + formatPrice(p.price * l.qty) + '</span>' +
        '</div>' +
        '<button type="button" class="cart-line-remove" data-remove-line data-id="' + p.id + '" data-variant="' + (l.variant || '') + '">Remove</button>' +
        '</div>' +
        '</div>'
      );
    })
    .join('');
  const subtotalEl = document.getElementById('cart-drawer-subtotal');
  if (subtotalEl) subtotalEl.textContent = formatPrice(cartSubtotal());
}

function openCartDrawer() {
  const drawer = document.getElementById('cart-drawer');
  if (!drawer) return;
  renderCartDrawer();
  drawer.classList.add('is-open');
  drawer.setAttribute('aria-hidden', 'false');
  document.body.classList.add('no-scroll');
  const closeBtn = drawer.querySelector('[data-cart-close]');
  if (closeBtn) closeBtn.focus();
}
function closeCartDrawer() {
  const drawer = document.getElementById('cart-drawer');
  if (!drawer) return;
  drawer.classList.remove('is-open');
  drawer.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('no-scroll');
}

function initCartUI() {
  updateCartBadges();
  document.addEventListener('cart:change', () => {
    updateCartBadges();
    renderCartDrawer();
  });

  document.querySelectorAll('[data-open-cart]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openCartDrawer();
    });
  });
  document.querySelectorAll('[data-cart-close]').forEach((btn) => {
    btn.addEventListener('click', closeCartDrawer);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeCartDrawer();
  });

  const body = document.getElementById('cart-drawer-body');
  if (body) {
    body.addEventListener('click', (e) => {
      const stepBtn = e.target.closest('[data-step]');
      if (stepBtn) {
        const stepper = stepBtn.closest('.stepper');
        const id = stepper.dataset.line;
        const variant = stepper.dataset.variant || null;
        const current = getCart().find((i) => cartLineKey(i.id, i.variant) === cartLineKey(id, variant));
        if (current) updateCartQty(id, variant, current.qty + Number(stepBtn.dataset.step));
        return;
      }
      const removeBtn = e.target.closest('[data-remove-line]');
      if (removeBtn) {
        removeFromCart(removeBtn.dataset.id, removeBtn.dataset.variant || null);
      }
    });
  }
}

function initWishlistButtons(root) {
  (root || document).querySelectorAll('[data-wishlist]').forEach((btn) => {
    const id = btn.dataset.wishlist;
    btn.setAttribute('aria-pressed', String(isWishlisted(id)));
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const on = toggleWishlist(id);
      btn.setAttribute('aria-pressed', String(on));
      showToast(on ? 'Saved to wishlist' : 'Removed from wishlist', getProductById(id) ? getProductById(id).name : '', on ? 'heart-fill' : 'heart-line');
    });
  });
}
