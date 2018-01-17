/**
 * Custom JavaScript functions for the customizer controls.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */
var hannoverCustomizeControls = (function (api, wp) {
	'use strict';

	var component = {
		l10n: {},
		categories: {},
		themeMods: {},
		portfolioPageSectionTitleMarkup: '',
		portfolioArchivePageSectionTitleMarkup: '',
		portfolioCategoryPageSectionTitleMarkup: '',
		portfolioCategoryPagesNextId: 1,
		removedPortfolioCategorySections: []
	};

	/**
	 * Function to init the customize control code of the Hannover theme.
	 *
	 * @param {object} data - Data exports from PHP.
	 * @param {object} data.l10n - Translation strings.
	 * @param {object} data.categories - Categories.
	 * @param {object} data.themeMods - Theme mods.
	 */
	component.init = function init(data) {
		// Check if we have a data object.
		if (data) {
			// Check for the l10n key and add the value to component.l10n.
			if (data.l10n) {
				_.extend(component.l10n, data.l10n);
			}

			// Check for the categories key and add the value to component.categories.
			if (data.categories) {
				_.extend(component.categories, data.categories);
			}

			// Check for the themeMods key and add the value to component.themeMods.
			if (data.themeMods) {
				_.extend(component.themeMods, data.themeMods);

				if (undefined !== component.themeMods.portfolio_category_page && false === component.isEmptyObject(component.themeMods.portfolio_category_page)) {
					// Set next highest int as ID for next category page.
					// https://stackoverflow.com/a/27376421/7774451
					component.portfolioCategoryPagesNextId = Number.parseInt(Object.keys(component.themeMods.portfolio_category_page).reduce(function (a, b) {
						return component.themeMods.portfolio_category_page[a] > component.themeMods.portfolio_category_page[b] ? a : b
					})) + 1;
				}

				console.log(data.themeMods);
			}
		}

		// Run the component.ready function after the customize ready event was fired.
		api.bind('ready', component.ready);
	};

	/**
	 * Run when customizer is ready.
	 */
	component.ready = function ready() {
		// Create the panel and sections.
		component.createPanel();

		// Add section and controls for the main portfolio page options.
		component.createPortfolioPageOptions();

		// Add section and controls for the portfolio archive page options.
		component.createPortfolioArchivePageOptions();

		// Add sections and controls for the existing portfolio category pages options.
		if (undefined !== component.themeMods.portfolio_category_page && false === component.isEmptyObject(component.themeMods.portfolio_category_page)) {
			for (var key in component.themeMods.portfolio_category_page) {
				component.createPortfolioCategoryPageOptions(key);
			}
		}

		// Add section for the next new portfolio category page.
		component.createPortfolioCategoryPageOptions(component.portfolioCategoryPagesNextId);

		// Display the template for adding a portfolio page if no page exists.
		component.displayAddPortfolioPageTemplate();

		// Display/hide the add portfolio page hint on change of the portfolio page select control.
		component.toggleAddPortfolioPageTemplate();

		// Display the template for adding a portfolio archive page if no page exists.
		component.displayAddPortfolioArchivePageTemplate();

		// Display/hide the add portfolio page hint on change of the portfolio page select control.
		component.toggleAddPortfolioArchivePageTemplate();

		component.displayPortfolioSectionHeadDescription();

		component.displayPortfolioArchiveSectionHeadDescription();

		component.displayAddPortfolioCategoryPageTemplate();

		component.displayPortfolioCategoryPagesSectionHeadDescription();

		api.panel('hannover_theme_options', function (panel) {
			// Add click event listener to the portfolio page section container.
			panel.container.find('.hannover-customize-create-portfolio-page').on('click', function () {
				api.section('hannover_portfolio_page_section').expand();
			});

			// Add click event listener to the portfolio archive page section container.
			panel.container.find('#accordion-section-hannover_portfolio_archive_page_options').on('click', function () {
				api.section('hannover_portfolio_archive_page_options').expand();
			});
		});
	};

	/**
	 * Creates the customize panel for the theme options.
	 */
	component.createPanel = function createPanel() {
		api.panel.add(
			new api.Panel('hannover_theme_options', {
				title: (component.l10n.panelTitle ? component.l10n.panelTitle : 'Theme Options'),
			})
		);
	};

	/**
	 * Creates the needed customize things for the main portfolio page.
	 */
	component.createPortfolioPageOptions = function createSections() {
		// Add the section.
		api.section.add(
			new api.Section('hannover_portfolio_page_section', {
				title: 'Portfolio page',
				panel: 'hannover_theme_options',
				customizeAction: 'Customizing ▸ Theme Options'
			})
		);

		// Add control to select portfolio page.
		api.control.add(
			new api.Control('portfolio_page', {
				setting: 'portfolio_page[id]',
				type: 'dropdown-pages',
				section: 'hannover_portfolio_page_section',
				label: 'Portfolio page',
				allow_addition: true
			})
		);

		// Add control to use category for portfolio posts.
		api.control.add(
			new api.Control('portfolio_from_category', {
				setting: 'portfolio_page[from_category]',
				type: 'checkbox',
				section: 'hannover_portfolio_page_section',
				label: 'Use a category instead of all gallery and image posts for portfolio elements.'
			})
		);

		// Add control to select portfolio category.
		api.control.add(
			new api.Control('portfolio_category', {
				setting: 'portfolio_page[category]',
				type: 'hannover_categories_control',
				section: 'hannover_portfolio_page_section',
				label: 'Portfolio category',
				choices: component.categories
			})
		);

		// Add control to remove portfolio category from category widget.
		api.control.add(
			new api.Control('portfolio_remove_category_from_cat_list', {
				setting: 'portfolio_page[remove_category_from_cat_list]',
				type: 'checkbox',
				section: 'hannover_portfolio_page_section',
				label: 'Remove portfolio category from category widget.'
			})
		);

		// Add control to remove portfolio posts from blog.
		api.control.add(
			new api.Control('exclude_portfolio_elements_from_blog', {
				setting: 'portfolio_page[exclude_portfolio_elements_from_blog]',
				type: 'checkbox',
				section: 'hannover_portfolio_page_section',
				label: 'Exclude the portfolio elements from the blog.'
			})
		);

		// Add control to define number of portfolio elements per page.
		api.control.add(
			new api.Control('portfolio_elements_per_page', {
				setting: 'portfolio_page[elements_per_page]',
				type: 'number',
				section: 'hannover_portfolio_page_section',
				label: 'Number of elements to show on one page (0 to show all).'
			})
		);

		// Add control to enable alternative layout for portfolio overview.
		api.control.add(
			new api.Control('portfolio_alt_layout', {
				setting: 'portfolio_page[alt_layout]',
				type: 'checkbox',
				section: 'hannover_portfolio_page_section',
				label: 'Use alternative layout for portfolio overview.'
			})
		);
	};

	/**
	 * Creates the needed customize things for the portfolio archive page.
	 */
	component.createPortfolioArchivePageOptions = function createSections() {
		// Add portfolio archive section.
		api.section.add(
			new api.Section('hannover_portfolio_archive_page_options', {
				title: 'Portfolio Archive',
				panel: 'hannover_theme_options',
				customizeAction: 'Customizing ▸ Theme Options'
			})
		);

		// Add control to select portfolio page.
		api.control.add(
			new api.Control('portfolio_archive', {
				setting: 'portfolio_archive[id]',
				type: 'dropdown-pages',
				section: 'hannover_portfolio_archive_page_options',
				label: 'Archive page',
				allow_addition: true
			})
		);

		// Add control to select archive category.
		api.control.add(
			new api.Control('portfolio_archive_category', {
				setting: 'portfolio_archive[category]',
				type: 'hannover_categories_control',
				section: 'hannover_portfolio_archive_page_options',
				label: 'Archive category',
				choices: component.categories
			})
		);

		// Add control to remove archive cat from category widget.
		api.control.add(
			new api.Control('portfolio_archive_remove_category_from_cat_widget', {
				setting: 'portfolio_archive[remove_category_from_cat_widget]',
				type: 'checkbox',
				section: 'hannover_portfolio_archive_page_options',
				label: 'Remove archive category from category widget.'
			})
		);

		// Add control to define number of archive elements per page.
		api.control.add(
			new api.Control('portfolio_archive_elements_per_page', {
				setting: 'portfolio_archive[elements_per_page]',
				type: 'number',
				section: 'hannover_portfolio_archive_page_options',
				label: 'Number of archived portfolio elements to show on one page (0 to show all elements on one page).'
			})
		);

		// Add control to use alternate layout for archive view.
		api.control.add(
			new api.Control('portfolio_archive_alt_layout', {
				setting: 'portfolio_archive[alt_layout]',
				type: 'checkbox',
				section: 'hannover_portfolio_archive_page_options',
				label: 'Use alternative layout for archive.'
			})
		);
	};

	/**
	 * Creates the needed customize things for the portfolio category page(s).
	 *
	 * @param {int} id - ID of the category page options.
	 */
	component.createPortfolioCategoryPageOptions = function createSections(id) {
		var portfolio_page_id_default = '';
		// Check for existing values for the settings.
		if (undefined !== component.themeMods.portfolio_category_page && undefined !== component.themeMods.portfolio_category_page[id]) {
			portfolio_page_id_default = component.themeMods.portfolio_category_page[id].id;
		}

		// Add section for page options.
		api.section.add(
			new api.Section('hannover_portfolio_category_page_section[' + id + ']', {
				title: 'This is a title',
				panel: 'hannover_theme_options',
				customizeAction: 'Customizing ▸ Theme Options'
			})
		);

		// Add setting for page ID.
		api.add(new api.Setting('portfolio_category_page[' + id + '][id]', portfolio_page_id_default));

		// Add control to select page.
		api.control.add(
			new api.Control('portfolio_category_page[' + id + '][id]', {
				setting: 'portfolio_category_page[' + id + '][id]',
				type: 'dropdown-pages',
				section: 'hannover_portfolio_category_page_section[' + id + ']',
				label: 'Category page',
				allow_addition: true
			})
		);

		if (id !== component.portfolioCategoryPagesNextId) {
			api.control.add(
				new api.Control('portfolio_category_page[' + id + '][delete]', {
					section: 'hannover_portfolio_category_page_section[' + id + ']',
					templateId: 'hannover-delete-portfolio-category-page-button',
				})
			)
		} else {
			api.control.add(
				new api.Control('portfolio_category_page[' + id + '][add]', {
					section: 'hannover_portfolio_category_page_section[' + id + ']',
					templateId: 'hannover-add-category-page-button',
				})
			);

			// Add event listener to the add button for new category pages.
			var elements = document.querySelectorAll('button.hannover-add-category-page-button');
			for (var i = 0; i < elements.length; i++) {
				elements[i].addEventListener('click', function () {
					// Get id of list item.
					var listItemId = this.parentElement.parentElement.id;

					// Extract the section index out of the element’s id.
					var sectionIndex = listItemId.match(/customize-control-portfolio_category_page-(\d)-add/);

					// Check if we have a match.
					if (null !== sectionIndex) {
						var id = sectionIndex[1];

						// Close the section.
						api.section('hannover_portfolio_category_page_section[' + id + ']').collapse();

						// Modify the section’s heading element (remove the button and add the normal toggle).
						api.section('hannover_portfolio_category_page_section[' + id + ']').headContainer[0].innerHTML = component.portfolioCategoryPageSectionTitleMarkup;

						// Add event listener.
						api.section('hannover_portfolio_category_page_section[' + id + ']').container.find('.accordion-section-title').on('click', function () {
							api.section('hannover_portfolio_category_page_section[' + id + ']').expand();
						});

						// Remove the add button and add a delete button.
						api.control('portfolio_category_page[' + id + '][add]').active(false);
						api.control.add(
							new api.Control('portfolio_category_page[' + id + '][delete]', {
								section: 'hannover_portfolio_category_page_section[' + id + ']',
								templateId: 'hannover-delete-portfolio-category-page-button',
							})
						);

						// Increase ID for next section.
						component.portfolioCategoryPagesNextId = component.portfolioCategoryPagesNextId + 1;

						// Add new section for adding a new page.
						component.createPortfolioCategoryPageOptions(component.portfolioCategoryPagesNextId);

						// Add button.
						component.displayAddPortfolioCategoryPageTemplate();

						// Add the sections description to first section (if not already there).
						component.displayPortfolioCategoryPagesSectionHeadDescription();
					}
				});
			}

			// Add click event listener to the buttons to remove a category page.
			var elements = document.querySelectorAll('.delete-portfolio-category-page button');
			for (var i = 0; i < elements.length; i++) {
				elements[i].addEventListener('click', function () {
					// Get id of list item.
					var listItemId = this.parentElement.parentElement.id;

					// Extract the section index out of the element’s id.
					var sectionIndex = listItemId.match(/customize-control-portfolio_category_page-(\d)-delete/);

					// Check if we have a match.
					if (null !== sectionIndex) {
						var id = sectionIndex[1];

						// Push ID to array so we know what sections where removed during the session.
						component.removedPortfolioCategorySections.push(id);

						// Close the section.
						api.section('hannover_portfolio_category_page_section[' + id + ']').collapse();

						// Set flag that the setting needs to be removed.
						api.control('portfolio_category_page[' + id + '][deleted]', function (control) {
							control.setting.set(-1);
						});

						// Remove the section.
						api.section('hannover_portfolio_category_page_section[' + id + ']').active(false);

						// Add the sections description to first section (if not already there).
						component.displayPortfolioCategoryPagesSectionHeadDescription();
					}
				});
			}
		}

		// Add setting for removal flag.
		api.add(new api.Setting('portfolio_category_page[' + id + '][deleted]'));

		// Add control for removal flag.
		api.control.add(
			new api.Control('portfolio_category_page[' + id + '][deleted]', {
				setting: 'portfolio_category_page[' + id + '][deleted]',
				type: 'number',
				section: 'hannover_portfolio_category_page_section[' + id + ']',
				active: false,
			})
		);
	};

	/**
	 * Adds a new section with options for portfolio category page options.
	 */
	component.addNewPortfolioCategoryPageSection = function addNewPortfolioCategoryPageSection() {
		component.portfolioCategoryPagesNextId = component.portfolioCategoryPagesNextId + 1;
		component.createPortfolioCategoryPageOptions(component.portfolioCategoryPagesNextId);
	};

	/**
	 * Removes a portfolio category page section if no page is selected.
	 *
	 * @param {integer} id ID of the section to remove.
	 */
	component.removePortfolioCategoryPageSection = function removePortfolioCategoryPageSection(id) {

	};

	/**
	 * Display hint that there is no portfolio page currently if needed.
	 */
	component.displayAddPortfolioPageTemplate = function displayAddPortfolioPageTemplate() {
		// Backup the markup of the original portfolio page section so we can restore it later.
		component.portfolioPageSectionTitleMarkup = api.section('hannover_portfolio_page_section').headContainer[0].innerHTML;

		// Check if we have no portfolio page theme mod.
		if (undefined === component.themeMods.portfolio_page) {
			api.section('hannover_portfolio_page_section').headContainer.find('.accordion-section-title').replaceWith(
				wp.template('hannover-no-portfolio-page-notice')
			);
		} else {
			// Check for a portfolio page ID in the theme mods.
			if (!component.themeMods.portfolio_page.id || !Number.isInteger(parseInt(component.themeMods.portfolio_page.id)) || 0 === parseInt(component.themeMods.portfolio_page.id)) {
				api.section('hannover_portfolio_page_section').headContainer.find('.accordion-section-title').replaceWith(
					wp.template('hannover-no-portfolio-page-notice')
				);

				// Remove the border top from the section.
				api.section('hannover_portfolio_page_section').headContainer[0].classList.add('no-border-top');
			}
		}
	};

	/**
	 * Toggle the no-portfolio-page hint on updating the portfolio page select.
	 */
	component.toggleAddPortfolioPageTemplate = function toggleAddPortfolioPageTemplate() {
		api.control('portfolio_page', function (control) {
			control.setting.bind(function (value) {
				// Check if the value was changed to the default state.
				if (0 === parseInt(value) || !Number.isInteger(parseInt(value))) {
					// Display the hint that there is no portfolio page currently.
					api.section('hannover_portfolio_page_section').headContainer.find('.accordion-section-title').replaceWith(
						wp.template('hannover-no-portfolio-page-notice')
					);

					// Add click event listener to button.
					api.panel('hannover_theme_options', function (panel) {
						// Add click event listener to the portfolio page section container.
						panel.container.find('.hannover-customize-create-portfolio-page').on('click', function () {
							api.section('hannover_portfolio_page_section').expand();
						});
					});
				} else {
					// Display the normal section title.
					api.section('hannover_portfolio_page_section').headContainer[0].innerHTML = component.portfolioPageSectionTitleMarkup;

					// Redirect to the new URL.
					api.previewer.previewUrl.set(api.settings.url.home + '?page_id=' + value);

					// Add panel description and event listener.
					api.section('hannover_portfolio_page_section', function (section) {
						section.headContainer.prepend(
							wp.template('hannover-portfolio-section-title')
						);

						section.container.find('.accordion-section-title').on('click', function () {
							api.section('hannover_portfolio_page_section').expand();
						});
					});
				}
			});
		});
	};

	/**
	 * Display hint that there is no portfolio archive page currently if needed.
	 */
	component.displayAddPortfolioArchivePageTemplate = function displayAddPortfolioArchivePageTemplate() {
		// Backup the markup of the original portfolio archive page section so we can restore it later.
		component.portfolioArchivePageSectionTitleMarkup = api.section('hannover_portfolio_archive_page_options').headContainer[0].innerHTML;

		// Check if we have no portfolio archive page theme mod.
		if (undefined === component.themeMods.portfolio_archive) {
			api.section('hannover_portfolio_archive_page_options').headContainer.find('.accordion-section-title').replaceWith(
				wp.template('hannover-no-portfolio-archive-page-notice')
			);
		} else {
			// Check for a portfolio archive page ID in the theme mods.
			if (!component.themeMods.portfolio_archive.id || !Number.isInteger(parseInt(component.themeMods.portfolio_archive.id)) || 0 === parseInt(component.themeMods.portfolio_archive.id)) {
				api.section('hannover_portfolio_archive_page_options').headContainer.find('.accordion-section-title').replaceWith(
					wp.template('hannover-no-portfolio-archive-page-notice')
				);

				// Remove the border top from the section.
				api.section('hannover_portfolio_archive_page_options').headContainer[0].classList.add('no-border-top');
			}
		}
	};

	/**
	 * Toggle the no-portfolio-archive-page hint on updating the portfolio archive page select.
	 */
	component.toggleAddPortfolioArchivePageTemplate = function toggleAddPortfolioArchivePageTemplate() {
		api.control('portfolio_archive', function (control) {
			control.setting.bind(function (value) {
				// Check if the value was changed to the default state.
				if (0 === parseInt(value) || !Number.isInteger(parseInt(value))) {
					// Display the hint that there is no portfolio page currently.
					api.section('hannover_portfolio_archive_page_options').headContainer.find('.accordion-section-title').replaceWith(
						wp.template('hannover-no-portfolio-archive-page-notice')
					);

					// Add no-border-top class.
					api.section('hannover_portfolio_archive_page_options').headContainer[0].classList.add('no-border-top');
				} else {
					// Display the normal section title.
					api.section('hannover_portfolio_archive_page_options').headContainer[0].innerHTML = component.portfolioArchivePageSectionTitleMarkup;

					component.displayPortfolioArchiveSectionHeadDescription();

					// Redirect to the new URL.
					api.previewer.previewUrl.set(api.settings.url.home + '?page_id=' + value);

					// Remove no-border-top class.
					api.section('hannover_portfolio_archive_page_options').headContainer[0].classList.remove('no-border-top');
				}
			});
		});
	};

	/**
	 * Display hint to add portfolio category page.
	 */
	component.displayAddPortfolioCategoryPageTemplate = function displayAddPortfolioCategoryPageTemplate() {
		// Get the ID of the new section.
		var sectionName = 'hannover_portfolio_category_page_section[' + component.portfolioCategoryPagesNextId + ']';
		// Backup the markup of the original portfolio page section so we can restore it later.
		component.portfolioCategoryPageSectionTitleMarkup = api.section(sectionName).headContainer[0].innerHTML;

		api.section(sectionName).headContainer.find('.accordion-section-title').replaceWith(
			wp.template('hannover-add-portfolio-category-page-button')
		);

		api.section(sectionName).headContainer.find('.hannover-customize-create-portfolio-category-page').on('click', function () {
			api.section(sectionName).expand();
		});
	};

	component.displayPortfolioSectionHeadDescription = function displayPortfolioSectionHeadDescription() {
		api.section('hannover_portfolio_page_section', function (section) {
			section.headContainer.prepend(
				wp.template('hannover-portfolio-section-title')
			);
		});
	};

	component.displayPortfolioArchiveSectionHeadDescription = function displayPortfolioArchiveSectionHeadDescription() {
		api.section('hannover_portfolio_archive_page_options', function (section) {
			section.headContainer.prepend(
				wp.template('hannover-portfolio-archive-notice')
			);
		});
	};

	component.displayPortfolioCategoryPagesSectionHeadDescription = function displayPortfolioCategoryPagesSectionHeadDescription() {
		// Get the ID of the first visible category pages li node.
		var firstPortfolioCategoryPagesSectionNode = component.getFirstVisiblePortfolioCategorySection();

		// Check if the node already contains the section description.
		// For that, we get the first child and check if it has the class hannover-category-pages-sections-description.
		var firstChild = firstPortfolioCategoryPagesSectionNode.firstElementChild;
		if ( ! firstChild.classList.contains('hannover-category-pages-sections-description')) {
			var firstPortfolioCategoryPagesSectionNodeId = firstPortfolioCategoryPagesSectionNode.id;
			var sectionName = firstPortfolioCategoryPagesSectionNodeId.replace('accordion-section-', '');
			api.section(sectionName, function (section) {
				section.headContainer.prepend(
					wp.template('hannover-portfolio-category-pages-notice')
				);
			});
		}
	};

	/**
	 * Check if object is empty.
	 *
	 * @link https://stackoverflow.com/a/32108184/7774451
	 *
	 * @param {object} obj – Object to test.
	 * @returns {boolean} true if empty, false otherwise.
	 */
	component.isEmptyObject = function isEmptyObject(obj) {
		for (var prop in obj) {
			if (obj.hasOwnProperty(prop))
				return false;
		}

		return JSON.stringify(obj) === JSON.stringify({});
	};

	/**
	 * Get first visible portfolio category section.
	 *
	 * @returns {obj} Node obj of first visible portfolio category section.
	 */
	component.getFirstVisiblePortfolioCategorySection = function getFirstVisiblePortfolioCategorySection() {
		var elem = document.getElementById('accordion-section-hannover_portfolio_archive_page_options').nextSibling;
		while(elem) {
			// Check if this is a category page section toggle.
			if ( 0 === elem.id.search(/accordion-section-hannover_portfolio_category_page_section\[\d\]/g) ) {
				// Get ID of section.
				var sectionIndex = elem.id.match(/accordion-section-hannover_portfolio_category_page_section\[(\d)\]/);

				if (null !== sectionIndex) {
					var id = sectionIndex[1];

					// Check if ID does not exist in component.removedPortfolioCategorySections
					if ( false === component.removedPortfolioCategorySections.includes(id) ) {
						return elem;
					}
				}

				elem = elem.nextSibling;
			}
		}
	};

	return component;
})(wp.customize, wp);

