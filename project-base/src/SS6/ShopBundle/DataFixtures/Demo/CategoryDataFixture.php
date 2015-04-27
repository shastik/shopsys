<?php

namespace SS6\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SS6\ShopBundle\Component\DataFixture\AbstractReferenceFixture;
use SS6\ShopBundle\DataFixtures\Base\CategoryRootDataFixture;
use SS6\ShopBundle\Model\Category\CategoryData;

class CategoryDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface {

	const ELECTRONICS = 'category_electronics';
	const TV = 'category_tv';
	const PHOTO = 'category_photo';
	const PRINTERS = 'category_printers';
	const PC = 'category_pc';
	const PHONES = 'category_phones';
	const COFFEE = 'category_coffee';
	const BOOKS = 'category_books';
	const TOYS = 'category_toys';

	/**
	 * @param \Doctrine\Common\Persistence\ObjectManager $manager
	 */
	public function load(ObjectManager $manager) {
		$categoryVisibilityRepository = $this->get('ss6.shop.category.category_visibility_repository');
		/* @var $categoryVisibilityRepository \SS6\ShopBundle\Model\Category\CategoryVisibilityRepository */

		$categoryData = new CategoryData();

		$categoryData->name = ['cs' => 'Elektro', 'en' => 'Electronics'];
		$categoryData->parent = $this->getReference(CategoryRootDataFixture::ROOT);
		$electronicsCategory = $this->createCategory(self::ELECTRONICS, $categoryData);

		$categoryData->name = ['cs' => 'Televize, audio', 'en' => 'TV, audio'];
		$categoryData->parent = $electronicsCategory;
		$this->createCategory(self::TV, $categoryData);

		$categoryData->name = ['cs' => 'Fotoaparáty', 'en' => 'Cameras & Photo'];
		$this->createCategory(self::PHOTO, $categoryData);

		$categoryData->name = ['cs' => 'Tiskárny', 'en' => null];
		$this->createCategory(self::PRINTERS, $categoryData);

		$categoryData->name = ['cs' => 'Počítače & příslušenství', 'en' => null];
		$this->createCategory(self::PC, $categoryData);

		$categoryData->name = ['cs' => 'Mobilní telefony', 'en' => null];
		$this->createCategory(self::PHONES, $categoryData);

		$categoryData->name = ['cs' => 'Kávovary', 'en' => null];
		$this->createCategory(self::COFFEE, $categoryData);

		$categoryData->name = ['cs' => 'Knihy', 'en' => 'Books'];
		$categoryData->parent = $this->getReference(CategoryRootDataFixture::ROOT);
		$this->createCategory(self::BOOKS, $categoryData);

		$categoryData->name = ['cs' => 'Hračky a další', 'en' => null];
		$this->createCategory(self::TOYS, $categoryData);

		$manager->flush();

		$categoryVisibilityRepository->refreshCategoriesVisibility();
	}

	/**
	 * @param string $referenceName
	 * @param \SS6\ShopBundle\Model\Category\CategoryData $categoryData
	 * @return \SS6\ShopBundle\Model\Category\Category
	 * @throws \Exception
	 * @throws \SS6\ShopBundle\Model\Category\Exception
	 * @internal param ObjectManager $manager
	 */
	private function createCategory($referenceName, CategoryData $categoryData) {
		$categoryFacade = $this->get('ss6.shop.category.category_facade');
		/* @var $categoryFacade \SS6\ShopBundle\Model\Category\CategoryFacade */

		$category = $categoryFacade->create($categoryData);
		$this->addReference($referenceName, $category);

		return $category;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies() {
		return [
			CategoryRootDataFixture::class,
		];
	}

}
