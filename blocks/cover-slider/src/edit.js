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
} from '@wordpress/block-editor'
import { ToolbarGroup, ToolbarItem, Button, Icon } from '@wordpress/components'
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

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, clientId }) {
	const { align } = attributes

	useEffect(() => {
		updateAlignment()
	}, [align])

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

	return (
		<>
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
			</div>
		</>
	)
}
