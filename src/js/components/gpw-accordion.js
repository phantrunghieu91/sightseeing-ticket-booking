export default class GPWAccordion {
  /**
   * Initialize accordion components on the page
   */
  constructor() {
    this.accordions = document.querySelectorAll('.accordion');
    
    if (this.accordions.length === 0) return;
    
    this.accordions.forEach(accordion => this.initAccordion(accordion));
  }

  /**
   * Set up a single accordion with event listeners
   * @param {HTMLElement} accordion - The accordion container element
   */
  /**
   * Initializes the accordion functionality for the given accordion element.
   * Sets up event listeners on accordion buttons to toggle panels and dispatches
   * a custom event ('gpw_accordion:toggle') when a button is clicked.
   *
   * @param {HTMLElement} accordion - The root element of the accordion component.
   */
  initAccordion(accordion) {
    const buttons = accordion.querySelectorAll('.accordion__button');
    const panels = accordion.querySelectorAll('.accordion__panel');

    // bind custom event listeners
    accordion.addEventListener('gpw_accordion:toggle', event => {
      const { button, buttons, panels } = event.detail;
      this.handleButtonClick(button, buttons, panels);
    });

    buttons.forEach(button => {
      button.addEventListener('click', () => {
        const toggleEvent = new CustomEvent('gpw_accordion:toggle', {
          detail: {
            button: button,
            buttons: buttons,
            panels: panels
          }, 
        });

        accordion.dispatchEvent(toggleEvent);
      });
    });
  }

  /**
   * Handle accordion button click events
   * @param {HTMLElement} clickedButton - The button that was clicked
   * @param {NodeList} allButtons - All buttons in this accordion
   * @param {NodeList} allPanels - All panels in this accordion
   */
  handleButtonClick(clickedButton, allButtons, allPanels) {
    const expanded = clickedButton.getAttribute('aria-expanded') === 'true' || false;
    const panel = clickedButton.nextElementSibling;
    
    // Close all sections
    this.closeAllSections(allButtons, allPanels);
    
    // Toggle the clicked section
    this.toggleSection(clickedButton, panel, expanded);
  }

  /**
   * Close all accordion sections
   * @param {NodeList} buttons - All buttons in the accordion
   * @param {NodeList} panels - All panels in the accordion
   */
  closeAllSections(buttons, panels) {
    buttons.forEach(btn => {
      btn.setAttribute('aria-expanded', 'false');
      this.changeIcon(btn, true);
    });
    
    panels.forEach(panel => {
      panel.setAttribute('aria-hidden', 'true');
    });
  }

  /**
   * Toggle a specific accordion section
   * @param {HTMLElement} button - The button to toggle
   * @param {HTMLElement} panel - The panel to toggle
   * @param {boolean} wasExpanded - Whether the section was previously expanded
   */
  toggleSection(button, panel, wasExpanded) {
    const shouldExpand = !wasExpanded;
    
    button.setAttribute('aria-expanded', String(shouldExpand));
    panel.setAttribute('aria-hidden', String(!shouldExpand));
    this.changeIcon(button, !shouldExpand);
  }

  /**
   * Update the icon in an accordion button based on expanded state
   * @param {HTMLElement} button - The button containing the icon
   * @param {boolean} expanded - Whether the section is expanded
   */
  changeIcon(button, expanded) {
    const icon = button.querySelector('.accordion__button-icon');
    if (icon) {
      icon.textContent = expanded ? 'add' : 'remove';
    }
  }
}
