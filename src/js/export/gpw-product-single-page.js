document.addEventListener('DOMContentLoaded', function() {
  // gallery
  const gallerySection = {
    init() {
      try {
        this.cacheElements();
        this.initFancybox();
        this.observeScreenChange();
      } catch (error) {
        console.warn('PRODUCT GALLERY ERROR: ', error);
        return;
      }
    },
    cacheElements() {
      this.swiperEl = document.querySelector('.gpw-gallery .swiper');
      if (!this.swiperEl) {
        throw new Error('No swiper element found for product gallery');
      }
    },
    initSwiper() {
      this.swiper = new Swiper(this.swiperEl, {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
          nextEl: '.gpw-gallery .gpw-nav-btn__next',
          prevEl: '.gpw-gallery .gpw-nav-btn__prev',
        },
        pagination: {
          el: '.gpw-gallery .gpw-pagination',
          clickable: true,
        },
      });
    },
    initFancybox() {
      if (typeof Fancybox === 'undefined') {
        throw new Error('Fancybox is not loaded');
      }
      Fancybox.bind('[data-fancybox="hotel-gallery"]', {
        Thumbs: {
          type: 'classic',
        },
      });
    },
    observeScreenChange() {
      let swiperInitialized = false;
      const observer = new ResizeObserver(entries => {
        const entry = entries[0];
        if (entry.contentRect.width < 850) {
          if (!swiperInitialized) {
            this.initSwiper();
            swiperInitialized = true;
          }
        } else {
          if (this.swiper) {
            this.swiper.detachEvents();
            this.swiper.destroy(true, true);
            this.swiper = null;
            swiperInitialized = false;
            const slides = this.swiperEl.querySelectorAll('.swiper-slide');
            slides.forEach(slide => {
              slide.removeAttribute('style');
              slide.classList.remove('swiper-slide-active', 'swiper-slide-next', 'swiper-slide-prev');
            });
          }
        }
      });
      observer.observe(document.querySelector('.gpw-gallery'));
    },
  }.init();

  // highlights controller
  const highlightsSection = {
    init() {
      try {
        this.cacheElements();
        this.bindEvents();
      } catch( error ) {
        console.warn('HIGHLIGHTS TOGGLE ERROR: ', error);
      }
    },
    cacheElements() {
      this.highlightsSectionEl = document.querySelector('.product-details__highlights');
      if ( !this.highlightsSectionEl ) {
        throw new Error('No highlights section found');
      }
      this.toggleBtn = this.highlightsSectionEl.querySelector('.product-details__highlights-toggle');
    },
    bindEvents() {
      this.toggleBtn.addEventListener('click', this.handleToggle.bind(this));
    },
    handleToggle() {
      const isExpanded = this.toggleBtn.getAttribute('aria-expanded') === 'true';
      this.toggleBtn.setAttribute('aria-expanded', String(!isExpanded));
    }
  }.init();
});