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

		component.createSliderOptions();

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

		component.bindPortfolioPageSelect();

		// Display the template for adding a portfolio archive page if no page exists.
		component.displayAddPortfolioArchivePageTemplate();

		component.bindPortfolioArchivePageSelect();

		component.displayPortfolioSectionHeadDescription();

		component.displayPortfolioArchiveSectionHeadDescription();

		component.displayAddPortfolioCategoryPageTemplate();

		component.displayPortfolioCategoryPagesSectionHeadDescription();

		component.togglePortfolioSections();

		api.panel('hannover_theme_options', function (panel) {
			// Add click event listener to the portfolio page section container.
			panel.container.find('.hannover-open-portfolio-page-section-button').on('click', function () {
				api.section('hannover_portfolio_page_section').expand();
			});

			// Add click event listener to the portfolio archive page section container.
			panel.container.find('.hannover-open-portfolio-archive-section-button').on('click', function () {
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

		// Add control for flag setting if portfolio page is active.
		api.control.add(
			new api.Control('portfolio_page_active', {
				setting: 'portfolio_page[active]',
				type: 'number',
				section: 'hannover_portfolio_page_section',
				active: false
			})
		);

		// Add deactivate button.
		api.control.add(
			new api.Control('portfolio_page_deactivate', {
				section: 'hannover_portfolio_page_section',
				templateId: 'hannover-deactivate-portfolio-button',
			})
		);

		// Add activate button.
		api.control.add(
			new api.Control('portfolio_page_activate', {
				section: 'hannover_portfolio_page_section',
				templateId: 'hannover-create-portfolio-button',
			})
		);

		// Add event listeners to the buttons.
		component.addPortfolioDeactivateButtonAction();
		component.addPortfolioCreateButtonAction();

		// Remove unecessary button.
		if (undefined !== component.themeMods.portfolio_page['active'] && 1 === component.themeMods.portfolio_page['active']) {
			// Hide activate button.
			api.control('portfolio_page_activate').active(false);
		} else {
			// Hide deactivate button.
			api.control('portfolio_page_deactivate').active(false);
		}
	};

	/**
	 * Adds event listener to the create portfolio button and runs the necessary actions.
	 */
	component.addPortfolioCreateButtonAction = function addPortfolioButtonAction() {
		var createButton = document.querySelector('button.hannover-create-portfolio-button');
		if (createButton) {
			createButton.addEventListener('click', function () {
				// Close the section.
				api.section('hannover_portfolio_page_section').collapse();

				// Modify the section’s heading element (remove the button and add the normal toggle).
				api.section('hannover_portfolio_page_section').headContainer[0].innerHTML = component.portfolioPageSectionTitleMarkup;

				// Add event listener.
				api.section('hannover_portfolio_page_section').container.find('.accordion-section-title').on('click', function () {
					api.section('hannover_portfolio_page_section').expand();
				});

				// Add panel description.
				api.section('hannover_portfolio_page_section', function (section) {
					section.headContainer.prepend(
						wp.template('hannover-portfolio-section-title')
					);
				});

				// Set value for flag that the portfolio is active.
				api.control('portfolio_page_active', function (control) {
					control.setting.set(1);
				});

				// Hide the add button and display delete button.
				api.control('portfolio_page_activate').active(false);
				api.control('portfolio_page_deactivate').active(true);

				component.togglePortfolioSections();
			});
		}
	};

	/**
	 * Adds event listener to the deactivate portfolio button and runs the necessary actions.
	 */
	component.addPortfolioDeactivateButtonAction = function addPortfolioDeactivateButtonAction() {
		var deactivateButton = document.querySelector('button.hannover-deactivate-portfolio-button');
		if (deactivateButton) {
			deactivateButton.addEventListener('click', function () {
				// Close the section.
				api.section('hannover_portfolio_page_section').collapse();

				// Add no portfolio hint.
				api.section('hannover_portfolio_page_section').headContainer.find('.accordion-section-title').replaceWith(
					wp.template('hannover-no-portfolio-page-notice')
				);

				// Add event listener.
				api.section('hannover_portfolio_page_section').container.find('.hannover-open-portfolio-page-section-button').on('click', function () {
					api.section('hannover_portfolio_page_section').expand();
				});

				// Set value for flag that the portfolio is inactive.
				api.control('portfolio_page_active', function (control) {
					control.setting.set(0);
				});

				// Hide the delete button and display add button.
				api.control('portfolio_page_deactivate').active(false);
				api.control('portfolio_page_activate').active(true);

				component.togglePortfolioSections();
			});
		}
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

		// Add control for flag setting if portfolio page is active.
		api.control.add(
			new api.Control('portfolio_archive_active', {
				setting: 'portfolio_archive[active]',
				type: 'number',
				section: 'hannover_portfolio_archive_page_options',
				active: false
			})
		);

		// Add deactivate button.
		api.control.add(
			new api.Control('portfolio_archive_deactivate', {
				section: 'hannover_portfolio_archive_page_options',
				templateId: 'hannover-deactivate-portfolio-archive-button',
			})
		);

		// Add activate button.
		api.control.add(
			new api.Control('portfolio_archive_activate', {
				section: 'hannover_portfolio_archive_page_options',
				templateId: 'hannover-create-portfolio-archive-button',
			})
		);

		// Add event listeners to the buttons.
		component.addPortfolioArchiveDeactivateButtonAction();
		component.addPortfolioArchiveCreateButtonAction();

		// Remove unecessary button.
		if (undefined !== component.themeMods.portfolio_archive['active'] && 1 === component.themeMods.portfolio_archive['active']) {
			// Hide activate button.
			api.control('portfolio_archive_activate').active(false);
		} else {
			// Hide deactivate button.
			api.control('portfolio_archive_deactivate').active(false);
		}
	};

	/**
	 * Adds event listener to the create portfolio archive button and runs the necessary actions.
	 */
	component.addPortfolioArchiveCreateButtonAction = function addPortfolioArchiveCreateButtonAction() {
		var createButton = document.querySelector('button.hannover-create-portfolio-archive-button');
		if (createButton) {
			createButton.addEventListener('click', function () {
				// Close the section.
				api.section('hannover_portfolio_archive_page_options').collapse();

				// Modify the section’s heading element (remove the button and add the normal toggle).
				api.section('hannover_portfolio_archive_page_options').headContainer[0].innerHTML = component.portfolioArchivePageSectionTitleMarkup;

				// Add event listener.
				api.section('hannover_portfolio_archive_page_options').container.find('.accordion-section-title').on('click', function () {
					api.section('hannover_portfolio_archive_page_options').expand();
				});

				// Add panel description.
				api.section('hannover_portfolio_archive_page_options', function (section) {
					section.headContainer.prepend(
						wp.template('hannover-portfolio-archive-notice')
					);
				});

				// Set value for flag that the portfolio is active.
				api.control('portfolio_archive_active', function (control) {
					control.setting.set(1);
				});

				// Hide the add button and display delete button.
				api.control('portfolio_archive_activate').active(false);
				api.control('portfolio_archive_deactivate').active(true);
			});
		}
	};

	/**
	 * Adds event listener to the deactivate portfolio archive button and runs the necessary actions.
	 */
	component.addPortfolioArchiveDeactivateButtonAction = function addPortfolioArchiveDeactivateButtonAction() {
		var deactivateButton = document.querySelector('button.hannover-deactivate-portfolio-archive-button');
		if (deactivateButton) {
			deactivateButton.addEventListener('click', function () {
				// Close the section.
				api.section('hannover_portfolio_archive_page_options').collapse();

				// Add no portfolio hint.
				api.section('hannover_portfolio_archive_page_options').headContainer.find('.accordion-section-title').replaceWith(
					wp.template('hannover-no-portfolio-archive-page-notice')
				);

				// Add event listener.
				api.section('hannover_portfolio_archive_page_options').container.find('.hannover-open-portfolio-archive-section-button').on('click', function () {
					api.section('hannover_portfolio_archive_page_options').expand();
				});

				// Set value for flag that the portfolio is ianctive.
				api.control('portfolio_archive_active', function (control) {
					control.setting.set(0);
				});

				// Hide the delete button and display add button.
				api.control('portfolio_archive_deactivate').active(false);
				api.control('portfolio_archive_activate').active(true);
			});
		}
	};

	/**
	 * Creates the needed customize things for the portfolio category page(s).
	 *
	 * @param {int} id - ID of the category page options.
	 */
	component.createPortfolioCategoryPageOptions = function createSections(id) {
		var portfolioCategoryPageIdDefault = '',
			portfolioCategoryPageCategoryDefault = '',
			portfolioCategoryPagePostsPerPageDefault = 10,
			portfolioCategoryPageAltLayoutDefault = 0;
		// Check for existing values for the settings.

		if (undefined !== component.themeMods.portfolio_category_page && undefined !== component.themeMods.portfolio_category_page[id]) {
			if (undefined !== component.themeMods.portfolio_category_page[id]['id']) {
				portfolioCategoryPageIdDefault = component.themeMods.portfolio_category_page[id]['id'];
			}
			if (undefined !== component.themeMods.portfolio_category_page[id]['category']) {
				portfolioCategoryPageCategoryDefault = component.themeMods.portfolio_category_page[id]['category'];
			}
			if (undefined !== component.themeMods.portfolio_category_page['elements_per_page']) {
				portfolioCategoryPagePostsPerPageDefault = component.themeMods.portfolio_category_page[id]['elements_per_page'];
			}
			if (undefined !== component.themeMods.portfolio_category_page[id]['alt_layout']) {
				portfolioCategoryPageAltLayoutDefault = component.themeMods.portfolio_category_page[id]['alt_layout'];
			}
		}

		// Add section for page options.
		api.section.add(
			new api.Section('hannover_portfolio_category_page_section[' + id + ']', {
				title: '',
				panel: 'hannover_theme_options',
				customizeAction: 'Customizing ▸ Category Pages'
			})
		);

		// Add setting for page ID.
		api.add(new api.Setting('portfolio_category_page[' + id + '][id]', portfolioCategoryPageIdDefault));

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

		// Add setting and control for category.
		api.add(new api.Setting('portfolio_category_page[' + id + '][category]', portfolioCategoryPageCategoryDefault));

		api.control.add(
			new api.Control('portfolio_category_page[' + id + '][category]', {
				setting: 'portfolio_category_page[' + id + '][category]',
				type: 'hannover_categories_control',
				section: 'hannover_portfolio_category_page_section[' + id + ']',
				label: 'Category to display portfolio elements from',
				choices: component.categories
			})
		);

		// Add setting and control for number of posts per page.
		api.add(new api.Setting('portfolio_category_page[' + id + '][elements_per_page]', portfolioCategoryPagePostsPerPageDefault));

		api.control.add(
			new api.Control('portfolio_category_page[' + id + '][elements_per_page]', {
				setting: 'portfolio_category_page[' + id + '][elements_per_page]',
				type: 'number',
				section: 'hannover_portfolio_category_page_section[' + id + ']',
				label: 'Number of elements to show on one page (0 to show all).'
			})
		);

		// Add setting and control to use alternate layout for category page.
		api.add(new api.Setting('portfolio_category_page[' + id + '][alt_layout]', portfolioCategoryPageAltLayoutDefault));

		api.control.add(
			new api.Control('portfolio_category_page[' + id + '][alt_layout]', {
				setting: 'portfolio_category_page[' + id + '][alt_layout]',
				type: 'checkbox',
				section: 'hannover_portfolio_category_page_section[' + id + ']',
				label: 'Use alternative layout.'
			})
		);

		// Add span for page title and category to section title markup.
		var pageTitleNode = document.createElement('span');
		pageTitleNode.className = 'page-title';
		document.querySelector('#accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .accordion-section-title .screen-reader-text').parentNode.insertBefore(pageTitleNode, document.querySelector('#accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .accordion-section-title .screen-reader-text'));

		var pageCategoryNode = document.createElement('span');
		pageCategoryNode.className = 'page-category';
		document.querySelector('#accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .accordion-section-title .screen-reader-text').parentNode.insertBefore(pageCategoryNode, document.querySelector('#accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .accordion-section-title .screen-reader-text'));

		// Add page and category to section head and the page title to the section title.
		component.updateSectionTitle(id);

		component.updateCategoryInSectionHead(id);

		// Listen to changes of the page control value to update the page title in the section head and section title.
		api.control('portfolio_category_page[' + id + '][id]', function (control) {
			control.setting.bind(function (value) {
				// Check if the value was changed to the default state.
				if (0 !== parseInt(value) && Number.isInteger(parseInt(value))) {
					// Redirect to the new URL.
					api.previewer.previewUrl.set(api.settings.url.home + '?page_id=' + value);
				}
				component.updateSectionTitle(id);
			});
		});

		// Listen to changes of the category control value to update the category title in the section head.
		api.control('portfolio_category_page[' + id + '][category]', function (control) {
			control.setting.bind(function () {
				component.updateCategoryInSectionHead(id);
			});
		});

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

						// Add the page title to the toggle.
						component.updateSectionTitle(id);

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
	 * Toogls the sections for portfolio archive and category pages.
	 */
	component.togglePortfolioSections = function togglePortfolioSections() {
		var active = false;
		console.log(wp.customize.control('portfolio_page_active').setting.get());
		if (1 === Number.parseInt(wp.customize.control('portfolio_page_active').setting.get()) && Number.isInteger(wp.customize.control('portfolio_page_active').setting.get())) {
			// The portfolio feature is enabled, so we need to display the sections.
			active = true;
		}
		// Hide the other portfolio sections (archive and category pages).
		api.section('hannover_portfolio_archive_page_options').active(active);

		// Get all category page sections.
		var categoryPageSections = document.querySelectorAll('li[id*="accordion-section-hannover_portfolio_category_page_section"]');
		if (0 !== categoryPageSections.length) {
			// Loop them.
			for (var i = 0; i < categoryPageSections.length; i++) {
				var sectionElemId = categoryPageSections[i].id;

				var sectionName = sectionElemId.substr('accordion-section-'.length);

				// Check if this is a category page’s section that was removed during this customize session.
				var sectionIndex = sectionElemId.match(/accordion-section-hannover_portfolio_category_page_section\[(\d)\]/);

				if (null !== sectionIndex) {
					var id = sectionIndex[1];

					// Check if ID does not exist in component.removedPortfolioCategorySections
					if (false === component.removedPortfolioCategorySections.includes(id)) {
						api.section(sectionName).active(active);
					}
				}
			}
		}
	};

	component.updateSectionTitle = function updateSectionTitle(id) {
		var pageTitle = 'No page selected';

		// Build the title for the section.
		if ('0' !== api.control('portfolio_category_page[' + id + '][id]').setting.get() && '' !== api.control('portfolio_category_page[' + id + '][id]').setting.get()) {
			// Get the page title.
			var pageSelectElem = document.querySelector('#customize-control-portfolio_category_page-' + id + '-id select');
			if (-1 !== pageSelectElem.selectedIndex) {
				pageTitle = '»' + pageSelectElem.options[pageSelectElem.selectedIndex].text.trim() + '«';
			}
		}

		// Add the page title to the section.
		document.querySelector('#accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .accordion-section-title .page-title').textContent = pageTitle;
		document.querySelector('#sub-accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .customize-section-title h3').lastChild.textContent = pageTitle;
	};

	component.updateCategoryInSectionHead = function updateCategoryInSectionHead(id) {
		var categoryTitle = '(No category selected)';

		// Build the category string.
		if ('0' !== api.control('portfolio_category_page[' + id + '][category]').setting.get() && '' !== api.control('portfolio_category_page[' + id + '][category]').setting.get()) {
			// Get the category title.
			var categorySelectElem = document.querySelector('#customize-control-portfolio_category_page-' + id + '-category select');
			if (-1 !== categorySelectElem.selectedIndex) {
				categoryTitle = '(Set to category: ' + categorySelectElem.options[categorySelectElem.selectedIndex].text.trim() + ')';
			}
		}

		document.querySelector('#accordion-section-hannover_portfolio_category_page_section\\[' + id + '\\] .accordion-section-title .page-category').textContent = categoryTitle;
	};

	/**
	 * Listen to changes of the portfolio page select and change the preview URL.
	 */
	component.bindPortfolioPageSelect = function bindPortfolioPageSelect() {
		api.control('portfolio_page', function (control) {
			control.setting.bind(function (value) {
				// Check if the value was changed to the default state.
				if (0 !== parseInt(value) && Number.isInteger(parseInt(value))) {
					// Redirect to the new URL.
					api.previewer.previewUrl.set(api.settings.url.home + '?page_id=' + value);
				}
			});
		});
	};

	/**
	 * Listen to changes of the portfolio archive page select and change the preview URL.
	 */
	component.bindPortfolioArchivePageSelect = function bindPortfolioArchivePageSelect() {
		api.control('portfolio_archive', function (control) {
			control.setting.bind(function (value) {
				// Check if the value was changed to the default state.
				if (0 !== parseInt(value) && Number.isInteger(parseInt(value))) {
					// Redirect to the new URL.
					api.previewer.previewUrl.set(api.settings.url.home + '?page_id=' + value);
				}
			});
		});
	};

	/**
	 * Creates the needed customize things for the slider options.
	 */
	component.createSliderOptions = function createSliderOptions() {
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
			if (undefined === component.themeMods.portfolio_page['active'] || 1 !== component.themeMods.portfolio_page['active']) {
				api.section('hannover_portfolio_page_section').headContainer.find('.accordion-section-title').replaceWith(
					wp.template('hannover-no-portfolio-page-notice')
				);

				// Remove the border top from the section.
				api.section('hannover_portfolio_page_section').headContainer[0].classList.add('no-border-top');
			}
		}
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
		if (!firstChild.classList.contains('hannover-category-pages-sections-description')) {
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
		while (elem) {
			// Check if this is a category page section toggle.
			if (0 === elem.id.search(/accordion-section-hannover_portfolio_category_page_section\[\d\]/g)) {
				// Get ID of section.
				var sectionIndex = elem.id.match(/accordion-section-hannover_portfolio_category_page_section\[(\d)\]/);

				if (null !== sectionIndex) {
					var id = sectionIndex[1];

					// Check if ID does not exist in component.removedPortfolioCategorySections
					if (false === component.removedPortfolioCategorySections.includes(id)) {
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
		value: function (searchElement, fromIndex) {

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
