# FlightSpy
[![Flight Spy](http://business.skyscanner.net/Content/images/logo/ssf-white-color.png)](http://www.skyscanner.net)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jeancsil/flight-spy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jeancsil/flight-spy/?branch=master)
[![Build Status](https://travis-ci.org/jeancsil/flight-spy.svg?branch=master)](https://travis-ci.org/jeancsil/flight-spy)
[![Latest Stable Version](https://img.shields.io/badge/packagist-flight--spy-blue.svg)](https://packagist.org/packages/jeancsil/flight-spy)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/jeancsil/flight-spy/master/LICENSE) [![Twitter](https://img.shields.io/twitter/url/https/github.com/jeancsil/flight-spy.svg?style=social)](https://twitter.com/intent/tweet?text=Watch the best fare for your next trip!&url=http://github.com%2Fjeancsil%2Fflight-spy)


##Looking for the cheapest flights and don't have enough time to track all the prices?

####Be notified whenever the budget you're looking for pops up by only declaring a **simple JSON file**!

```
[
  {
    "from": "FRA-sky",
    "to": "GRU-sky",
    "search-period-from": "2017-07-01",
    "search-period-to": "2017-07-31",
    "search-period-travel-days": 20,
    "max-price": "650",
    "currency": "EUR",
    "country": "DE",
    "locale": "de-DE",
    "adults": "1",
    "children": "1"
  }
]
```

## Prerequisites
Create your account at Skyscanner to get your API access. Don't worry, it's for free! [http://en.business.skyscanner.net/](http://en.business.skyscanner.net/en-gb/products/travel-apis/)

To receive notifications by Slack you'll have to create one account here: [https://slack.com/](https://slack.com/)

If you also want to receive e-mails create a free account at [https://postmarkapp.com/](https://postmarkapp.com/)

## Install with docker
Rename the `src/Resources/parameters.yml.dist` to `src/Resources/parameters.yml` and update the content with the data from the previous step.

Rename the `docker/volume/watch.json.dist` to `docker/volume/watch.json` and fill with your next trips!

```
$ git clone https://github.com/jeancsil/flight-spy.git
$ docker-compose up -d
```

## Visualize your watched prices with Kibana

![alt text](https://raw.githubusercontent.com/jeancsil/flight-spy/master/src/Resources/KibanaDashboard.png "Kibana Dashboard")

Import the `src/Resources/kibana/Dashboard.json` file into your kibana dashboards panel: [http://localhost:5601/app/kibana#/management/kibana/objects](http://localhost:5601/app/kibana#/management/kibana/objects)

## Documentation

FlightSpy will look for the best deals for you every 15 minutes and will let you know by e-mail/Slack if there is a good price for you next trip!

## Support

For general support and questions, find me on Twitter as [@jeancsil](http://twitter.com./jeancsil).

Bugs and suggestions: [open a ticket](https://github.com/jeancsil/flight-spy/issues).

## License

This package is available under the [MIT license](LICENSE).