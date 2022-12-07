<p align="center">
  <a href="https://github.com/JungesMuensterschwarzach/App">
    <img src="files/assets/imgs/logo.png" alt="Logo">
  </a>

  <h3 align="center">Advent of Code - Private Leaderboard Dashboard</h3>

  <p align="center">
    This repository hosts a simple dockerized webpage based on PHP that crawls Advent of Code's API for your configured private leaderboard and displays the leaderboard in a different theme than the original website.
    <br />
    <br />
    <a href="https://luckev.info/aoc-leaderboard-rzuw/">Example Deployment</a>
    ·
    <a href="https://github.com/Dherlou/aoc-leaderboard/issues">Report Bug</a>
    ·
    <a href="https://github.com/Dherlou/aoc-leaderboard/issues">Request Feature</a>
  </p>
</p>


## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

["All you need is LOVE!"](https://youtu.be/_7xMfIp-irg) - and [Docker](https://www.docker.com/) as well as [Git](https://git-scm.com/)!

### Installation

Once docker is installed, you must follow these steps:

1. Clone the repo
   ```sh
   git clone https://github.com/Dherlou/aoc-leaderboard.git
   ```
2. Set up your environment (create a `.env` file):
    * <strong>AOC_SESSION_COOKIE</strong>: your session cookie of Advent of Code (required for crawling your private leaderboard, can be grabbed from your browser's cookie storage after logging in to the official Advent of Code website, typically valid for around 1 month)
    * <strong>AOC_LEADERBOARD_ID</strong>: the ID of your private leaderboard (required for crawling your private leaderboard)
    * <strong>AOC_LEADERBOARD_TITLE</strong>: a custom title for your dashboard (optional, default: 'Advent of Code Private Leaderboard')
    * <strong>AOC_LEADERBOARD_FAVICON</strong>: a custom favicon URL(-path) for your dashboard (optional, default: IT-Centre of University of Würzburg favicon)
    * <strong>AOC_LEADERBOARD_LOGO</strong>: a custom logo URL(-path) for your dashboard (optional, default: IT-Centre of University of Würzburg logo)
    * <strong>AOC_YEAR</strong>: the year of the event (optional, default: current year)
    * <strong>AOC_NO_GLOBAL_SCORE</strong>: enable to hide the global score column on the dashboard (optional, default: false)
    * <strong>DEV_MODE</strong>: enable to display the Advent of Code API's response at the bottom of the page (optional, default: false)

3. Build the docker image and start the container:
    ```sh
    docker-compose (-f docker-compose.yml -f docker-compose.dev.yml) up -d
    ```
    Including the `docker-compose.dev.yml` exposes the webserver on host port 80 and mounts the `files` directory into the container for an easy local development.
    If you want to use it in production, you can forward requests from an ssl-offloading proxy-server (e.g. nginx) to this container or make changes to the `docker-compose.yml` to fit your environment.


## To-Dos

* i18n
* wait for bootstrap to fix layered icons


## License

Distributed under the GNU Affero General Public License. See [LICENSE](./LICENSE) for more information.


## Contact

Lucas Kinne - lucas@luckev.info

Project Link: [https://github.com/Dherlou/aoc-leaderboard](https://github.com/Dherlou/aoc-leaderboard)
