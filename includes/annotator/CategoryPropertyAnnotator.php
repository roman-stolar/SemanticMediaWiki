<?php

namespace SMW;

/**
 * Handling category annotation
 *
 * @ingroup SMW
 *
 * @licence GNU GPL v2+
 * @since 1.9
 *
 * @author mwjames
 */
class CategoryPropertyAnnotator extends PropertyAnnotatorDecorator {

	/** @var array */
	protected $categories;

	/**
	 * @since 1.9
	 *
	 * @param PropertyAnnotator $propertyAnnotator
	 * @param array $categories
	 */
	public function __construct( PropertyAnnotator $propertyAnnotator, array $categories ) {
		parent::__construct( $propertyAnnotator );
		$this->categories = $categories;
	}

	/**
	 * @see PropertyAnnotator::addAnnotation
	 *
	 * @since 1.9
	 */
	public function addAnnotation() {

		$settings  = $this->withContext()->getSettings();
		$namespace = $this->getSemanticData()->getSubject()->getNamespace();

		foreach ( $this->categories as $catname ) {

			if ( $settings->get( 'smwgCategoriesAsInstances' ) && ( $namespace !== NS_CATEGORY ) ) {
				$this->getSemanticData()->addPropertyObjectValue(
					new DIProperty( DIProperty::TYPE_CATEGORY ),
					new DIWikiPage( $catname, NS_CATEGORY, '' )
				);
			}

			if ( $settings->get( 'smwgUseCategoryHierarchy' ) && ( $namespace === NS_CATEGORY ) ) {
				$this->getSemanticData()->addPropertyObjectValue(
					new DIProperty( DIProperty::TYPE_SUBCATEGORY ),
					new DIWikiPage( $catname, NS_CATEGORY, '' )
				);
			}
		}

		$this->setState( 'updateOutput' );

		return $this;
	}

}
