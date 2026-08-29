/* Alo Story — home page: render bestseller grid. */
document.addEventListener('DOMContentLoaded', () => {
  const grid = document.getElementById('bestsellers-grid');
  const bestsellers = PRODUCTS.filter((p) => p.tag === 'Bestseller');
  renderProductGrid(grid, bestsellers);
});
