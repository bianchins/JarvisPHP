JarvisPHP Google Calendar Client
--------------------------------

To create an api key, and put it in the api-key.json config file, please follow the instructions placed in:
https://developers.google.com/google-apps/calendar/quickstart/php

    Use this wizard (https://console.developers.google.com/start/api?id=calendar) to create or select a project in the Google Developers Console and automatically enable the API. Click the Go to credentials button to continue.
    At the top of the page, select the OAuth consent screen tab. Select an Email address, enter a Product name if not already set, and click the Save button.
    Back on the Credentials tab, click the Add credentials button and select OAuth 2.0 client ID.
    Select the application type Other and click the Create button.
    Click OK to dismiss the resulting dialog.
    Click the (Download JSON) button to the right of the client ID. Move this file to your working directory and rename it api-key.json.




After, to obtain a valid session token please execute 
php cli_createKey.php
It will create a secret-client-key.json file. 