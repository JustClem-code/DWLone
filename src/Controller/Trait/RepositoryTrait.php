<?php

namespace App\Controller\Trait;

trait RepositoryTrait
{
  private function findOrNull(object $repository, ?int $id): ?object
  {
    if ($id === null) {
      return null;
    }
    return $repository->find($id);
  }

  /**
   * @param iterable $entities
   * @param callable $toArray   function(object $entity): array
   */
  public function transformEntities(iterable $entities, callable $toArray): array
  {
    $collection = [];

    foreach ($entities as $entity) {
      $collection[] = $toArray($entity);
    }

    return $collection;
  }
}
