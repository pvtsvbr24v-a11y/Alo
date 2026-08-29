/* Alo Story — full cart page: line items, promo code, order summary. */

function cartRowHTML(line) {
  const p = line.product;
  return (
    '<div class="cart-row" data-line-id="' + p.id + '" data-line-variant="' + (line.variant || '') + '">' +
    '<div class="product-col">' +
    '<div class="cart-line-media">' + icon(categoryIconName(p.category)) + '</div>' +
    '<div class="cart-line-info">' +
    '<h4><a href="product.html?id=' + p.id + '">' + p.name + '</a></h4>' +
    (line.variant ? '<div class="variant">' + line.variant + '</div>' : '') +
    '<button type="button" class="cart-line-remove" data-remove-line>Remove</button>' +
    '</div>' +
    '</div>' +
    '<div><span class="col-label">Price</span>' + formatPrice(p.price) + '</div>' +
    '<div><span class="col-label">Quantity</span>' +
    '<div class="stepper">' +
    '<button type="button" data-step="-1" aria-label="Decrease quantity">' + icon('minus', 'icon-sm') + '</button>' +
    '<input type="text" inputmode="numeric" value="' + line.qty + '" aria-label="Quantity" readonly>' +
    '<button type="button" data-step="1" aria-label="Increase quantity">' + icon('plus', 'icon-sm') + '</button>' +
    '</div></div>' +
    '<div><span class="col-label">Total</span><strong>' + formatPrice(p.price * line.qty) + '</strong></div>' +
    '<div></div>' +
    '</div>'
  );
}

function renderCartPage() {
  const lines = cartLinesWithProducts();
  const rowsEl = document.getElementById('cart-rows');
  const headEl = document.querySelector('.cart-table-head');
  const summaryCard = document.getElementById('summary-card');

  if (lines.length === 0) {
    headEl.style.display = 'none';
    summaryCard.style.display = 'none';
    rowsEl.innerHTML =
      '<div class="empty-cart">' +
      icon('cart', 'icon-lg') +
      '<h3 class="mb-2">Your bag is empty</h3>' +
      '<p class="mb-5">Looks like you haven\'t added anything yet.</p>' +
      '<a href="shop.html" class="btn btn-primary">Start Shopping</a>' +
      '</div>';
    return;
  }

  headEl.style.display = '';
  summaryCard.style.display = '';
  rowsEl.innerHTML = lines.map(cartRowHTML).join('');

  const totals = cartTotals();
  document.getElementById('sum-subtotal').textContent = formatPrice(totals.subtotal);
  document.getElementById('sum-shipping').textContent = totals.shipping === 0 ? 'Free' : formatPrice(totals.shipping);
  document.getElementById('sum-total').textContent = formatPrice(totals.total);

  const discountRow = document.getElementById('sum-discount-row');
  const promoCode = activePromoCode();
  if (promoCode) {
    discountRow.style.display = '';
    document.getElementById('sum-promo-code').textContent = promoCode;
    document.getElementById('sum-discount').textContent = '−' + formatPrice(totals.discount);
  } else {
    discountRow.style.display = 'none';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  renderCartPage();
  document.addEventListener('cart:change', renderCartPage);

  const rowsEl = document.getElementById('cart-rows');
  rowsEl.addEventListener('click', (e) => {
    const row = e.target.closest('.cart-row');
    if (!row) return;
    const id = row.dataset.lineId;
    const variant = row.dataset.lineVariant || null;

    const stepBtn = e.target.closest('[data-step]');
    if (stepBtn) {
      const current = getCart().find((i) => cartLineKey(i.id, i.variant) === cartLineKey(id, variant));
      if (current) updateCartQty(id, variant, current.qty + Number(stepBtn.dataset.step));
      return;
    }
    if (e.target.closest('[data-remove-line]')) {
      removeFromCart(id, variant);
    }
  });

  const promoInput = document.getElementById('promo-input');
  const promoMsg = document.getElementById('promo-msg');
  document.getElementById('promo-apply').addEventListener('click', () => {
    if (!promoInput.value.trim()) return;
    const ok = setPromoCode(promoInput.value);
    promoMsg.textContent = ok ? 'Promo code applied!' : 'That code isn\'t valid.';
    promoMsg.style.color = ok ? 'var(--color-success)' : 'var(--color-error)';
    if (ok) {
      promoInput.value = '';
      renderCartPage();
    }
  });
});
