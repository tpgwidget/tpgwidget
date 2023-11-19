![TPGwidget logo](https://www.nicolapps.ch/tpgwidget/name.png)

[![Download on the App Store](https://user-images.githubusercontent.com/551004/29770691-a2082ff4-8bc6-11e7-89a6-964cd405ea8e.png)](https://tinyurl.com/TPGwidget-iOS)
[![Download on Google Play](https://user-images.githubusercontent.com/551004/29770692-a20975c6-8bc6-11e7-8ab0-1cde275496e0.png)](https://tinyurl.com/TPGwidget-Android)

# TPGwidget
TPGwidget is an iOS/Android app for public transport in Geneva. It uses the free Open Data API provided by the transit operator ([transports publics genevois](https://www.tpg.ch/)).

TPGwidget includes many features such as :
- stops shortcuts on the home screen
- real-time lines schedules
- routes planning
- vehicles informations
- and much more!

The projet is made with [Framework7](http://framework7.io) in the front end, and PHP in the back end. The app users download on the [App Store](https://github.com/tpgwidget/ios) or on [Google Play](https://github.com/tpgwidget/android) is powered by Apache Cordova.

![TPGwidget screenshots](https://www.nicolapps.ch/tpgwidget/screenshots.png)

## Getting Started

Prerequisites : PHP 7+, MySQL, Node.js (dev only)

### Install

To run an instance of TPGwidget, you have to :
1. Create a copy of the `.env.example` file named `.env` and fill in your TPG Open Data API key and your database credentials.
2. Create on your MySQL Server the required tables (use the `dump.sql` file)

On the production server,
- https://tpg.nicolapps.ch is linked to the `tpg` folder. It contains the iOS web app.
- https://tpga.nicolapps.ch is linked to the `tpga` folder. It contains the Android web app.
- https://tpgdata.nicolapps.ch is linked to the `tpgdata` folder. It contains generic data (maps, vehicles images)

### Develop
Front-end files are compiled with [Gulp](https://gulpjs.com). To install it, use `npm install gulp-cli -g` from the command line. You can then compile the assets using `gulp ios-css`, `gulp android-css`, `gulp ios-js` and `gulp android-js`.

## Contributing
If you have a question or an idea, you can create an issue. Pull requests are welcome! If you want to contribute, don’t hesitate to look into the unassigned issues.

## Authors
- **Nicolas Ettlin** ([@Nicolapps](https://github.com/Nicolapps))
- **Adam Mathieson** ([@AMathieson](https://github.com/amathieson)) - Help in transition to alternative API & Updates to vehicles

## License
TPGwidget code is released under the MIT license, see the [LICENSE](https://github.com/tpgwidget/tpgwidget/blob/master/LICENSE) file for details. This repository includes maps (© transports publics genevois), and the vehicle icons are [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/deed.en) licensed.
