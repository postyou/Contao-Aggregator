Contao-Aggregator
==========

Aggregator is an extension für Contao 3.3.x. With Aggregator, you can aggregate your hottest news right out of your Profiles at Facebook, Twitter and Instagram.

One annotation before we start
----------------------
This tool will give you access to the APIs of Facebook, Twitter & Instagram. Please consider to fetch only data you actually own and you have uploaded yourselves on these plattforms. You are responsible to comply at any time with the plattform policies of the respective companies.

Installation
----------------------
Just download this package from the build-in extension repository of Contao. Then you are ready to rock. Please note that Aggregator wil add a Cronjob to the Scheduler of Contao. It is highly recommended to run the Contao-Cronjob with a native cronjob. Otherwise it will slow down the performance of your website.

Receiving API-Credentials
----------------------
To use this extension, all you need are API-Credentials from Facebook, Twitter and Instagram. If you are only using one of these plattforms, then you only need credentials for that API.

Follow the follwing instructions to receive these credentials:

####Facebook API-Credentials
1. Go to [developers.facebook.com](https://developers.facebook.com "Opens developers.facebook.com in a new window") and sign up as a developer if you haven't already.
2. Create a new App for a website and skip the quickstart program.
3. Copy the App-Id and the App-Secret to your clipboard and paste these values into the "Settings"-Section of your Contao installation.
4. Now, you are ready to roll. Add your Facebook profile in the "Aggregator"-Section of Contao.

####Twitter API-Credentials
1. Go to [dev.twitter.com](https://dev.twitter.com "Opens dev.twitter.com in a new window") and sign up as a developer if you haven't already.
2. Visit [apps.twitter.com](https://apps.twitter.com "Opens apps.twitter.com in a new window") and create a new app.
3. Open your App configuration on Twitter and browse to the "API-Keys"-Section.
4. Copy the API key, API secret, Access token and Access token secret to your clipboard and paste these values into the "Settings"-Section of your Contao installation.
5. Now, it's time to fuel the engine. Add your Twitter profile or Hashtags in the "Aggregator"-Section of Contao.

####Instagram API-Credentials
1. Go to [instagram.com/developer](http://instagram.com/developer "Opens instagram.com/developer in a new window") and sign up as a developer if you haven't already.
2. Open the "Manage Clients"-section and register a new client. Enter your website address for the OAuth redirect_uri input field.
3. Copy the Client-Id and the Client-Secret to your clipboard and paste these values into the "Settings"-Section of your Contao installation.
4. Now, it's time to hit the stage. Add your Instagram profile or Hashtags in the "Aggregator"-Section of Contao.

Support
----------------------
This extension is developed as an open source extension. Therefore there is no official support. If you have any technical questions or problems, please create a ticket here at GitHub.

Legal-Notes
----------------------
With downloading this package, you accept the terms and conditions of the MIT Licence. Also, I do not own any of the used Trademarks in the context of this extension. This extension is developed independent from these organisations and do not represent official products by these.
