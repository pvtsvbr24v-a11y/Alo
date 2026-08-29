/* Alo Story — product catalog.
   Static demo data (no backend). Each product uses an illustrated
   line-icon "art" card instead of photography, tinted per colorway. */

const CATEGORY_LABELS = {
  'phone-cases': 'Phone Cases',
  'metal-stickers': 'Metal Stickers',
  bookmarks: 'Bookmarks',
};

const PRODUCTS = [
  // ---- Phone Cases ----
  {
    id: 'pc-01', category: 'phone-cases', name: 'The Gatsby Case',
    price: 36, compareAt: null, tag: 'Bestseller', tint: 'terracotta', rating: 4.9, reviews: 214,
    colors: ['#2b2620', '#b08d57'], variantLabel: 'Compatibility',
    variants: ['iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro', 'iPhone 14'],
    material: 'Vegan leather wrap over a shock-absorbing bumper, foil-stamped detailing',
    description: 'A little decadence for your pocket. Deep espresso leatherette with a hairline gold foil edge, built for a life spent between chapters and champagne towers.',
  },
  {
    id: 'pc-02', category: 'phone-cases', name: 'The Austen Case',
    price: 34, compareAt: null, tag: null, tint: 'blush', rating: 4.8, reviews: 156,
    colors: ['#c98f96', '#ede6da'], variantLabel: 'Compatibility',
    variants: ['iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro', 'iPhone 14'],
    material: 'Dusty rose vegan leather, satin-brass corner guards',
    description: 'Quietly witty, endlessly re-read. A softly blushed case with satin-brass corners for the sort of romance that ages well.',
  },
  {
    id: 'pc-03', category: 'phone-cases', name: 'The Brontë Case',
    price: 36, compareAt: 42, tag: 'New', tint: 'slate', rating: 4.7, reviews: 63,
    colors: ['#33415c', '#1c1917'], variantLabel: 'Compatibility',
    variants: ['iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro'],
    material: 'Moody navy leatherette, matte anti-scratch finish',
    description: 'Windswept moors, midnight ink. A stormy navy case with a matte finish built to weather every dramatic entrance.',
  },
  {
    id: 'pc-04', category: 'phone-cases', name: 'The Hemingway Case',
    price: 32, compareAt: null, tag: null, tint: 'ivory', rating: 4.6, reviews: 98,
    colors: ['#ede6da', '#7c8a6f'], variantLabel: 'Compatibility',
    variants: ['iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro', 'iPhone 14'],
    material: 'Sun-worn tan leatherette, brushed-brass camera ring',
    description: 'Spare, sturdy, well-travelled. A sun-warmed tan case for short sentences and long journeys.',
  },
  {
    id: 'pc-05', category: 'phone-cases', name: 'The Woolf Case',
    price: 34, compareAt: null, tag: 'Bestseller', tint: 'sage', rating: 4.9, reviews: 187,
    colors: ['#7c8a6f', '#2b2620'], variantLabel: 'Compatibility',
    variants: ['iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro', 'iPhone 14'],
    material: 'Sage vegan leather, raised stream-of-consciousness texture',
    description: 'A room of one’s own, even in your pocket. Soft sage leatherette with a gently textured finish that feels as good as it reads.',
  },
  {
    id: 'pc-06', category: 'phone-cases', name: 'The Dickinson Case',
    price: 32, compareAt: null, tag: 'New', tint: 'rose', rating: 4.8, reviews: 41,
    colors: ['#c98f96', '#b08d57'], variantLabel: 'Compatibility',
    variants: ['iPhone 15 Pro', 'iPhone 15', 'iPhone 14 Pro'],
    material: 'Rosewood leatherette, hairline gold trim',
    description: 'Hope is the thing with pockets. A quiet rosewood case for carrying small, private worlds.',
  },

  // ---- Metal Stickers (enamel pins) ----
  {
    id: 'ms-01', category: 'metal-stickers', name: 'Quill & Ink Pin',
    price: 12, compareAt: null, tag: 'Bestseller', tint: 'ivory', rating: 4.9, reviews: 302,
    colors: ['#b08d57', '#1c1917'], variantLabel: null, variants: [],
    material: 'Hard enamel, brass plating, secure rubber clasp',
    description: 'A hand-finished brass quill dipped in enamel ink, sized to sit neatly on a tote, jacket lapel, or reading journal.',
  },
  {
    id: 'ms-02', category: 'metal-stickers', name: 'Open Book Pin',
    price: 10, compareAt: null, tag: null, tint: 'blush', rating: 4.7, reviews: 121,
    colors: ['#c98f96', '#b08d57'], variantLabel: null, variants: [],
    material: 'Hard enamel, gold plating, secure rubber clasp',
    description: 'The simplest shape in the shop and the most honest — an open book, cast in gold-plated enamel.',
  },
  {
    id: 'ms-03', category: 'metal-stickers', name: 'Reading Nook Pin',
    price: 12, compareAt: null, tag: 'New', tint: 'sage', rating: 4.8, reviews: 54,
    colors: ['#7c8a6f', '#ede6da'], variantLabel: null, variants: [],
    material: 'Soft enamel, brushed brass plating',
    description: 'A tiny armchair, a tiny lamp, a tiny stack of books — for the corner of the world you’d rather be in.',
  },
  {
    id: 'ms-04', category: 'metal-stickers', name: 'Tea & Pages Pin',
    price: 11, compareAt: null, tag: null, tint: 'terracotta', rating: 4.6, reviews: 88,
    colors: ['#c17f59', '#1c1917'], variantLabel: null, variants: [],
    material: 'Hard enamel, antique brass plating',
    description: 'For the ritual before the reading. A warm terracotta teacup, permanently mid-steep.',
  },
  {
    id: 'ms-05', category: 'metal-stickers', name: 'Moonlit Library Pin',
    price: 13, compareAt: 15, tag: 'Bestseller', tint: 'slate', rating: 4.9, reviews: 176,
    colors: ['#33415c', '#b08d57'], variantLabel: null, variants: [],
    material: 'Hard enamel, gold plating, glow-in-the-dark accent',
    description: 'Shelves under starlight, cast in navy enamel with a gold-plated spine that catches the light.',
  },
  {
    id: 'ms-06', category: 'metal-stickers', name: 'Wanderer’s Compass Pin',
    price: 11, compareAt: null, tag: 'New', tint: 'rose', rating: 4.7, reviews: 39,
    colors: ['#c98f96', '#3a3530'], variantLabel: null, variants: [],
    material: 'Soft enamel, brushed brass plating',
    description: 'For characters (and readers) who are always mid-journey. A compass with nowhere in particular to be.',
  },

  // ---- Bookmarks ----
  {
    id: 'bm-01', category: 'bookmarks', name: 'Brass Feather Bookmark',
    price: 18, compareAt: null, tag: 'Bestseller', tint: 'ivory', rating: 4.9, reviews: 268,
    colors: ['#b08d57', '#3a3530'], variantLabel: null, variants: [],
    material: 'Solid brass, hand-finished edge, 5 × 15cm',
    description: 'Weighty enough to stay put, light enough to forget it’s there. A single brass feather, hand-finished to a soft antique glow.',
  },
  {
    id: 'bm-02', category: 'bookmarks', name: 'Engraved Initial Bookmark',
    price: 20, compareAt: null, tag: null, tint: 'blush', rating: 4.8, reviews: 143,
    colors: ['#b08d57', '#c98f96'], variantLabel: null, variants: [],
    material: 'Brass with hand-stamped lettering, 4 × 13cm',
    description: 'A single hand-stamped letter on brushed brass — quietly monogrammed, the way a good bookplate should be.',
  },
  {
    id: 'bm-03', category: 'bookmarks', name: 'Pressed Botanical Bookmark',
    price: 16, compareAt: null, tag: 'New', tint: 'sage', rating: 4.8, reviews: 71,
    colors: ['#7c8a6f', '#ede6da'], variantLabel: null, variants: [],
    material: 'Real pressed botanicals sealed in matte laminate, 4.5 × 15cm',
    description: 'A pressed sprig from the garden, sealed for keeps between the pages you love most.',
  },
  {
    id: 'bm-04', category: 'bookmarks', name: 'Corner Page Charm',
    price: 14, compareAt: null, tag: null, tint: 'terracotta', rating: 4.6, reviews: 96,
    colors: ['#c17f59', '#1c1917'], variantLabel: null, variants: [],
    material: 'Enamelled brass corner clip, fits most page weights',
    description: 'Slips over the corner of the page like a tiny bookplate, so you never lose your place mid-sentence.',
  },
  {
    id: 'bm-05', category: 'bookmarks', name: 'Midnight Star Bookmark',
    price: 18, compareAt: null, tag: 'Bestseller', tint: 'slate', rating: 4.9, reviews: 201,
    colors: ['#33415c', '#b08d57'], variantLabel: null, variants: [],
    material: 'Oxidised brass with gold-plated star charm, 5 × 16cm',
    description: 'For late reading sessions and early morning ones. A dark brass ribbon with a single gilded star charm.',
  },
  {
    id: 'bm-06', category: 'bookmarks', name: 'Rose Gold Ribbon Bookmark',
    price: 20, compareAt: 24, tag: 'New', tint: 'rose', rating: 4.7, reviews: 48,
    colors: ['#c98f96', '#b08d57'], variantLabel: null, variants: [],
    material: 'Rose-gold plated brass, satin ribbon tassel, 4 × 14cm',
    description: 'A slim rose-gold ribbon with a satin tassel — a soft, celebratory way to mark a favourite chapter.',
  },
];

function getProductById(id) {
  return PRODUCTS.find((p) => p.id === id) || null;
}

function getRelatedProducts(product, limit) {
  return PRODUCTS.filter((p) => p.category === product.category && p.id !== product.id).slice(0, limit || 4);
}

function formatPrice(value) {
  return '$' + value.toFixed(2).replace(/\.00$/, '');
}
