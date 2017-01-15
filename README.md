# FlightSpy
[![Flight Spy](http://business.skyscanner.net/Content/images/logo/ssf-white-color.png)](http://www.skyscanner.net)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jeancsil/flight-spy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jeancsil/flight-spy/?branch=master)
[![Build Status](https://travis-ci.org/jeancsil/flight-spy.svg?branch=master)](https://travis-ci.org/jeancsil/flight-spy)
[![Latest Stable Version](https://img.shields.io/badge/packagist-flight--spy-blue.svg)](https://packagist.org/packages/jeancsil/flight-spy)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/jeancsil/flight-spy/master/LICENSE) [![Twitter](https://img.shields.io/twitter/url/https/github.com/jeancsil/flight-spy.svg?style=social)](https://twitter.com/intent/tweet?text=Watch the best fare for your next trip!&url=http://github.com%2Fjeancsil%2Fflight-spy)


Watch as much flights you want and get notified for YOUR budget.
Currently by E-mail and/or Slack.

###Notification channels:

[![Slack](https://raw.githubusercontent.com/jeancsil/flight-spy/master/src/Resources/slack.png)](https://slack.com/)

[![Postmark](https://raw.githubusercontent.com/jeancsil/flight-spy/master/src/Resources/postmark.png)](https://postmarkapp.com/)

## Install with docker (recommended)
Rename the `src/Resources/parameters.yml.dist` to `src/Resources/parameters.yml` and update the content with your data.

Copy the `src/Resources/watch.json` to `/watch.json` and add how many flights you wish to track.
In this file you will put all the flights information like dates, budged, number of adults, kids etc...

`$ git clone https://github.com/jeancsil/flight-spy.git`

`$ docker-compose up -d`
`$ docker-compose stop //to stop the container`

## Documentation

FlightSpy will look for the best deals for you from 5 to 5 minutes and will let you know by e-mail AND/OR Slack if there is a good fare for you next trip!

## Support

For general support and questions, find me on Twitter as [@jeancsil](http://twitter.com./jeancsil).

Bugs and suggestions: [open a ticket](https://github.com/jeancsil/flight-spy/issues).

## License

This package is available under the [MIT license](LICENSE).
