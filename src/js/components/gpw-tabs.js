export default class GPWTabs {
  constructor(parentSelector = '.tabs') {
    this.tabGroups = [];
    const tabContainers = document.querySelectorAll(parentSelector);
    
    if (!tabContainers.length) return;
    
    // Process each tab container separately
    tabContainers.forEach(container => {
      const navItems = Array.from(container.querySelectorAll('.tabs__nav-item'));
      const panelElements = Array.from(container.querySelectorAll('.tabs__panel'));
      
      if (!navItems.length || !panelElements.length) return;
      
      // Create panel map for this specific group
      const panels = panelElements.reduce((acc, panel) => {
        acc[panel.id] = panel;
        return acc;
      }, {});
      
      // Store the group
      this.tabGroups.push({ container, navItems, panels });
      
      // Add event listeners for this group
      this.addEvents(navItems, panels);
    });
  }
  
  addEvents(navItems, panels) {
    navItems.forEach(navItem => {
      navItem.addEventListener('click', event => {
        this.handleSwitchTab(event, navItem, navItems, panels);
      });
    });
  }
  
  handleSwitchTab(event, self, navItems, panels) {
    if(self.getAttribute('aria-selected') === 'true') {
      return;
    }
    
    const currentSelectedTab = navItems.find(item => item.getAttribute('aria-selected') === 'true');
    const currentSelectedPanel = panels[currentSelectedTab.getAttribute('aria-controls')];
    const newSelectedPanel = panels[self.getAttribute('aria-controls')];

    this.updateTabState(currentSelectedTab, self, currentSelectedPanel, newSelectedPanel);
  }

  updateTabState( oldTab, newTab, oldPanel, newPanel ) {
    // Update the state of the old tab
    oldTab.setAttribute('aria-selected', 'false');
    oldTab.setAttribute('tabindex', '-1');
    oldPanel.setAttribute('aria-hidden', 'true');

    // Update the state of the new tab
    newTab.setAttribute('aria-selected', 'true');
    newTab.setAttribute('tabindex', '0');
    newPanel.setAttribute('aria-hidden', 'false');
  }
}