# Flight Spy
[![Flight Spy](http://business.skyscanner.net/Content/images/logo/ssf-white-color.png)](http://www.skyscanner.net)

[![Latest Stable Version](https://img.shields.io/badge/packagist-flight--spy-blue.svg)](https://packagist.org/packages/jeancsil/flight-spy)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/jeancsil/flight-spy/master/LICENSE) [![Twitter](https://img.shields.io/twitter/url/https/github.com/jeancsil/flight-spy.svg?style=social)](https://twitter.com/intent/tweet?text=Watch the best fare for your next trip!&url=http://github.com%2Fjeancsil%2Fflight-spy)


Provides console commands to keep watching flight deals for you!


## Install
`composer require jeancsil/flight-spy`

OR

Add in your composer.json:

```json
"require": {
    "jeancsil/flight-spy": "1.*"
}
```

Rename the src/Resources/parameters.yml.dist to parameters.yml. 
Add these configurations in your parameters.yml file:

```yaml
jeancsil.flightspy.api.host: 'http://partners.api.skyscanner.net'
jeancsil.flightspy.api.key: YOUR_API_KEY (OPTIONAL)
jeancsil.flightspy.http.client.config:
    base_uri: '%jeancsil.flightspy.api.host%'
    timeout: 30
    headers:
        Content-Type: application/x-www-form-urlencoded
        Accept: application/json
        User-Agent: 'Mozilla/5.0 (Windows NT 10.0; WOW64) (OPTIONAL)'
```

## Documentation

Simply run `./bin/flight-watcher flightspy:skyscanner:live_prices --help` to learn how it works.

## Examples

Watch as many trips as you wish (see src/Resources/watch.json):

`./bin/flight-watcher flightspy:skyscanner:live_prices --file=yourWatchFile.json`

**OR**

Watch a single trip:

`./bin/flight-watcher flightspy:skyscanner:live_prices --from=SAO-sky --to=FRA-sky --departure=2016-10-10 --arrival=2016-11-20 --country=BR --currency=BRL --locale=pt-BR --adults=2 --max-price=8000`.

You might want to put it in your crontab as well. (*and go grab a beer!*)

## Support

For general support and questions, find me on Twitter as [@jeancsil](http://twitter.com./jeancsil).

Bugs and suggestions: [open a ticket](https://github.com/jeancsil/flight-spy/issues).

## License

This package is available under the [MIT license](LICENSE).
