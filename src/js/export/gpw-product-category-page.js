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
        this.buttonClasses = ['gpw-button', 'gpw-button__primary', 'gpw-button--full-width'];
        this.cacheElements();
        this.initObserver();
      } catch (error) {
        console.warn('ERROR IN PRODUCT CATEGORIES CAROUSEL CONTROLLER: ', error);
      }
    },
    cacheElements() {
      this.containerEls = [...document.querySelectorAll('.gpw-prd-cat')];
      this.viewAllBtns = [...this.containerEls.map(containerEl => containerEl.querySelector('.gpw-prd-cat__view-all'))];
      if( !this.containerEls.length ) {
        throw new Error('No carousel elements found');
      }
    },
    initSwiper() {
      this.swipers = this.containerEls.map( containerEl => {
        return new Swiper( containerEl.querySelector('.swiper'), {
          slidesPerView: 4,
          spaceBetween: 20,
          navigation: {
            nextEl: containerEl.querySelector('.gpw-nav-btn__next'),
            prevEl: containerEl.querySelector('.gpw-nav-btn__prev'),
          },
        } );
      } );
    },
    changingButtonStyle( screen = 'desktop' ) {
      this.viewAllBtns.forEach( btn => {
        if( screen === 'desktop' ) {
          btn.classList.remove( ...this.buttonClasses );
        } else {
          btn.classList.add( ...this.buttonClasses );
        }
      } );
    },
    initObserver() {
      const observer = new ResizeObserver( entries => {
        const entry = entries[0];
        if ( entry.contentRect.width > 1200 ) {
          this.initSwiper();
          this.changingButtonStyle('desktop');
        } else {
          if( this.swipers ) {
            this.swipers.forEach( swiper => swiper.destroy() );
            this.swipers = [];
          }
          this.changingButtonStyle('mobile');
        }
      } );
      observer.observe( document.body );
    }
  }.init();
});
