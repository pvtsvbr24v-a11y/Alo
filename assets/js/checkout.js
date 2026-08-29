/* Alo Story — checkout: order summary + demo order placement (no backend). */

function renderCheckoutSummary() {
  const lines = cartLinesWithProducts();
  const miniWrap = document.getElementById('checkout-mini-lines');
  miniWrap.innerHTML = lines
    .map(
      (l) =>
        '<div class="summary-mini-line">' +
        '<div class="cart-line-media">' + icon(categoryIconName(l.product.category)) + '</div>' +
        '<div style="flex:1"><h5>' + l.product.name + '</h5><span class="text-xs muted">Qty ' + l.qty + (l.variant ? ' · ' + l.variant : '') + '</span></div>' +
        '<span class="text-sm">' + formatPrice(l.product.price * l.qty) + '</span>' +
        '</div>'
    )
    .join('');

  const totals = cartTotals();
  document.getElementById('co-subtotal').textContent = formatPrice(totals.subtotal);
  document.getElementById('co-shipping').textContent = totals.shipping === 0 ? 'Free' : formatPrice(totals.shipping);
  document.getElementById('co-total').textContent = formatPrice(totals.total);
  document.getElementById('place-order-total').textContent = formatPrice(totals.total);

  const discountRow = document.getElementById('co-discount-row');
  if (totals.discount > 0) {
    discountRow.style.display = '';
    document.getElementById('co-discount').textContent = '−' + formatPrice(totals.discount);
  } else {
    discountRow.style.display = 'none';
  }
}

function generateOrderNumber() {
  return 'ALO-' + Math.floor(100000 + Math.random() * 900000);
}

document.addEventListener('DOMContentLoaded', () => {
  if (cartCount() === 0) {
    document.getElementById('checkout-wrap').hidden = true;
    document.getElementById('checkout-empty').hidden = false;
    hydrateIcons(document.getElementById('checkout-empty'));
    return;
  }

  renderCheckoutSummary();
  document.addEventListener('cart:change', renderCheckoutSummary);

  document.getElementById('checkout-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const fields = e.target.querySelectorAll('.field');
    let valid = true;
    fields.forEach((f) => {
      if (!validateField(f)) valid = false;
    });
    if (!valid) {
      const firstError = e.target.querySelector('.field.has-error input, .field.has-error select');
      if (firstError) firstError.focus();
      return;
    }

    const orderNumber = generateOrderNumber();
    document.getElementById('order-number').textContent = orderNumber;
    clearCart();

    document.getElementById('checkout-wrap').hidden = true;
    document.getElementById('confirmation-wrap').hidden = false;
    hydrateIcons(document.getElementById('confirmation-wrap'));
    document.title = 'Order Confirmed — Alo Story';
    document.getElementById('confirmation-wrap').scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});
