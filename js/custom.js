async function getWeather() {
    let response = await fetch('https://api.openweathermap.org/data/2.5/onecall?lat=35.58158224338281&lon=-97.57585136086652&exclude=minutely,hourly,daily,alerts&units=imperial&appid=a8d2c96aaeef9f098ebcb45a54e15e09');
    let data = await response.json();
    currentTemp = new String(Math.round(data.current.temp));
    return String('It is currently ' + currentTemp + '\xB0 F');
}

function search(searchText, site) {
    if (site === 'gsearch') {
        window.open('https://www.google.com/search?q=' + searchText + '&newwindow=1');
    } else if (site === 'ssearch') {
        window.open('https://stackoverflow.com/search?q=' + searchText)
    } else if (site === 'rsearch') {
        window.open('https://www.reddit.com/search/?q=' + searchText)
    } else {
        window.open('https://www.google.com/search?q=' + searchText + '&newwindow=1');
    }
}