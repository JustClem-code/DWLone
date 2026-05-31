# DWLone

A web-based Warehouse Management System (WMS) designed for a package sorting center.

This application helps manage incoming packages delivered on pallets by truck, sort them by postal code, and assign them to delivery drivers using vans for redistribution.

## Features

- Receive packages arriving by pallet.
- Register and track incoming shipments.
- Sort packages according to postal code.
- Prepare packages for redistribution.
- Assign packages to drivers and vans.
- Monitor warehouse operations in a simple and efficient workflow.

## Project Goals

The goal of this project is to provide a practical digital tool for a package sorting warehouse.
It is designed to improve skills in fullstack developpement.

## Tech Stack

- **Backend:** Symfony
- **Frontend:** Vue.js via Symfony UX Vue
- **Database:** PostgreSQL
- **CSS Framework:** Tailwind CSS
- **Project Base:** Symfony Docker / Dunglas Symfony template

Symfony UX Vue allows Vue components to be rendered from Twig and integrated directly into a Symfony application. The Symfony Docker template provides a solid Docker-based setup for running and deploying Symfony projects consistently.

## Architecture

The application is structured as follows:

- Symfony handles business logic, routing, security, and API or server-side operations.
- Vue.js is used for interactive user interfaces.
- PostgreSQL stores warehouse, package, and delivery data.
- Tailwind CSS provides a clean and responsive design.

## Main Use Case

1. Packages arrive at the sorting center on pallets.
2. Warehouse staff register the delivery.
3. Packages are sorted by postal code.
4. Packages are grouped for delivery routes.
5. Drivers receive packages in vans for final redistribution.

## Development Notes

- Vue components are located in the Symfony frontend assets.
- Symfony manages backend logic and persistence.
- PostgreSQL is used as the main relational database.
- Tailwind CSS is used for layout and styling.

# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## License

This project is licensed under the MIT License.

Symfony Docker is available under the MIT License.

## Credits

* [Original readme of symfony docker](docs/README-symfony-docker.md)

Symfony docker is created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
