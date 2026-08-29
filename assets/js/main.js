/* Alo Story — shared page behavior: header, mobile nav, search,
   scroll reveal, accordions, newsletter/contact form helpers. */

document.documentElement.classList.remove('no-js');

/* ---------------- icon hydration ----------------
   Static markup uses <i data-icon="name" data-icon-class="..."></i>
   placeholders so every SVG path lives once, in icons.js. */
function hydrateIcons(root) {
  (root || document).querySelectorAll('[data-icon]').forEach((el) => {
    const name = el.dataset.icon;
    const cls = el.dataset.iconClass || '';
    el.outerHTML = icon(name, cls);
  });
}

/* ---------------- header scroll state ---------------- */
function initHeaderScroll() {
  const header = document.querySelector('.site-header');
  if (!header) return;
  const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
}

/* ---------------- mobile nav drawer ---------------- */
function initMobileNav() {
  const nav = document.getElementById('mobile-nav');
  const openBtn = document.querySelector('[data-open-menu]');
  if (!nav || !openBtn) return;
  const closeEls = nav.querySelectorAll('[data-menu-close]');
  const open = () => {
    nav.classList.add('is-open');
    document.body.classList.add('no-scroll');
    const first = nav.querySelector('a, button');
    if (first) first.focus();
  };
  const close = () => {
    nav.classList.remove('is-open');
    document.body.classList.remove('no-scroll');
    openBtn.focus();
  };
  openBtn.addEventListener('click', open);
  closeEls.forEach((el) => el.addEventListener('click', close));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('is-open')) close();
  });
}

/* ---------------- header search toggle ---------------- */
function initHeaderSearch() {
  const toggle = document.querySelector('[data-search-toggle]');
  const wrap = document.querySelector('.header-search');
  if (!toggle || !wrap) return;
  toggle.addEventListener('click', () => {
    const opening = !wrap.classList.contains('is-open');
    wrap.classList.toggle('is-open');
    if (opening) {
      const input = wrap.querySelector('input');
      if (input) setTimeout(() => input.focus(), 260);
    }
  });
}

/* ---------------- scroll reveal ---------------- */
let revealObserver = null;
function observeReveal(root) {
  const items = (root || document).querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) {
    items.forEach((el) => el.classList.add('is-visible'));
    return;
  }
  if (!revealObserver) {
    revealObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
  }
  items.forEach((el) => {
    if (!el.classList.contains('is-visible')) revealObserver.observe(el);
  });
}

/* ---------------- generic accordion ---------------- */
function initAccordions() {
  document.querySelectorAll('.accordion-trigger').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.accordion-item');
      const panel = item.querySelector('.accordion-panel');
      const isOpen = item.classList.contains('is-open');
      const group = item.closest('[data-accordion-single]');
      if (group) {
        group.querySelectorAll('.accordion-item.is-open').forEach((openItem) => {
          if (openItem !== item) {
            openItem.classList.remove('is-open');
            openItem.querySelector('.accordion-trigger').setAttribute('aria-expanded', 'false');
          }
        });
      }
      item.classList.toggle('is-open', !isOpen);
      trigger.setAttribute('aria-expanded', String(!isOpen));
    });
  });
}

/* ---------------- footer year ---------------- */
function initFooterYear() {
  document.querySelectorAll('[data-year]').forEach((el) => {
    el.textContent = String(new Date().getFullYear());
  });
}

/* ---------------- generic field validation helpers ---------------- */
function setFieldError(field, message) {
  field.classList.toggle('has-error', Boolean(message));
  const msg = field.querySelector('.error-msg span');
  if (msg && message) msg.textContent = message;
}
function validateField(field) {
  const input = field.querySelector('input, textarea, select');
  if (!input) return true;
  if (input.hasAttribute('required') && !input.value.trim()) {
    setFieldError(field, 'This field is required.');
    return false;
  }
  if (input.type === 'email' && input.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
    setFieldError(field, 'Enter a valid email address.');
    return false;
  }
  setFieldError(field, '');
  return true;
}

/* ---------------- newsletter form ---------------- */
function initNewsletterForms() {
  document.querySelectorAll('[data-newsletter-form]').forEach((form) => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const field = form.querySelector('.field');
      if (!validateField(field)) return;
      form.querySelector('input').disabled = true;
      form.querySelector('button').setAttribute('disabled', 'true');
      const success = form.parentElement.querySelector('.form-success');
      if (success) success.classList.add('is-visible');
      form.hidden = true;
    });
  });
}

/* ---------------- contact form (front-end demo, no backend) ---------------- */
function initContactForm() {
  const form = document.getElementById('contact-form');
  if (!form) return;
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const fields = form.querySelectorAll('.field');
    let valid = true;
    fields.forEach((f) => {
      if (!validateField(f)) valid = false;
    });
    const alert = document.getElementById('contact-alert');
    if (!valid) {
      if (alert) {
        alert.className = 'form-alert info is-visible';
        alert.querySelector('span').textContent = 'Please fix the highlighted fields and try again.';
      }
      const firstError = form.querySelector('.field.has-error input, .field.has-error textarea');
      if (firstError) firstError.focus();
      return;
    }
    if (alert) {
      alert.className = 'form-alert success is-visible';
      alert.querySelector('span').textContent = 'Message sent — thank you! We reply within one business day.';
    }
    form.reset();
  });
}

document.addEventListener('DOMContentLoaded', () => {
  hydrateIcons(document);
  initHeaderScroll();
  initMobileNav();
  initHeaderSearch();
  initAccordions();
  initFooterYear();
  initNewsletterForms();
  initContactForm();
  initCartUI();
  observeReveal(document);
});
