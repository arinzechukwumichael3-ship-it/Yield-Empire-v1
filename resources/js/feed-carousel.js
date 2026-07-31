/**
 * EnzoBank Feed Carousel — Instagram/Facebook Stories-style
 * Vanilla JS, no dependencies. Usage:
 *
 *   new FeedCarousel('#feedCarousel', {
 *     slides: [...],
 *     interval: 8000,
 *   });
 *
 * Each slide: { id, category, title, description, imageUrl }
 */
class FeedCarousel {
  constructor(container, opts = {}) {
    this.el = typeof container === 'string' ? document.querySelector(container) : container;
    if (!this.el) return;

    this.opts = Object.assign({ interval: 8000 }, opts);
    this.slides = this.opts.slides || [];
    this.filtered = this.slides.slice();
    this.current = 0;
    this.timer = null;
    this.progressTimer = null;
    this.progressStart = null;
    this.isPaused = false;
    this.isTransitioning = false;

    this.render();
    this.bindEvents();
    if (this.filtered.length) this.startTimer();
  }

  /* ───────── render ───────── */
  render() {
    this.el.innerHTML = `
      <div class="fcar-wrap">
        <div class="fcar-tabs"></div>
        <div class="fcar-stage">
          <div class="fcar-progress"></div>
          <div class="fcar-viewport">
            <div class="fcar-track"></div>
          </div>
          <button class="fcar-nav fcar-prev" aria-label="Previous">‹</button>
          <button class="fcar-nav fcar-next" aria-label="Next">›</button>
        </div>
      </div>
    `;

    this.tabsEl = this.el.querySelector('.fcar-tabs');
    this.progressEl = this.el.querySelector('.fcar-progress');
    this.trackEl = this.el.querySelector('.fcar-track');
    this.prevBtn = this.el.querySelector('.fcar-prev');
    this.nextBtn = this.el.querySelector('.fcar-next');
    this.viewport = this.el.querySelector('.fcar-viewport');

    this.renderTabs();
    this.renderProgress();
    this.renderSlide(0);
  }

  renderTabs() {
    const cats = ['all', 'Company updates', 'Portfolio reports', 'Market updates'];
    this.tabsEl.innerHTML = cats.map(c => {
      const label = c === 'all' ? 'All' : c;
      return `<button class="fcar-tab${c === 'all' ? ' active' : ''}" data-cat="${c}">${label}</button>`;
    }).join('');
    this.tabsEl.querySelectorAll('.fcar-tab').forEach(tab => {
      tab.addEventListener('click', () => this.filter(tab.dataset.cat));
    });
  }

  renderProgress() {
    const n = this.filtered.length;
    if (!n) { this.progressEl.innerHTML = ''; return; }
    this.progressEl.innerHTML = Array.from({ length: n }, (_, i) =>
      `<span class="fcar-prog-seg" data-idx="${i}">
        <span class="fcar-prog-fill"></span>
      </span>`
    ).join('');
    this.segs = this.progressEl.querySelectorAll('.fcar-prog-seg');
    this.fills = this.progressEl.querySelectorAll('.fcar-prog-fill');
  }

  renderSlide(idx, dir) {
    if (!this.filtered.length) {
      this.trackEl.innerHTML = `<div class="fcar-empty">No articles yet — check back soon.</div>`;
      return;
    }
    const s = this.filtered[idx];
    if (!s) return;

    const slide = document.createElement('div');
    slide.className = 'fcar-slide';
    slide.style.backgroundImage = `url(${s.imageUrl || ''})`;
    slide.innerHTML = `
      <div class="fcar-overlay"></div>
      <div class="fcar-content">
        <span class="fcar-cat">${this.esc(s.category)}</span>
        <h3 class="fcar-title">${this.esc(s.title)}</h3>
        <p class="fcar-desc">${this.esc(s.description)}</p>
      </div>
    `;
    slide.dataset.id = s.id;

    // Transition
    const existing = this.trackEl.querySelector('.fcar-slide');
    if (!existing) {
      this.trackEl.appendChild(slide);
      requestAnimationFrame(() => slide.classList.add('fcar-slide-in'));
      return;
    }

    this.isTransitioning = true;
    const outDir = dir === -1 ? 'right' : 'left';
    existing.classList.add(`fcar-slide-out-${outDir}`);
    slide.classList.add(`fcar-slide-in-${outDir}`);
    this.trackEl.appendChild(slide);

    existing.addEventListener('transitionend', () => existing.remove(), { once: true });
    setTimeout(() => { this.isTransitioning = false; }, 350);
  }

