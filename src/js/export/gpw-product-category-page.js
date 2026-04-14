document.addEventListener('DOMContentLoaded', function () {
  // Product header controller
  const productHeaderController = {
    init() {
      try {
        this.cacheElements();
        this.bindEvents();
      } catch (error) {
        console.warn('ERROR IN PRODUCT HEADER CONTROLLER: ', error);
      }
    },
    cacheElements() {
      this.headerEl = document.querySelector('.product-cat-header');
      if (!this.headerEl) {
        throw new Error('Product header element not found');
      }
      this.descToggleBtn = this.headerEl.querySelector('.product-cat-header__description-toggle');
      this.descPopoverEl = this.headerEl.querySelector('.product-cat-popover');
      this.closePopoverBtn = this.descPopoverEl.querySelector('.product-cat-popover__close-btn');
    },
    bindEvents() {
      this.descToggleBtn.addEventListener('click', this.handleToggle.bind(this, 'open' ));
      this.closePopoverBtn.addEventListener('click', this.handleToggle.bind(this, 'close' ));
      this.descPopoverEl.addEventListener('toggle', event => {
        document.documentElement.classList.toggle('no-scroll', event.newState === 'open');
      });
    },
    handleToggle( state = 'open' ) {
      if( !this.descPopoverEl ) {
        throw new Error('Description popover element not found');
      }
      this.descPopoverEl.togglePopover( state === 'open' );
    }
  }.init();

  // Product categories carousel controller
  const productCategoriesCarouselController = {
    init() {
      try {
        this.cacheElements();
        this.initSwiper();
      } catch (error) {
        console.warn('ERROR IN PRODUCT CATEGORIES CAROUSEL CONTROLLER: ', error);
      }
    },
    cacheElements() {
      this.swiperEls = document.querySelectorAll('.gpw-prd-cat__carousel .swiper');
      if( !this.swiperEls.length ) {
        throw new Error('No carousel elements found');
      }
    },
    initSwiper() {
      this.swiperEls.forEach( swiperEl => {
        new Swiper( swiperEl, {
          slidesPerView: 1,
          spaceBetween: 20,
          navigation: {
            nextEl: swiperEl.querySelector('.gpw-nav-btn__next'),
            prevEl: swiperEl.querySelector('.gpw-nav-btn__prev'),
          },
          breakpoints: {
            550: {
              slidesPerView: 2,
            },
            850: {
              slidesPerView: 4,
            }
          }
        } );
      } );
    }
  }.init();
});
