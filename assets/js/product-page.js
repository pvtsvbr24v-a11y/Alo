/* Alo Story — product detail page rendering + interactions. */

function getUrlProductId() {
  return new URLSearchParams(window.location.search).get('id');
}

function renderProductDetail(product) {
  const wishOn = isWishlisted(product.id);
  const hasVariants = product.variantLabel && product.variants && product.variants.length;

  document.getElementById('product-detail').innerHTML =
    '<div class="product-gallery">' +
    '<div class="product-gallery-main" id="gallery-main" style="background:var(--tint-' + product.tint + ')">' +
    icon(categoryIconName(product.category), 'gallery-icon') +
    '</div>' +
    (product.colors && product.colors.length > 1
      ? '<div class="product-gallery-thumbs" role="group" aria-label="Colorways">' +
        product.colors
          .map(
            (c, i) =>
              '<button type="button" data-thumb="' + i + '" aria-pressed="' + (i === 0) + '" aria-label="View colorway ' + (i + 1) + '" style="background:' + c + '22">' +
              icon(categoryIconName(product.category)) +
              '</button>'
          )
          .join('') +
        '</div>'
      : '') +
    '</div>' +
    '<div class="product-summary">' +
    '<span class="eyebrow">' + CATEGORY_LABELS[product.category] + '</span>' +
    '<h1>' + product.name + '</h1>' +
    '<div class="price-row">' +
    '<span class="price">' + (product.compareAt ? '<span class="was">' + formatPrice(product.compareAt) + '</span>' : '') + formatPrice(product.price) + '</span>' +
    starRowWithCount(product.rating, product.reviews) +
    '</div>' +
    '<p class="lede">' + product.description + '</p>' +
    (product.colors && product.colors.length
      ? '<div class="option-group"><div class="label-row"><span>Color</span></div><div class="swatch-row">' +
        product.colors.map((c, i) => '<button type="button" class="swatch-btn" data-color="' + i + '" aria-pressed="' + (i === 0) + '" aria-label="Color option ' + (i + 1) + '" style="background:' + c + '"></button>').join('') +
        '</div></div>'
      : '') +
    (hasVariants
      ? '<div class="option-group"><div class="label-row"><span>' + product.variantLabel + '</span></div>' +
        '<div class="select-field" style="width:100%"><select id="variant-select" style="width:100%">' +
        product.variants.map((v) => '<option value="' + v + '">' + v + '</option>').join('') +
        '</select><i data-icon="chevron-down" data-icon-class="icon-sm"></i></div></div>'
      : '') +
    '<div class="product-actions">' +
    '<div class="stepper" id="qty-stepper">' +
    '<button type="button" data-step="-1" aria-label="Decrease quantity"><i data-icon="minus" data-icon-class="icon-sm"></i></button>' +
    '<input type="text" inputmode="numeric" id="qty-input" value="1" aria-label="Quantity">' +
    '<button type="button" data-step="1" aria-label="Increase quantity"><i data-icon="plus" data-icon-class="icon-sm"></i></button>' +
    '</div>' +
    '<button type="button" class="btn btn-primary" id="add-to-cart-btn" style="flex:1">Add to Bag — <span id="add-to-cart-price">' + formatPrice(product.price) + '</span></button>' +
    '<button type="button" class="icon-btn" data-wishlist="' + product.id + '" aria-pressed="' + wishOn + '" aria-label="' + (wishOn ? 'Remove from wishlist' : 'Add to wishlist') + '" style="border:1px solid var(--color-border-strong)">' +
    icon('heart-line', 'icon-heart-line') + icon('heart-fill', 'icon-heart-fill') +
    '</button>' +
    '</div>' +
    '<ul class="trust-strip">' +
    '<li>' + icon('truck', 'icon-sm') + ' Free shipping over $50</li>' +
    '<li>' + icon('shield', 'icon-sm') + ' 2-year guarantee</li>' +
    '<li>' + icon('rotate', 'icon-sm') + ' 30-day returns</li>' +
    '</ul>' +
    '<div data-accordion-single>' +
    accordionItem('Description', '<p>' + product.description + ' Every Alo Story piece is inspected by hand before it ships — small imperfections are part of the material, not a flaw.</p>', true) +
    accordionItem('Materials &amp; Care', '<ul><li>' + product.material + '</li><li>Wipe clean with a soft, dry cloth.</li><li>Keep away from prolonged direct sunlight and moisture.</li></ul>') +
    accordionItem('Shipping &amp; Returns', '<p>Free standard shipping on orders over $50 (otherwise a flat $5). Orders ship within 2 business days. Not the right fit? Returns are accepted within 30 days, unworn and in original packaging. See our <a href="shipping-returns.html" class="link-underline">full policy</a>.</p>') +
    '</div>' +
    '</div>';

  hydrateIcons(document.getElementById('product-detail'));
  initWishlistButtons(document.getElementById('product-detail'));
  initAccordions();
  wireProductInteractions(product);
}