  /* ───────── filtering ───────── */
  filter(cat) {
    this.tabsEl.querySelectorAll('.fcar-tab').forEach(t => t.classList.toggle('active', t.dataset.cat === cat));
    this.activeFilter = cat;
    this.filtered = cat === 'all' ? this.slides.slice() : this.slides.filter(s => s.category === cat);
    this.current = 0;
    this.clearTimer();
    this.renderProgress();
    this.trackEl.innerHTML = '';
    this.renderSlide(0);
    if (this.filtered.length) this.startTimer();
  }

  /* ───────── navigation ───────── */
  goTo(idx, dir) {
    if (this.isTransitioning || !this.filtered.length) return;
    if (idx < 0) idx = this.filtered.length - 1;
    if (idx >= this.filtered.length) idx = 0;
    if (idx === this.current) return;
    dir = dir || (idx > this.current ? 1 : -1);
    this.current = idx;
    this.clearTimer();
    this.renderSlide(idx, dir);
    this.updateProgress();
    this.startTimer();
  }

  next() { this.goTo(this.current + 1, 1); }
  prev() { this.goTo(this.current - 1, -1); }

  /* ───────── progress ───────── */
  updateProgress() {
    this.segs.forEach((seg, i) => {
      seg.classList.toggle('fcar-prog-done', i < this.current);
      seg.classList.toggle('fcar-prog-active', i === this.current);
      seg.classList.toggle('fcar-prog-pending', i > this.current);
    });
    // Reset active fill width
    const activeFill = this.fills[this.current];
    if (activeFill) activeFill.style.width = '0%';
  }

  startTimer() {
    if (this.filtered.length < 2) return;
    this.clearTimer();
    this.progressStart = performance.now();
    this.updateProgress();

    // Animate the active fill
    const activeFill = this.fills[this.current];
    if (activeFill) activeFill.style.width = '0%';

    this.timer = setTimeout(() => {
      this.next();
    }, this.opts.interval);

    // rAF progress fill
    const tick = (now) => {
      if (this.isPaused || this.isTransitioning || !this.filtered.length) return;
      const elapsed = now - this.progressStart;
      const pct = Math.min((elapsed / this.opts.interval) * 100, 100);
      const f = this.fills[this.current];
      if (f) f.style.width = pct + '%';
      if (pct < 100) this.progressRAF = requestAnimationFrame(tick);
    };
    this.progressRAF = requestAnimationFrame(tick);
  }

  clearTimer() {
    if (this.timer) { clearTimeout(this.timer); this.timer = null; }
    if (this.progressRAF) { cancelAnimationFrame(this.progressRAF); this.progressRAF = null; }
  }

  /* ───────── events ───────── */
  bindEvents() {
    this.prevBtn.addEventListener('click', () => this.prev());
    this.nextBtn.addEventListener('click', () => this.next());

    // Touch / swipe
    let startX = 0, startY = 0, touching = false;
    this.viewport.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
      touching = true;
      this.pause();
    }, { passive: true });

    this.viewport.addEventListener('touchend', (e) => {
      if (!touching) return;
      touching = false;
      const dx = e.changedTouches[0].clientX - startX;
      const dy = e.changedTouches[0].clientY - startY;
      if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
        if (dx < 0) this.next();
        else this.prev();
      }
      this.resume();
    }, { passive: true });

    // Mouse drag
    let mouseDown = false, mx = 0;
    this.viewport.addEventListener('mousedown', (e) => {
      mouseDown = true;
      mx = e.clientX;
      this.pause();
    });
    document.addEventListener('mousemove', (e) => {
      if (!mouseDown) return;
      // just tracking for threshold
    });
    document.addEventListener('mouseup', (e) => {
      if (!mouseDown) return;
      mouseDown = false;
      const dx = e.clientX - mx;
      this.resume();
      if (Math.abs(dx) > 40) {
        if (dx < 0) this.next();
        else this.prev();
      }
    });

    // Keyboard
    document.addEventListener('keydown', (e) => {
      if (!this.el.contains(document.activeElement) && !this.el.contains(e.target)) return;
      if (e.key === 'ArrowLeft') this.prev();
      if (e.key === 'ArrowRight') this.next();
    });
  }

  pause() { this.isPaused = true; }
  resume() { this.isPaused = false; this.progressStart = performance.now(); }

  /* ───────── helpers ───────── */
  esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
  }

  destroy() {
    this.clearTimer();
    this.el.innerHTML = '';
  }
}

// Auto-init on DOM ready if data attribute present
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-feed-carousel]').forEach(el => {
    try {
      const slides = JSON.parse(el.dataset.feedCarousel || '[]');
      new FeedCarousel(el, { slides });
    } catch (e) {
      // silent
    }
  });
});
