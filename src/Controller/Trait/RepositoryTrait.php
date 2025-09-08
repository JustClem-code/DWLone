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
}
