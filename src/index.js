/**
 * andW Simple FAQPage Schema — ブロック登録エントリ
 *
 * @package AndwSimpleFaqpageSchema
 */

import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';
import save from './save';
import './editor.css';

registerBlockType( metadata.name, {
	edit: Edit,
	save,
} );
