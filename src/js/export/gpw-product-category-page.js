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
});
