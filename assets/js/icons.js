/* Alo Story — inline SVG icon set.
   One consistent line-art family (1.6 stroke, round caps) so icons never
   depend on emoji or external icon fonts. Filled variants set their own
   fill/stroke. Icons are decorative by default (aria-hidden); give the
   *button* around them the accessible name. */

const ICONS = {
  menu: '<line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>',
  close: '<line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/>',
  search: '<circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.6" y2="16.6"/>',
  cart: '<path d="M6 8h12l-1.1 11.2a2 2 0 0 1-2 1.8H9.1a2 2 0 0 1-2-1.8L6 8Z"/><path d="M9 8V6.3a3 3 0 0 1 6 0V8"/>',
  'heart-line': '<path d="M12 20.5s-7-4.4-9.4-8.7C1 8.7 1.9 5 5.2 4.2c2.1-.5 4 .4 5.3 2C11.8 4.6 13.7 3.7 15.8 4.2c3.3.8 4.2 4.5 2.6 7.6-2.4 4.3-9.4 8.7-9.4 8.7Z"/>',
  'heart-fill': '<path d="M12 20.5s-7-4.4-9.4-8.7C1 8.7 1.9 5 5.2 4.2c2.1-.5 4 .4 5.3 2C11.8 4.6 13.7 3.7 15.8 4.2c3.3.8 4.2 4.5 2.6 7.6-2.4 4.3-9.4 8.7-9.4 8.7Z" fill="currentColor" stroke="none"/>',
  'star-fill': '<path d="M12 3l2.6 5.4 6 .7-4.4 4.1 1.2 5.9L12 16.2l-5.4 2.9 1.2-5.9-4.4-4.1 6-.7L12 3Z" fill="currentColor" stroke="none"/>',
  'chevron-down': '<polyline points="6 9 12 15 18 9"/>',
  'chevron-right': '<polyline points="9 6 15 12 9 18"/>',
  'chevron-left': '<polyline points="15 6 9 12 15 18"/>',
  'arrow-right': '<line x1="4" y1="12" x2="19" y2="12"/><polyline points="12.5 5.5 19 12 12.5 18.5"/>',
  plus: '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
  minus: '<line x1="5" y1="12" x2="19" y2="12"/>',
  check: '<polyline points="5 13 10 18 19 7"/>',
  trash: '<path d="M4 7h16"/><path d="M10 11v6M14 11v6"/><path d="M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12"/><path d="M9 7V4.6A1 1 0 0 1 10 3.6h4a1 1 0 0 1 1 1V7"/>',
  mail: '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 6.5 12 13l8.5-6.5"/>',
  'map-pin': '<path d="M12 21s7-6.7 7-11.8A7 7 0 1 0 5 9.2C5 14.3 12 21 12 21Z"/><circle cx="12" cy="9.2" r="2.4"/>',
  truck: '<rect x="1.5" y="7" width="13" height="9" rx="1"/><path d="M14.5 10.2h3.2a1 1 0 0 1 .84.46l1.96 3v2.34h-6"/><circle cx="6" cy="18" r="1.7"/><circle cx="16.6" cy="18" r="1.7"/>',
  leaf: '<path d="M20 4C10 4 4 10 4 18c8 0 14-6 16-14Z"/><path d="M9.5 14.5C13 11 15.5 8.5 18.5 5.5"/>',
  sparkle: '<path d="M12 3.2l1.5 5 5 1.5-5 1.5-1.5 5-1.5-5-5-1.5 5-1.5 1.5-5Z"/>',
  shield: '<path d="M12 3.2 19 6v6c0 4.6-3 7.6-7 9-4-1.4-7-4.4-7-9V6l7-2.8Z"/><polyline points="9 12 11 14 15.2 9.7"/>',
  'book-open': '<path d="M12 6.6c-1.8-1.5-4.3-2.1-6.6-1.8v12.4c2.3-.3 4.8.3 6.6 1.8 1.8-1.5 4.3-2.1 6.6-1.8V4.8c-2.3-.3-4.8.3-6.6 1.8Z"/><line x1="12" y1="6.6" x2="12" y2="19"/>',
  'phone-case': '<rect x="6.2" y="2" width="11.6" height="20" rx="3"/><circle cx="15.2" cy="5.1" r="0.55" fill="currentColor" stroke="none"/><path d="M9.3 18.3c1 .55 1.7.55 2.7.55s1.7 0 2.7-.55"/>',
  'metal-sticker': '<circle cx="12" cy="12" r="8.2"/><path d="M12 7.3l1.4 3 3.3.45-2.4 2.3.6 3.3-2.9-1.55-2.9 1.55.6-3.3-2.4-2.3 3.3-.45L12 7.3Z"/>',
  bookmark: '<path d="M7.2 3.2h9.6a1 1 0 0 1 1 1V21l-5.8-3.9L6.2 21V4.2a1 1 0 0 1 1-1Z"/>',
  quote: '<path d="M9.4 7C6.6 7.4 4.6 9.8 4.6 12.8c0 1.9 1 3.4 2.6 3.9-.9 1.6-2.2 2.4-2.2 2.4l.5 1.4c2.8-.9 5.6-3 5.6-6.9C11.1 10.2 9.7 7.5 9.4 7Z"/><path d="M17.4 7c-2.8.4-4.8 2.8-4.8 5.8 0 1.9 1 3.4 2.6 3.9-.9 1.6-2.2 2.4-2.2 2.4l.5 1.4c2.8-.9 5.6-3 5.6-6.9C19.1 10.2 17.7 7.5 17.4 7Z"/>',
  help: '<circle cx="12" cy="12" r="9"/><path d="M9.2 9.3a2.8 2.8 0 0 1 5.4.9c0 1.8-2.6 2-2.6 3.6"/><line x1="12" y1="16.8" x2="12" y2="16.81"/>',
  alert: '<circle cx="12" cy="12" r="9"/><line x1="12" y1="7.8" x2="12" y2="13"/><line x1="12" y1="16.2" x2="12" y2="16.21"/>',
  package: '<path d="M3.4 8.4 12 4l8.6 4.4v7.7L12 20.5l-8.6-4.4Z"/><path d="M3.7 8.3 12 12.6l8.3-4.3"/><line x1="12" y1="12.6" x2="12" y2="20.5"/>',
  lock: '<rect x="5" y="10.4" width="14" height="9.4" rx="1.6"/><path d="M8 10.4V7.6a4 4 0 0 1 8 0v2.8"/>',
  rotate: '<path d="M4.3 9a8 8 0 1 1 1.6 8.6"/><polyline points="4 4 4 9 9 9"/>',
  clock: '<circle cx="12" cy="12" r="8.5"/><polyline points="12 7.6 12 12 15.4 14"/>',
  gift: '<rect x="4" y="9" width="16" height="11" rx="1"/><path d="M4 9h16v3.2H4z"/><line x1="12" y1="9" x2="12" y2="20"/><path d="M12 9c-1.8-3.8-5.6-3.8-5.6-1.3C6.4 9 9.2 9 12 9Z"/><path d="M12 9c1.8-3.8 5.6-3.8 5.6-1.3C17.6 9 14.8 9 12 9Z"/>',
};

function icon(name, className) {
  const body = ICONS[name] || '';
  return `<svg class="icon${className ? ' ' + className : ''}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">${body}</svg>`;
}

function categoryIconName(category) {
  if (category === 'phone-cases') return 'phone-case';
  if (category === 'metal-stickers') return 'metal-sticker';
  return 'bookmark';
}

function starRow(rating) {
  const full = Math.round(rating);
  let out = '';
  for (let i = 0; i < 5; i++) {
    out += icon('star-fill', i < full ? '' : 'muted-star');
  }
  return `<span class="stars" role="img" aria-label="${rating} out of 5 stars">${out}</span>`;
}
