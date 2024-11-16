var el = wp.element.createElement;

wp.blocks.registerBlockType(

	'wowvendor-gutenberg/gutenberg-notice-block',

	{
		// Block name visible to user

		title: 'Notice',

		// Toolbar icon can be either using WP Dashicons or custom SVG

		icon: 'lightbulb',

		// Under which category the block would appear

		category: 'common',

		// The data this block will be storing 

		attributes: {

			// Notice box type for loading the appropriate CSS class. Default class is 'empty'.

			type: { type: 'string', default: 'empty' },

			// Notice box title in h4 tag

			title: { type: 'string' },

			// Notice box content in p tag

			content: { type: 'array', source: 'children', selector: 'p' }
		},

		// How our block renders in the editor in edit mode
		edit: function (props) {

			function updateTitle(event) {
				props.setAttributes({ title: event.target.value });
			}

			function updateContent(newdata) {
				props.setAttributes({ content: newdata });
			}

			function updateType(event) {
				props.setAttributes({ type: event.target.value });
			}

			function getTypeElement() {
				return gutenbergBlockNotice.map(function (option, index) {
					return el(
						"option", {
						key: index,

						value: option.id,

					}, option.name)
				})
			}

			return el(

				'div',

				{
					className: 'notice-box notice-' + props.attributes.type
				},

				el(
					'select',

					{
						onChange: updateType,

						value: props.attributes.type,
					},

					el("option", { value: "empty" }, "Empty"),

					getTypeElement()
				),

				el(
					'input',
					{
						type: 'text',

						placeholder: 'Enter title here...',

						value: props.attributes.title,

						onChange: updateTitle,

						style: { width: '100%' }
					}
				),

				el(
					wp.editor.RichText,
					{
						tagName: 'p',

						onChange: updateContent,

						value: props.attributes.content,

						placeholder: 'Enter description here...'
					}
				)
			);
		},

		// How our block renders on the frontend  

		save: function (props) {

			let contentElement = el(
				wp.editor.RichText.Content,

				{
					tagName: 'p',

					value: props.attributes.content
				}
			);

			return el(

				'div',

				{
					type: props.attributes.type,

					className: 'notice-box notice-' + props.attributes.type
				},

				el(
					'h4',

					null,

					props.attributes.title
				),

				contentElement
			);
		}
	}
);