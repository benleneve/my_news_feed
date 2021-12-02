#  My Feed News (Symfony 5 - API Platform - React)

- **Symfony** 5.4
- **Requires PHP:** 7.4
- **License:** MIT
- **License URI:** https://opensource.org/licenses/MIT

## Overview

This project allows you to display news feed according to a specific theme.

- Possibility to import articles with a Symfony command
- Provision of an API to retrieve the list of articles
- Display of the list of articles (10 by default)
- Possibility to temporarily deactivate an offer
- Possibility to display more articles with the help of a button
- Viewing more information about the article using a modal

## Plugin installation via GitHub

Follow the instruction below if you want to install my_news_feed project using Git.

1.) Clone the git repository using:

    git clone git@github.com:benleneve/my_news_feed.git my_news_feed

2.) Go to the my_news_feed folder

    cd my_news_feed

3.) Install Symfony dependencies via Composer

    composer install

4.) Create the Docker containers required for the database

    docker-compose up -d

5.) Install JS dependencies via Yarn

    yarn install

6.) Build JS files via Yarn

    yarn run build

7.) Start the Symfony server

    symfony server:start -d

8.) Create the Database and tables needed for the project

    symfony console doctrine:database:create  
    symfony console doctrine:schema:update --force

The project is available on http://127.0.0.1:8000/ and phpMyAdmin on http://127.0.0.1:8080/

## Use the Symfony news import command

The project contains a Symfony command allowing to import a precise number of articles according to a theme

    symfony console app:my_news_feed:import [arg::searchType] [arg::numberMaxOfNews] [opt::dry-run]

    Arguments
    [searchType] REQUIRED -> Indicates the type of article to search (example: apple)
    [numberMaxOfNews] OPTIONAL -> Indicates the number of articles to import (maximum 20 items)

    Options
    [dry-run] -> Allows you to test the command without inserting anything in the database

## Using the news API

The news API is managed by API platform. It has 2 endpoints only in GET :

- GET /api/news Retrieves the collection of News resources
- GET /api/news/{id} Retrieves News resources

The display of the collection is paginated by default at 10 items.
Articles are sorted by default by date of publication

Return example GET /api/news

    [
        {
        "id": 62,
        "title": "Omicron is Here. Will We Use Our New Covid Drugs Wisely?",
        "author": "XXXXX XXXXX",
        "description": "The world must not repeat history by making Covid-19 drugs inaccessible.",
        "content": "If companies refuse to cooperate, governments many of which fund the underlying research for new drugs and vaccines can step in to require drug companies to share knowledge, make their products affor… [+1907 chars]",
        "url": "https:\/\/www.nytimes.com\/2021\/12\/01\/opinion\/omicron-covid-drugs-pfizer-antiviral.html",
        "imageUrl": "https:\/\/static01.nyt.com\/images\/2021\/12\/01\/opinion\/01cohen_2\/01cohen_2-facebookJumbo.jpg",
        "publishedAt": "2021-12-01T20:05:36+01:00"
        },
        {
        "id": 32,
        "title": "Microsoft Teams Essentials is a new standalone version for small businesses",
        "author": "XXXXX XXXXXXX",
        "description": "Microsoft’s new standalone version of Teams competes even more with Zoom. Microsoft Teams Essentials drops the Slack-like channels in favor of simple chat and video calls.",
        "content": "This standalone version of Teams is even more of a Zoom competitor\r\nIf you buy something from a Verge link, Vox Media may earn a commission. See our ethics statement.\r\nMicrosoft is creating its first… [+3988 chars]",
        "url": "https:\/\/www.theverge.com\/2021\/12\/1\/22811605\/microsoft-teams-essentials-price-features-availability",
        "imageUrl": "https:\/\/cdn.vox-cdn.com\/thumbor\/eEoF5eIsMcMyu5SnpFUnsh5c6Jo=\/0x75:3840x2085\/fit-in\/1200x630\/cdn.vox-cdn.com\/uploads\/chorus_asset\/file\/23055111\/Create_group_from_templates__2_.png",
        "publishedAt": "2021-12-01T16:00:00+01:00"
        }
    ]

Return example GET /api/news/{id}

    {
        "id": 62,
        "title": "Omicron is Here. Will We Use Our New Covid Drugs Wisely?",
        "author": "XXXXXX XXXXX",
        "description": "The world must not repeat history by making Covid-19 drugs inaccessible.",
        "content": "If companies refuse to cooperate, governments many of which fund the underlying research for new drugs and vaccines can step in to require drug companies to share knowledge, make their products affor… [+1907 chars]",
        "url": "https:\/\/www.nytimes.com\/2021\/12\/01\/opinion\/omicron-covid-drugs-pfizer-antiviral.html",
        "imageUrl": "https:\/\/static01.nyt.com\/images\/2021\/12\/01\/opinion\/01cohen_2\/01cohen_2-facebookJumbo.jpg",
        "publishedAt": "2021-12-01T20:05:36+01:00"
    }


## Possibility of project improvements

- Setting up an infinite scroll for the display of articles
- Setting up a cron to import the articles 
- Setting up a cron to delete articles older than 30 days