function accordionItem(title, bodyHtml, openByDefault) {
  return (
    '<div class="accordion-item' + (openByDefault ? ' is-open' : '') + '">' +
    '<button type="button" class="accordion-trigger" aria-expanded="' + Boolean(openByDefault) + '">' + title + '<i data-icon="plus" data-icon-class="icon-sm"></i></button>' +
    '<div class="accordion-panel"><div>' + bodyHtml + '</div></div>' +
    '</div>'
  );
}

function wireProductInteractions(product) {
  const qtyInput = document.getElementById('qty-input');
  document.getElementById('qty-stepper').addEventListener('click', (e) => {
    const btn = e.target.closest('[data-step]');
    if (!btn) return;
    const next = Math.max(1, Number(qtyInput.value || 1) + Number(btn.dataset.step));
    qtyInput.value = next;
  });

  const thumbs = document.querySelectorAll('[data-thumb]');
  const swatches = document.querySelectorAll('[data-color]');
  function selectColor(i) {
    thumbs.forEach((t) => t.setAttribute('aria-pressed', String(Number(t.dataset.thumb) === i)));
    swatches.forEach((s) => s.setAttribute('aria-pressed', String(Number(s.dataset.color) === i)));
  }
  thumbs.forEach((t) => t.addEventListener('click', () => selectColor(Number(t.dataset.thumb))));
  swatches.forEach((s) => s.addEventListener('click', () => selectColor(Number(s.dataset.color))));

  document.getElementById('add-to-cart-btn').addEventListener('click', () => {
    const qty = Math.max(1, Number(qtyInput.value || 1));
    const variantSelect = document.getElementById('variant-select');
    const variant = variantSelect ? variantSelect.value : null;
    addToCart(product.id, qty, variant);
    showToast('Added to bag', qty + ' × ' + product.name, 'cart');
    openCartDrawer();
  });
}

function renderBreadcrumb(product) {
  const nav = document.getElementById('product-breadcrumb');
  nav.innerHTML =
    '<a href="index.html">Home</a><i data-icon="chevron-right" data-icon-class="icon-sm"></i>' +
    '<a href="shop.html">Shop</a><i data-icon="chevron-right" data-icon-class="icon-sm"></i>' +
    '<a href="shop.html?category=' + product.category + '">' + CATEGORY_LABELS[product.category] + '</a>' +
    '<i data-icon="chevron-right" data-icon-class="icon-sm"></i><span aria-current="page">' + product.name + '</span>';
  hydrateIcons(nav);
}

document.addEventListener('DOMContentLoaded', () => {
  const product = getProductById(getUrlProductId());

  if (!product) {
    document.getElementById('product-detail-wrap').hidden = true;
    document.getElementById('product-breadcrumb').hidden = true;
    document.getElementById('not-found').hidden = false;
    hydrateIcons(document.getElementById('not-found'));
    document.title = 'Product Not Found — Alo Story';
    return;
  }

  document.title = product.name + ' — Alo Story';
  renderBreadcrumb(product);
  renderProductDetail(product);
  renderProductGrid(document.getElementById('related-grid'), getRelatedProducts(product, 4));
});
