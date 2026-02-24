/**
 * エディタ側UIコンポーネント
 *
 * @package AndwSimpleFaqpageSchema
 */

import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	RichText,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import {
	PanelBody,
	SelectControl,
	Button,
	Notice,
} from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const { headingLevel, faqs } = attributes;
	const blockProps = useBlockProps();

	// 見出しタグ名
	const HeadingTag = 'h' + headingLevel;

	// 全ブロックの再帰的な取得と競合検知
	const hasConflictingBlocks = useSelect((select) => {
		const blocks = select('core/block-editor').getBlocks();

		// ブロックを安全に再帰的にフラット化するヘルパー
		const flattenBlocks = (blks) => {
			if (!blks || !Array.isArray(blks)) {
				return [];
			}
			return blks.flatMap((b) => [
				b,
				...flattenBlocks(b.innerBlocks),
			]);
		};

		const allBlocks = flattenBlocks(blocks);

		return allBlocks.some(
			(block) =>
				block.name === 'yoast/faq-block' ||
				block.name === 'rank-math/faq-block'
		);
	}, []);

	/**
	 * FAQ項目の更新
	 */
	const updateFaq = (index, key, value) => {
		const newFaqs = [...faqs];
		newFaqs[index] = { ...newFaqs[index], [key]: value };
		setAttributes({ faqs: newFaqs });
	};

	/**
	 * FAQ項目の追加
	 */
	const addFaq = () => {
		setAttributes({
			faqs: [...faqs, { question: '', answer: '' }],
		});
	};

	/**
	 * FAQ項目の削除
	 */
	const removeFaq = (index) => {
		const newFaqs = faqs.filter((_, i) => i !== index);
		setAttributes({ faqs: newFaqs });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__(
						'FAQ設定',
						'andw-simple-faqpage-schema'
					)}
				>
					<SelectControl
						label={__(
							'見出しレベル',
							'andw-simple-faqpage-schema'
						)}
						value={String(headingLevel)}
						options={[
							{ label: 'H2', value: '2' },
							{ label: 'H3', value: '3' },
							{ label: 'H4', value: '4' },
						]}
						onChange={(value) =>
							setAttributes({
								headingLevel: Number(value),
							})
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasConflictingBlocks && (
					<Notice status="warning" isDismissible={false}>
						{__(
							'この投稿には他プラグインの FAQ ブロックも含まれています。JSON-LDスキーマが重複出力される可能性があります。',
							'andw-simple-faqpage-schema'
						)}
					</Notice>
				)}

				{faqs.map((faq, index) => (
					<div
						key={index}
						className="andw-faq-schema-item"
					>
						<div className="andw-faq-schema-item-header">
							<span className="andw-faq-schema-item-label">
								{__(
									'Q',
									'andw-simple-faqpage-schema'
								)}
								{index + 1}
							</span>
							<Button
								variant="tertiary"
								isDestructive
								size="small"
								onClick={() => removeFaq(index)}
								label={__(
									'削除',
									'andw-simple-faqpage-schema'
								)}
							>
								{__(
									'削除',
									'andw-simple-faqpage-schema'
								)}
							</Button>
						</div>

						{ /* 質問入力 */}
						<RichText
							tagName={HeadingTag}
							className="andw-faq-question"
							value={faq.question}
							onChange={(value) =>
								updateFaq(index, 'question', value)
							}
							placeholder={__(
								'質問を入力…',
								'andw-simple-faqpage-schema'
							)}
							allowedFormats={[]}
						/>

						{ /* 回答入力 */}
						<RichText
							tagName="div"
							className="andw-faq-answer"
							value={faq.answer}
							onChange={(value) =>
								updateFaq(index, 'answer', value)
							}
							placeholder={__(
								'回答を入力…',
								'andw-simple-faqpage-schema'
							)}
							allowedFormats={[
								'core/bold',
								'core/italic',
								'core/link',
								'core/list',
							]}
						/>
					</div>
				))}

				<Button variant="secondary" onClick={addFaq}>
					{__(
						'+ FAQ項目を追加',
						'andw-simple-faqpage-schema'
					)}
				</Button>
			</div>
		</>
	);
}
