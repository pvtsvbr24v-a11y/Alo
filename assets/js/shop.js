/* Alo Story — shop page: category filter, search, sort, grid render. */

const CATEGORY_COPY = {
  all: {
    title: 'Shop All',
    lede: 'Phone cases, enamel pins, and brass bookmarks — every piece designed to be carried, not just owned.',
  },
  'phone-cases': {
    title: 'Phone Cases',
    lede: 'Vegan-leather cases named after the authors who inspired them, wrapped by hand in small batches.',
  },
  'metal-stickers': {
    title: 'Metal Stickers',
    lede: 'Hard-enamel pins for totes, jackets, and reading journals — cast, plated, and hand-finished.',
  },
  bookmarks: {
    title: 'Bookmarks',
    lede: 'Solid brass and hand-finished bookmarks, made to outlast the books they mark.',
  },
};

function currentShopState() {
  const params = new URLSearchParams(window.location.search);
  return {
    category: params.get('category') || 'all',
    q: (params.get('q') || '').trim().toLowerCase(),
    sort: params.get('sort') || 'featured',
  };
}

function applyShopFilters(state) {
  let items = PRODUCTS.slice();
  if (state.category && state.category !== 'all') {
    items = items.filter((p) => p.category === state.category);
  }
  if (state.q) {
    items = items.filter((p) => p.name.toLowerCase().includes(state.q) || p.description.toLowerCase().includes(state.q));
  }
  switch (state.sort) {
    case 'price-asc':
      items.sort((a, b) => a.price - b.price);
      break;
    case 'price-desc':
      items.sort((a, b) => b.price - a.price);
      break;
    case 'rating':
      items.sort((a, b) => b.rating - a.rating);
      break;
    default:
      items.sort((a, b) => (b.tag === 'Bestseller') - (a.tag === 'Bestseller'));
  }
  return items;
}

function renderShop() {
  const state = currentShopState();
  const items = applyShopFilters(state);

  document.querySelectorAll('#category-chips .chip').forEach((chip) => {
    chip.setAttribute('aria-pressed', String(chip.dataset.filter === state.category));
  });
  document.getElementById('sort-select').value = state.sort;

  const copy = CATEGORY_COPY[state.category] || CATEGORY_COPY.all;
  document.getElementById('shop-title').textContent = state.q ? 'Search results' : copy.title;
  document.getElementById('shop-lede').textContent = state.q
    ? 'Showing results for "' + state.q + '".'
    : copy.lede;
  document.getElementById('results-count').textContent = items.length + (items.length === 1 ? ' piece' : ' pieces');

  renderProductGrid(document.getElementById('shop-grid'), items);
}

function updateShopUrl(patch) {
  const params = new URLSearchParams(window.location.search);
  Object.entries(patch).forEach(([key, value]) => {
    if (!value || value === 'all' || value === 'featured') params.delete(key);
    else params.set(key, value);
  });
  const query = params.toString();
  const url = window.location.pathname + (query ? '?' + query : '');
  window.history.pushState({}, '', url);
  renderShop();
}

document.addEventListener('DOMContentLoaded', () => {
  const state = currentShopState();
  const searchInput = document.getElementById('header-search-input');
  if (searchInput && state.q) searchInput.value = state.q;

  renderShop();

  document.getElementById('category-chips').addEventListener('click', (e) => {
    const chip = e.target.closest('.chip');
    if (!chip) return;
    updateShopUrl({ category: chip.dataset.filter });
  });

  document.getElementById('sort-select').addEventListener('change', (e) => {
    updateShopUrl({ sort: e.target.value });
  });

  window.addEventListener('popstate', renderShop);
});