// https://tc39.github.io/ecma262/#sec-array.prototype.includes
if (!Array.prototype.includes) {
	Object.defineProperty(Array.prototype, 'includes', {
		value: function(searchElement, fromIndex) {

			if (this == null) {
				throw new TypeError('"this" is null or not defined');
			}

			// 1. Let O be ? ToObject(this value).
			var o = Object(this);

			// 2. Let len be ? ToLength(? Get(O, "length")).
			var len = o.length >>> 0;

			// 3. If len is 0, return false.
			if (len === 0) {
				return false;
			}

			// 4. Let n be ? ToInteger(fromIndex).
			//    (If fromIndex is undefined, this step produces the value 0.)
			var n = fromIndex | 0;

			// 5. If n ≥ 0, then
			//  a. Let k be n.
			// 6. Else n < 0,
			//  a. Let k be len + n.
			//  b. If k < 0, let k be 0.
			var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

			function sameValueZero(x, y) {
				return x === y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
			}

			// 7. Repeat, while k < len
			while (k < len) {
				// a. Let elementK be the result of ? Get(O, ! ToString(k)).
				// b. If SameValueZero(searchElement, elementK) is true, return true.
				if (sameValueZero(o[k], searchElement)) {
					return true;
				}
				// c. Increase k by 1.
				k++;
			}

			// 8. Return false
			return false;
		}
	});
}
/*(function () {
	wp.customize.bind('ready', function () {

		// Add a section.
		api.section.add(
			new api.Section('hannover_portfolio_category_page', {
				title: 'This is a title',
				panel: 'hannover_theme_options',
				customizeAction: 'Customizing ▸ Theme Options'
			})
		);

		var setting = new api.Setting( 'portfolio_category_page' );
		api.add( setting );

		// Add control to select the category to show on the page.
		api.control.add(
			new api.Control('portfolio_category_page', {
				setting: setting,
				type: 'text',
				section: 'hannover_portfolio_category_page',
				label: 'Choose page',
			})
		);

		// Loop them.
		portfolioCategoryPages.forEach(function (portfolioCategoryPage) {
			// Get the ID of the page.
			var id = portfolioCategoryPage['ID'];

			// Get the title.
			var title = portfolioCategoryPage['post_title'];

			// Add a section.
			api.section.add(
				new api.Section('hannover_portfolio_category_page_' + id, {
					title: title,
					panel: 'hannover_theme_options',
					customizeAction: 'Customizing ▸ Theme Options'
				})
			);

			// Add control to select the category to show on the page.
			api.control.add(
				new api.Control('portfolio_category_page_' + id, {
					setting: 'portfolio_category_page_' + id,
					type: 'hannover_categories_control',
					section: 'hannover_portfolio_category_page_' + id,
					label: 'Choose portfolio category to show',
					choices: customizeRegisterObject.categories
				})
			);

			// Add control to define number of posts per page.
			api.control.add(
				new api.Control('portfolio_category_page_elements_per_page_' + id, {
					setting: 'portfolio_category_page_elements_per_page_' + id,
					type: 'number',
					section: 'hannover_portfolio_category_page_' + id,
					label: 'Number of elements to show on one page (0 to show all).'
				})
			);

			// Add control to enable alternative layout for portfolio overview.
			api.control.add(
				new api.Control('portfolio_category_page_alt_layout_' + id, {
					setting: 'portfolio_category_page_alt_layout_' + id,
					type: 'checkbox',
					section: 'hannover_portfolio_category_page_' + id,
					label: 'Use alternative layout.'
				})
			);
		});

		// Add section for slider settings.
		api.section.add(
			new api.Section('hannover_slider_settings', {
				title: 'Slider settings',
				panel: 'hannover_theme_options',
				customizeAction: 'Customizing ▸ Theme Options'
			})
		);

		// Add control to enable slider autoplay.
		api.control.add(
			new api.Control('slider_autoplay', {
				setting: 'slider_autoplay',
				type: 'checkbox',
				section: 'hannover_slider_settings',
				label: 'Enable autoplay.'
			})
		);

		// Add control to define slider time.
		api.control.add(
			new api.Control('slider_autoplay_time', {
				setting: 'slider_autoplay_time',
				type: 'number',
				section: 'hannover_slider_settings',
				label: 'Time in milliseconds to show each image with autoplay.'
			})
		);

		// Add control to display all galleries as sliders.
		api.control.add(
			new api.Control('galleries_as_slider', {
				setting: 'galleries_as_slider',
				type: 'checkbox',
				section: 'hannover_slider_settings',
				label: 'Display all galleries as sliders.'
			})
		);
	});
})();
*/