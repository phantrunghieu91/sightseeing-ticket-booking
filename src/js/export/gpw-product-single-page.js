document.addEventListener('DOMContentLoaded', function () {
  const throttle = (func, delay) => {
    let isThrottled = false;
    return function( ...args ) {
      if (isThrottled) return;
      func.apply(this, args);
      isThrottled = true;
      setTimeout(() => {
        isThrottled = false;
      }, delay);
    }
  };
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

  // navigation
  const navigationController = {
    init() {
      try {
        this.cacheElements();
        this.initState();
        this.observeNavigationVisibility();
        this.bindEvents();
      } catch (error) {
        console.warn('NAVIGATION CONTROLLER ERROR: ', error);
      }
    },
    initState() {
      this.state = new Proxy({
        currentActiveNav: null,
      }, {
        set: (target, prop, value) => {
          if (prop === 'currentActiveNav') {
            if (target.currentActiveNav) {            
              target.currentActiveNav.classList.remove('product-details__nav-item--active');
              value.classList.add('product-details__nav-item--active');
            }
          }
          target[prop] = value;
          return true;
        }
      });
    },
    cacheElements() {
      this.productDetailsEl = document.querySelector('.product-details');
      if (!this.productDetailsEl) {
        throw new Error('No product details element found');
      }
      this.navEl = this.productDetailsEl.querySelector('.product-details__nav');
      this.navItems = [...this.productDetailsEl.querySelectorAll('.product-details__nav-item')];
      if (this.navItems.length === 0) {
        throw new Error('No navigation items found');
      }
      this.correspondingSections = [...this.productDetailsEl.querySelector('.product-details__main')?.children].reduce((acc, section) => {
        const id = section.id;
        return id ? { ...acc, [id]: section } : acc;
      }, {});
    },
    observeNavigationVisibility() {
      if (!this.navEl) {
        throw new Error('No navigation element found to toggle visibility');
      }

      this.lastScrollTop = 0;
      this.isScrollingDown = true;
      this.correspondingSectionsWithOffset = {};

      this.onScrollHandler = throttle(this.handleNavigationScroll.bind(this), 250);
      this.calculateSectionOffsetsHandler = this.calculateSectionOffsets.bind(this);

      document.addEventListener('navigationCtrl:calculateSectionOffsets', this.calculateSectionOffsetsHandler);
      this.calculateSectionOffsets();
    },

    handleNavigationScroll() {
      const scrollTop = window.scrollY;

      this.isScrollingDown = scrollTop > this.lastScrollTop;

      this.toggleNavigationVisibility(scrollTop);
      this.updateActiveNavigationWhenScrolling(scrollTop);

      this.lastScrollTop = scrollTop;
    },

    toggleNavigationVisibility(scrollTop) {
      const { top, bottom } = this.getProductDetailsBounds();
      const isWithinProductDetails = scrollTop >= top && scrollTop <= bottom;

      this.navEl.classList.toggle('product-details__nav--hidden', !isWithinProductDetails);
    },

    updateActiveNavigationWhenScrolling(scrollTop) {
      const sections = Object.values(this.correspondingSectionsWithOffset);
      const orderedSections = this.isScrollingDown ? sections : [...sections].reverse();

      for (const { top, bottom, nav } of orderedSections) {
        const shouldActivate = this.isScrollingDown
          ? scrollTop >= top
          : scrollTop <= bottom;

        if (shouldActivate) {
          this.state.currentActiveNav = nav;
        }
      }
    },

    getProductDetailsBounds() {
      const top = this.productDetailsEl.offsetTop - 60;

      return {
        top,
        bottom: top + this.productDetailsEl.offsetHeight,
      };
    },

    calculateSectionOffsets() {
      Object.entries(this.correspondingSections).forEach(([id, section]) => {
        const top = section.offsetTop - 80;
        const bottom = top + section.offsetHeight;
        const nav = this.navItems.find(item => item.getAttribute('aria-controls') === id);

        if (nav) {
          this.correspondingSectionsWithOffset[id] = { top, bottom, nav };
        }
      });
      document.removeEventListener('scroll', this.onScrollHandler);
      document.addEventListener('scroll', this.onScrollHandler, { passive: true });

      this.handleNavigationScroll();
    },
    bindEvents() {
      this.navItems.forEach( navItem => {
        navItem.addEventListener('click', this.handleClickOnNavItem.bind(this, navItem));
      } );
    },
    handleClickOnNavItem(navItem, event) {
      const targetId = navItem.getAttribute('aria-controls');
      if( this.state.currentActiveNav && this.state.currentActiveNav == targetId ) {
        return;
      }
      const targetSection = this.correspondingSections[targetId];
      if (targetSection) {
        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        this.state.currentActiveNav = navItem;
      }
    },
  }.init();

  // highlights controller
  const highlightsSection = {
    init() {
      try {
        this.cacheElements();
        this.bindEvents();
      } catch (error) {
        console.warn('HIGHLIGHTS TOGGLE ERROR: ', error);
      }
    },
    cacheElements() {
      this.highlightsSectionEl = document.querySelector('.product-details__highlights');
      if (!this.highlightsSectionEl) {
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
      setTimeout(() => {  // wait for the height transition to finish before recalculating section offsets
        document.dispatchEvent(new Event('navigationCtrl:calculateSectionOffsets'));
      }, 550);
    },
  }.init();

  // Form controller
  const formController = {
    init() {
      try {
        this.cacheElements();
        this.bindEvents();
      } catch(error) {
        console.warn('FORM CONTROLLER ERROR: ', error);
      }
    },
    cacheElements() {
      this.formEl = document.querySelector('.product-form__form');
      if (!this.formEl) {
        throw new Error('No product form found');
      }
      this.addToCartBtn = this.formEl.querySelector('.product-form__add-to-cart-btn');
      this.buyNowBtn = this.formEl.querySelector('.product-form__buy-now-btn');
      this.quantityInput = this.formEl.querySelector('.product-form__control--quantity');
      this.decreaseBtn = this.formEl.querySelector('.product-form__quantity-btn--decrease');
      this.increaseBtn = this.formEl.querySelector('.product-form__quantity-btn--increase');
    },
    bindEvents() {
      this.decreaseBtn.addEventListener('click', this.handleChangeQuantity.bind(this, 'decrease'));
      this.increaseBtn.addEventListener('click', this.handleChangeQuantity.bind(this, 'increase'));
      this.addToCartBtn.addEventListener('click', (event) => this.handleSubmit(event, 'add_to_cart'));
      this.buyNowBtn.addEventListener('click', (event) => this.handleSubmit(event, 'buy_now'));
    },
    handleChangeQuantity( action = 'increase' ) {
      const currentValue = parseInt(this.quantityInput.value) || 1;
      if( action === 'decrease' && currentValue <= 1 ) {
        return;
      }
      const newValue = action === 'increase' ? currentValue + 1 : currentValue - 1;
      this.quantityInput.value = newValue;
    },
    async handleSubmit( event, action = 'add_to_cart' ) {
      event.preventDefault();
      const button = action === 'add_to_cart' ? this.addToCartBtn : this.buyNowBtn;
      const oldBtnContent =  button.innerHTML;
      this.setLoadingState(button);
      const formData = new FormData(this.formEl);
      formData.append('action', action === 'add_to_cart' ? ajaxObj.add_to_cart_action : ajaxObj.buy_now_action);
      formData.append('nonce', ajaxObj.nonce);
      if( !this.validateInput( 'date', formData.get('booking-date') ) ) {
        alert('Chọn ngày trước khi submit form!');
        this.removeLoadingState(oldBtnContent, button);
        return;
      }
      try {
        const response = await fetch( ajaxObj.url, {
          method: 'POST',
          body: formData,
        });
        const resData = await response.json();
        if( !response.ok || !resData.success ) {
          throw new Error(resData.data?.message || 'Failed to submit the form');
        }
        console.log(resData);
        await this.refreshCart();
        if( action === 'buy_now' && resData.data?.redirect_url ) {
          window.location.href = resData.data.redirect_url;
        }
      } catch( error ) {
        console.error('Error submitting the form: ', error);
      } finally {
        this.removeLoadingState(oldBtnContent, button);
      }
    },
    validateInput( type = 'date', value ) {
      if (type === 'date') {
        const datePattern = /^\d{4}-\d{2}-\d{2}$/;
        return datePattern.test(value);
      }
      return false;
    },
    setLoadingState(button) {
      const loadingEl = document.createElement('span');
      loadingEl.classList.add('material-symbols-outlined');
      loadingEl.textContent = 'progress_activity';
      button.classList.add('loading');
      button.disabled = true;
      button.textContent = '';
      button.appendChild(loadingEl);
    },
    removeLoadingState(oldContent, button) {
      button.classList.remove('loading');
      button.disabled = false;
      button.innerHTML = oldContent;
    },
    async refreshCart( ) {
      try {
        const response = await fetch('/?wc-ajax=get_refreshed_fragments', { method: 'POST' });
        const data = await response.json();
        if (data.fragments) {
          for (let key in data.fragments) {
            let element = document.querySelector(key);
            if (element) {
              if (element.classList.contains('cart-icon')) {
                const parent = element.parentElement;
                element.remove();
                parent.innerHTML = data.fragments[key];
              } else {
                element.innerHTML = data.fragments[key];
              }
            }
          }
        }
      } catch (error) {
        console.error(error);
      }
    }
  }.init();

  // related products section
  const relatedProductsSection = {
    init() {
      try {
        this.cacheElements();
        this.initSwiper();
      } catch (error) {
        console.warn('RELATED PRODUCTS SWIPER ERROR: ', error);
      }
    },
    cacheElements() {
      this.swiperEl = document.querySelector('.related-products__carousel .swiper');
      if (!this.swiperEl) {
        throw new Error('No swiper element found for related products');
      }
    },
    initSwiper() {
      this.swiper = new Swiper(this.swiperEl, {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
          nextEl: '.related-products__carousel .gpw-nav-btn__next',
          prevEl: '.related-products__carousel .gpw-nav-btn__prev',
        },
        pagination: {
          el: '.related-products__carousel .gpw-pagination',
          clickable: true,
        },
        breakpoints: {
          550: {
            slidesPerView: 2,
          },
          850: {
            slidesPerView: 4,
          }
        }
      });
    },
  }.init();
});
