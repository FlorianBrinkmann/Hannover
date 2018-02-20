require('babel-polyfill');
(function () {
	const root = document.documentElement,
		menuToggle = document.getElementById('menu-toggle'),
		subMenuLinks = document.querySelectorAll('#menu-main-menu .menu-item-has-children > a'),
		siteNavigation = document.getElementById('main-menu-container');

	// remove no-js class from HTML element and add js class.
	root.classList.remove('no-js');
	root.classList.add('js');

	initMainNavigation();

	function initMainNavigation() {
		// Create toggle button.
		let button = dropdownToggleButtonMarkup(),
			dropdownToggles;

		// Add button to links.
		for (let subMenuLink of subMenuLinks) {
			subMenuLink.appendChild(button);
		}

		// Get all buttons.
		dropdownToggles = document.querySelectorAll('#menu-main-menu .dropdown-toggle');

		// Add event listener to buttons.
		for (let dropdownToggle of dropdownToggles) {
			dropdownToggle.addEventListener('click', function (e) {
				let screenReaderSpan = this.childNodes[0],
					subMenuContainer = this.parentElement.nextElementSibling;

				e.preventDefault();

				this.classList.toggle('toggled-on');
				subMenuContainer.classList.toggle('toggled-on');

				this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false');

				if (screenReaderText.expand === screenReaderSpan.text) {
					screenReaderSpan.text = screenReaderText.collapse;
				} else {
					screenReaderSpan.text = screenReaderText.expand;
				}
			});
		}

		// Run menu toggle logic.
		initMenuToggle();

		if ('ontouchstart' in window) {
			window.addEventListener('resize', toggleFocusClassTouch);
			toggleFocusClassTouch();
		}

		toggleFocusClassKeyboard();

		onResizeAria();
		window.addEventListener('resize', function () {
			onResizeAria();
		});
	}

	function dropdownToggleButtonMarkup() {
		let button = document.createElement('button'),
			screenReaderSpan = document.createElement('span'),
			screenReaderSpanText = document.createTextNode(screenReaderText.expand),
			svgElem = document.createElementNS('http://www.w3.org/2000/svg', 'svg'),
			useElem = document.createElementNS('http://www.w3.org/2000/svg', 'use');

		// Build the button.
		button.classList.add('dropdown-toggle');
		button.setAttribute('aria-expanded', 'false');

		screenReaderSpan.classList.add('screen-reader-text');
		screenReaderSpan.appendChild(screenReaderSpanText);

		useElem.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#down-arrow');

		svgElem.appendChild(useElem);

		button.appendChild(screenReaderSpan);
		button.appendChild(svgElem);
		return button;
	}

	/**
	 * Menu toggle button logic.
	 */
	function initMenuToggle() {
		if (null === menuToggle) {
			return;
		}

		menuToggle.setAttribute('aria-expanded', 'false');
		siteNavigation.setAttribute('aria-expanded', 'false');

		menuToggle.addEventListener('click', function () {
			this.classList.toggle('toggled-on');
			siteNavigation.classList.toggle('toggled-on');

			this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false');
			siteNavigation.setAttribute('aria-expanded', siteNavigation.getAttribute('aria-expanded') === 'false' ? 'true' : 'false');
		});
	}

	/**
	 * Set focus class for touch devices.
	 */
	function toggleFocusClassTouch() {
		if (window.innerWidth >= 910) {
			document.body.addEventListener('touchstart', function (e) {
				// Check if that was not a click on a menu item with dropdown toggle.
				if (e.target.firstElementChild !== null && (e.target.nodeType !== 1 || false === e.target.firstElementChild.classList.contains('dropdown-toggle'))) {
					for (let subMenuLink of subMenuLinks) {
						subMenuLink.parentElement.classList.remove('focus');
					}
				}
			});
			for (let subMenuLink of subMenuLinks) {
				subMenuLink.addEventListener('touchstart', function (e) {
					let el = this.parentElement;

					if (false === el.classList.contains('focus')) {
						let siblingListItems = getSiblings(this.parentElement);
						e.preventDefault();
						el.classList.toggle('focus');
						for (let siblingListItem of siblingListItems) {
							if (siblingListItem.classList.contains('focus')) {
								siblingListItem.classList.remove('focus')
							}
						}
					}
				});
			}
		} else {
			for (let subMenuLink of subMenuLinks) {
				subMenuLink.removeEventListener('touchstart');
			}
		}
	}

	/**
	 * Set focus class for keyboard.
	 */
	function toggleFocusClassKeyboard() {
		let menuLinks = document.querySelectorAll('#menu-main-menu a');
		for (let menuLink of menuLinks) {
			menuLink.addEventListener('focus', function () {
				toggleFocusClassOnParents(this);
			});
			menuLink.addEventListener('blur', function () {
				toggleFocusClassOnParents(this);
			});
		}
	}

	/**
	 * Toggles aria attributes for navigation elemes.
	 */
	function onResizeAria() {
		if (window.innerWidth < 910) {
			if (menuToggle.classList.contains('toggled-on')) {
				menuToggle.setAttribute('aria-expanded', 'true');
			} else {
				menuToggle.setAttribute('aria-expanded', 'false');
			}

			if (siteNavigation.classList.contains('toggled-on')) {
				siteNavigation.setAttribute('aria-expanded', 'true');
			} else {
				siteNavigation.setAttribute('aria-expanded', 'false');
			}

			menuToggle.setAttribute('aria-controls', 'menu-main-menu');
		} else {
			menuToggle.removeAttribute('aria-expanded');
			siteNavigation.removeAttribute('aria-expanded');
			menuToggle.removeAttribute('aria-controls');
		}
	}

	/**
	 * Toggle focus class on parent .menu-item elems.
	 *
	 * @param {Node} elem - The reference element.
	 */
	function toggleFocusClassOnParents(elem) {
		let parentMenuItems = getParents(elem, '.menu-item');
		parentMenuItems.forEach(function (item) {
			item.classList.toggle('focus');
		});
	}

	/**
	 * Get parent selectors.
	 *
	 * @link https://gist.github.com/ziggi/2f15832b57398649ee9b
	 *
	 * @param {NodeSelector} element - The element we search the parents of.
	 * @param {string} selector - The selector.
	 *
	 * @return {Array}
	 */
	function getParents(element, selector) {
		let elements = [];
		let elem = element;
		const ishaveselector = selector !== undefined;

		while ((elem = elem.parentElement) !== null) {
			if (elem.nodeType !== Node.ELEMENT_NODE) {
				continue;
			}

			if (!ishaveselector || elem.matches(selector)) {
				elements.push(elem);
			}
		}

		return elements
	}

	/**
	 * Helper function to get siblings.
	 *
	 * @link https://stackoverflow.com/a/842346
	 *
	 * @param {Object} n – First sibling of elem we search all siblings for.
	 * @param {Object} skipMe – The element we search the siblings for.
	 * @returns {Array}
	 */
	function getChildren(n, skipMe) {
		let r = [];
		for (; n; n = n.nextSibling)
			if (n.nodeType === 1 && n !== skipMe)
				r.push(n);
		return r;
	}

	/**
	 * Function to get siblings of element n.
	 *
	 * @param {Object} n – Element to get siblings for.
	 * @returns {Array}
	 */
	function getSiblings(n) {
		return getChildren(n.parentNode.firstChild, n);
	}
})();
