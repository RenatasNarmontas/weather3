NFQ Academy Weather Bundle Homework
===================================

Example of cache file:


```
renatas@renatas-HP-8440p:/tmp$ cat weather_cache.txt 
1460627391 Nfq\WeatherBundle\Provider\DelegatingProvider 24 55 6
1460627370 Nfq\WeatherBundle\Provider\YahooProvider 24 55 5
1460626247 Nfq\WeatherBundle\Provider\YahooProvider 24 56 5
1460626240 Nfq\WeatherBundle\Provider\YahooProvider 24 54 6
1460626235 Nfq\WeatherBundle\Provider\YahooProvider 24 55 5
```

**WARNING**: no management for cache file yet. You need to clean it up manually. Code example below:

```
renatas@renatas-HP-8440p:~$ cd /tmp
renatas@renatas-HP-8440p:/tmp$ rm weather_cache.txt 
renatas@renatas-HP-8440p:/tmp$ 
```

Config file example:

```
nfq_weather:
    provider: Cached
    providers:
        yahoo:
        openweathermap:
            api_key: "%openweathermap_api_key%"
        delegating:
            providers:
                - OpenWeatherMap
                - Yahoo
        cached:
            provider: Delegating
            ttl: 500
```

Renatas Narmontas, 2016

