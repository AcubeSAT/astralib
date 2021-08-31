<?php

namespace App\Repository;

use App\Entity\Category;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{
    /**
     * Sets the colour of all children of this category to the colour of this category.
     * This function also _persists_ the objects in memory
     */
    public function propagateColours(Category $category): void
    {
        // $this->children already does the tree traversal for us
        /** @var Category $child */
        foreach ($this->children($category) as $child) {
            $child->setColour($category->getColour());
        }
    }
}
