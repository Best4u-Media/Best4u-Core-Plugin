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
	BlockControls,
	store as blockEditorStore,
	InspectorControls,
	PanelColorSettings,
	getColorClassName,
} from '@wordpress/block-editor'
import {
	ToolbarGroup,
	ToolbarItem,
	Button,
	Icon,
	PanelBody,
	ToggleControl,
	__experimentalNumberControl as NumberControl,
	RangeControl,
} from '@wordpress/components'
import { createBlock } from '@wordpress/blocks'
import { dispatch, select } from '@wordpress/data'
import { useEffect } from '@wordpress/element'

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss'

function getColorClassAndStyle(color, backgroundColor = false) {
	let className
	const style = {}

	if (!color) {
		return { className, style }
	}

	if (color.class) {
		className = color.class
	} else {
		if (backgroundColor) {
			style.backgroundColor = color.color
		} else {
			style.color = color.color
		}
	}

	return { className, style }
}

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({
	attributes,
	setAttributes,
	clientId,
	dotColor,
	setDotColor,
	arrowColor,
	setArrowColor,
	dotActiveColor,
	setDotActiveColor,
}) {
	const {
		align,
		useArrows,
		useDots,
		enableAutoplay,
		autoplaySpeed,
		autoplayPauseOnHover,
		dotSize,
		slideCount,
		arrowSize,
	} = attributes

	useEffect(() => {
		updateAlignment()
	}, [align])

	useEffect(() => {
		setAttributes({ _customDotActiveColor: dotActiveColor.color })
	}, [dotActiveColor])

	const updateAlignment = () => {
		const { updateBlockAttributes } = dispatch(blockEditorStore)
		const { getBlockOrder } = select(blockEditorStore)

		const innerBlockClientIds = getBlockOrder(clientId)
		innerBlockClientIds.forEach((innerBlockClientId) => {
			updateBlockAttributes(innerBlockClientId, { align })
		})
	}

	const addBlock = () => {
		const type = 'core/cover'

		const { replaceInnerBlocks } = dispatch(blockEditorStore)
		const { getBlocks } = select(blockEditorStore)

		let innerBlocks = getBlocks(clientId)
		innerBlocks = [...innerBlocks, createBlock(type, { align })]

		replaceInnerBlocks(clientId, innerBlocks)
	}

	const { className: dotClass, style: dotStyle } = getColorClassAndStyle(
		dotColor,
		true
	)

	const { className: dotActiveClass, style: dotActiveStyle } =
		getColorClassAndStyle(dotActiveColor, true)

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Slider settings')} initialOpen={true}>
					<ToggleControl
						label={__('Use arrows')}
						checked={useArrows}
						onChange={() =>
							setAttributes({ useArrows: !useArrows })
						}
					/>
					{useArrows && (
						<>
							<RangeControl
								label={__('Arrow size')}
								help={__('In pixels')}
								min={1}
								max={50}
								value={arrowSize}
								onChange={(value) =>
									setAttributes({ arrowSize: value })
								}
							/>
						</>
					)}
					<ToggleControl
						label={__('Use dots')}
						checked={useDots}
						onChange={() => setAttributes({ useDots: !useDots })}
					/>
					{useDots && (
						<>
							<RangeControl
								label={__('Dot size')}
								help={__('In pixels')}
								min={1}
								max={50}
								value={dotSize}
								onChange={(value) =>
									setAttributes({ dotSize: value })
								}
							/>
						</>
					)}
				</PanelBody>
				<PanelBody title={__('Autoplay settings')} initialOpen={false}>
					<ToggleControl
						label={__('Enable autoplay')}
						checked={enableAutoplay}
						onChange={() =>
							setAttributes({ enableAutoplay: !enableAutoplay })
						}
					/>
					{enableAutoplay && (
						<>
							<ToggleControl
								label={__('Pause on hover')}
								checked={autoplayPauseOnHover}
								onChange={() =>
									setAttributes({
										autoplayPauseOnHover:
											!autoplayPauseOnHover,
									})
								}
							/>
							<NumberControl
								label={__('Autoplay speed')}
								value={autoplaySpeed}
								onChange={(value) =>
									setAttributes({ autoplaySpeed: value })
								}
								help={__('In milliseconds')}
							/>
						</>
					)}
				</PanelBody>
				<PanelColorSettings
					title={__('Color settings')}
					colorSettings={[
						{
							value: dotColor.color,
							onChange: setDotColor,
							label: __('Dot color'),
						},
						{
							value: arrowColor.color,
							onChange: setArrowColor,
							label: __('Arrow color'),
						},
						{
							value: dotActiveColor.color,
							onChange: setDotActiveColor,
							label: __('Dot active color'),
						},
					]}
				/>
			</InspectorControls>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarItem
						as={Button}
						icon={() => <Icon icon="plus-alt2" />}
						onClick={addBlock}
					/>
				</ToolbarGroup>
			</BlockControls>
			<div {...useBlockProps()}>
				<InnerBlocks
					allowedBlocks={['core/cover']}
					template={[['core/cover', { align }]]}
					renderAppender={false}
				/>
				<div
					className="slider-dots-preview"
					style={{
						'--dotSize': dotSize,
					}}
				>
					<div
						className={`slider-dot ${dotActiveClass}`}
						style={dotActiveStyle}
					/>
					{Array.from({ length: 4 }).map((_, index) => (
						<div
							key={index}
							className={`slider-dot ${dotClass}`}
							style={dotStyle}
						/>
					))}
				</div>
			</div>
		</>
	)
}
