<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;

use App\Repository\UserRepository;

class UserService
{
  // Avoid calling getUser() in the constructor: auth may not
  // be complete yet. Instead, store the entire Security object.
  public function __construct(
    private Security $security,
    private UserRepository $repository
  ) {}

  public function getCurrentUser(): array
  {
    // returns User object or null if not authenticated
    $user = $this->repository->toArray($this->security->getUser());

    return $user;
  }
}
