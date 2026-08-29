/* Alo Story — shared product card / grid rendering. */

function productCardHTML(product, index) {
  const wishOn = isWishlisted(product.id);
  return (
    '<article class="product-card reveal" style="--reveal-delay:' + (index % 4) * 60 + 'ms">' +
    '<div class="product-media tint-' + product.tint + '" style="background:var(--tint-' + product.tint + ')">' +
    (product.tag ? '<span class="product-tag' + (product.tag === 'New' ? ' tag-new' : '') + '">' + product.tag + '</span>' : '') +
    '<button type="button" class="wishlist-btn" data-wishlist="' + product.id + '" aria-pressed="' + wishOn + '" aria-label="' + (wishOn ? 'Remove ' + product.name + ' from wishlist' : 'Add ' + product.name + ' to wishlist') + '">' +
    icon('heart-line', 'icon-heart-line') + icon('heart-fill', 'icon-heart-fill') +
    '</button>' +
    '<a href="product.html?id=' + product.id + '" class="art" aria-hidden="true" tabindex="-1">' + icon(categoryIconName(product.category)) + '</a>' +
    '<div class="quick-add">' +
    '<button type="button" class="btn btn-primary btn-block btn-sm" data-quick-add="' + product.id + '">Add to Bag</button>' +
    '</div>' +
    '</div>' +
    '<div class="product-info">' +
    '<span class="eyebrow" style="margin-bottom:4px">' + CATEGORY_LABELS[product.category] + '</span>' +
    '<h3><a href="product.html?id=' + product.id + '">' + product.name + '</a></h3>' +
    starRowWithCount(product.rating, product.reviews) +
    '<div class="product-meta">' +
    '<span class="price">' + (product.compareAt ? '<span class="was">' + formatPrice(product.compareAt) + '</span>' : '') + formatPrice(product.price) + '</span>' +
    '</div>' +
    (product.colors && product.colors.length ? '<div class="swatches" aria-hidden="true">' + product.colors.map((c) => '<span class="swatch" style="background:' + c + '"></span>').join('') + '</div>' : '') +
    '</div>' +
    '</article>'
  );
}

function starRowWithCount(rating, reviews) {
  return '<div class="gap-2 text-sm" style="margin:4px 0">' + starRow(rating) + '<span class="muted">(' + reviews + ')</span></div>';
}

function renderProductGrid(container, products) {
  if (!container) return;
  if (products.length === 0) {
    container.innerHTML =
      '<div class="empty-state">' + icon('search', 'icon-lg') +
      '<h3>No pieces found</h3>' +
      '<p>Try a different category or search term.</p>' +
      '</div>';
    return;
  }
  container.innerHTML = products.map((p, i) => productCardHTML(p, i)).join('');
  initWishlistButtons(container);
  observeReveal(container);
  container.querySelectorAll('[data-quick-add]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const product = getProductById(btn.dataset.quickAdd);
      if (!product) return;
      addToCart(product.id, 1, product.variants && product.variants.length ? product.variants[0] : null);
      showToast('Added to bag', product.name, 'cart');
    });
  });
}
