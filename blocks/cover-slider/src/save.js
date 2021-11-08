/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n'

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {
	useBlockProps,
	InnerBlocks,
	store as blockEditorStore,
	getColorClassName,
} from '@wordpress/block-editor'

function getColorClassAndStyle(color, customColor, options = {}) {
	const settings = {
		backgroundColor: false,
		addStyle: false,
		...options,
	}

	let className
	const style = {}

	if (color) {
		className = getColorClassName(
			settings.backgroundColor ? 'background-color' : 'color',
			color
		)
	}
	if (customColor || settings.addStyle) {
		if (settings.backgroundColor) {
			style.backgroundColor = customColor
		} else {
			style.color = customColor
		}
	}

	return { className, style }
}

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save({ attributes }) {
	const {
		useArrows,
		useDots,
		enableAutoplay,
		autoplaySpeed,
		autoplayPauseOnHover,
		dotSize,
		arrowSize,
		dotColor,
		customDotColor,
		arrowColor,
		customArrowColor,
		dotActiveColor,
		customDotActiveColor,
		_customDotActiveColor,
	} = attributes

	const settings = {
		useArrows,
		useDots,
		enableAutoplay,
		autoplaySpeed,
		autoplayPauseOnHover,
	}

	const { className: dotClass, style: dotStyle } = getColorClassAndStyle(
		dotColor,
		customDotColor,
		{
			backgroundColor: true,
		}
	)
	const { className: arrowClass, style: arrowStyle } = getColorClassAndStyle(
		arrowColor,
		customArrowColor
	)

	const { className: dotActiveClass, style: dotActiveStyle } =
		getColorClassAndStyle(dotActiveColor, customDotActiveColor, {
			backgroundColor: true,
			addStyle: true,
		})

	const cssVariables = {
		'--dotSize': `${dotSize}px`,
		'--arrowSize': `${arrowSize}px`,
		'--dotActiveColor': _customDotActiveColor,
	}

	return (
		<div
			{...useBlockProps.save()}
			data-settings={JSON.stringify(settings)}
			style={cssVariables}
		>
			<div className="slider-container">
				<div className="slider-track">
					<InnerBlocks.Content />
				</div>
				{useArrows && (
					<div className="slider-arrows">
						<div className="slider-arrow-prev">
							<button className={arrowClass} style={arrowStyle}>
								<svg
									width="24"
									height="24"
									viewBox="0 0 24 24"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M16.2426 6.34317L14.8284 4.92896L7.75739 12L14.8285 19.0711L16.2427 17.6569L10.5858 12L16.2426 6.34317Z"
										fill="currentColor"
									/>
								</svg>
							</button>
						</div>
						<div className="slider-arrow-next">
							<button className={arrowClass} style={arrowStyle}>
								<svg
									width="24"
									height="24"
									viewBox="0 0 24 24"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
										fill="currentColor"
									/>
								</svg>
							</button>
						</div>
					</div>
				)}
			</div>
			{useDots && (
				<div className="slider-dots">
					<button className={`slider-dot ${dotClass}`} />
				</div>
			)}
		</div>
	)
}
