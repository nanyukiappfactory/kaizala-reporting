## Kaizala Reporting via 3rd Party App - About Project

---

Kaizala is a mobile application that allows work get done within the context of a conversation / group using actions. While it provides a whole lot of functionalities that allow businesses and enterprises to get work done, it also supports extensiblity allowing building of powerful integrations with existing business processes / workflows. This project allows registering of Webhooks to listen to content being posted on the group and use that information to update your database.

---

## A. Getting Started...

---

### Project Setup

**1. Clone this repository**

```sh
git clone https://github.com/nanyukiappfactory/kaizala-reporting.git

```

**2. Delete the .git folder**

**3. Rename configsample.php to config.php**

**4. Open config.php file located in the application/config directory**

- change base_url to live server url or localhost.

```sh
for example

$config['base_url'] = 'https://YOUR_LIVE_SERVER_URL/'
or
$config['base_url'] = 'https://YOUR_LOCALHOST_URL.'

```

- Edit config.php file by providing your applicationId, applicationSecret and refreshToken:

```sh

$config['application_id'] = '';
$config['application_secret'] = '';
$config['refresh_token'] = '';

```

For more on how to acquire applicationId, applicationSecret and refreshToken, Click [here](https://kaizala007.blog/2017/12/30/getting-started-with-kaizala-apis/)

**5. Rename databasesample.php to database.php**

**6. Open database.php file in the location: application/config directory and setup the connection to your database by providing the following:**

```sh

'hostname' => '{your_db_hostname}',
'username' => '{your_db_username}',
'password' => '{your_db_password}',
'database' => '{your_db_name}',
'dbdriver' => '{db_driver :: refer to comments above}',

```

**7. To run Migrations**

Now that you have configured your database.php and config.php files, open a new tab and run:

```sh
Live: https://YOUR_LIVE_SERVER_URL/migrate
or
Localhost: http://YOUR_LOCALHOST_URL/migrate

```

---

## B. Enabling Login with Office 365 & Azure Active Directory

---

#### Step 1: Login to your Office 365 account

Navigate to the Microsoft app registration portal at [https://apps.dev.microsoft.com/](https://apps.dev.microsoft.com/)
Sign in with either a personal or work or school Microsoft account. If you don't have either, sign up for a new personal account

#### Step 2: Add a new application

![image](https://user-images.githubusercontent.com/12447806/54804589-18168080-4c85-11e9-864c-ebe4ec1fccc7.png)

Navigate to [Application Registration Portal](https://apps.dev.microsoft.com/), click **Go to app list > Add an app**.

![image](https://user-images.githubusercontent.com/12447806/54805805-1cdd3380-4c89-11e9-8c55-c6a40cc34d52.png)

Enter your Application Name (e.g. 'Kaizala-Reporting') and click Create application.

If you get the following popup, click **Not Now**

![image](https://user-images.githubusercontent.com/12447806/54805410-ee128d80-4c87-11e9-96b2-81995d68471f.png)

Locate the **Application Id** and copy it to notepad...we will configure this as a setting in PHP later.

#### Step 3: Generate a New Password

![image](https://user-images.githubusercontent.com/12447806/54805527-4c3f7080-4c88-11e9-9409-efd96a5358ea.png)

Via **Application Secrets**, click **Generate New Password** and copy it, as it will be used to configure Office 365 SSO for your application

#### Step 4: Add Platform

Click **Add Platform**

![image](https://user-images.githubusercontent.com/12447806/54806107-1ac7a480-4c8a-11e9-8f93-665bed81bb5f.png)

Via **Platforms**, click **Add Platform**, choose **Web**.

![image](https://user-images.githubusercontent.com/12447806/54806498-32535d00-4c8b-11e9-9c2d-71e3ab61e41d.png)

Via **Platforms**, **Redirect URLs**, enter the URL in the format **YOUR_SERVER_URL/login** either Localhost or live e.g.

- localhost e.g. http://localhost/kaizala_reporting/login
- live e.g https://kaizala-reporting.azurewebsites.com/login

And click **SAVE**

### Updating config.php with AD

config.php file needs to be updated with many of the values captured from the application registration process we just finished in Azure Active Directory. Specifically, values for client_id, secret, and redirect_uri should be updated to reflect the values from your application registration in Azure AD.

1. \$config['client_id'] should be set to the value from **Step 2** above
2. \$config['secret'] should be set to the value from **Step 3** above
3. \$config['redirect_uri'] should be set to the **Sign-on URL** value from **Step 4** above

Navigate to application/config/config.php and edit the following:

```sh

$config['client_id'] = '';
$config['secret'] = '';
$config['redirect_uri'] = '';

```

---

### To run the webAp either on Localhost/Live

Open a new tab, paste the following URL and click Go:

```sh
Localhost: http://YOUR_LOCALHOST_URL/
Live: https://YOUR_LIVE_SERVER_URL/

```

---

## C. More On Integration into Kaizala

##### a) Webhooks

Webhooks allow you to build or integrate applications which subscribe to certain events on Kaizala. When one of those events is triggered, Kaizala service would send a HTTPS POST payload to the webhook’s configured URL. Webhooks can be used to listen to content being posted on the group and use that information to update your database, trigger workflows in your internal systems, etc.

##### b) To register a webhook and update database with content posted on group

The following are required:

- accessToken - Generated using applicationId, applicationSecret and refreshToken
- objectId - in this case, it is groupId
- objectType - it will be Group for the sake of getting content posted on group
- eventTypes - different types of events to subscribe for
- callbackUrl - HTTPS URL to which the subscribed events need to be notified to

For example,
Request Body - Subscribe to all events at group level

```sh
{
   "objectId":"74943849802190eaea3810",
   "objectType":"Group",
   "eventTypes":[
      "ActionCreated",
      "ActionResponse",
      "SurveyCreated",
      "JobCreated",
      "SurveyResponse",
      "JobResponse",
      "TextMessageCreated",
      "AttachmentCreated",
      "Announcement",
      "MemberAdded",
      "MemberRemoved",
      "GroupAdded",
      "GroupRemoved"
   ],
   "callBackUrl":"https://requestb.in/123",
   "callBackToken":"tokenToBeVerifiedByCallback",
   "callBackContext":"Any data which is required to be returned in callback"
}
```

Note that: To ensure that your webhook service endpoint is authentic and working, your callback URL will be verified before creating subscription. For verification, Kaizala will generate a validation token and send a GET request to your webhook with a query parameter “validationToken” which you need to send back within 5 seconds. [Read More](https://docs.microsoft.com/en-us/kaizala/connectors/webhookvalidaton)

Once the webhook is registered successfully and one of the subscribed events is triggered, the callback payload (Request Body) (a JSON) will be sent to your callbackUrl and you will have to parse the JSON content to get the data of interest.

Sample JSON sent to callbackURL when a survey is created:

```sh
{
  "subscriptionId": "e312f80d-e6f1-4d06-beb2-90eaea3810",
  "objectId": "29b9bf47-9249-90eaea3810-97fa940a29f7",
  "objectType": "Group",
  "eventType": "SurveyCreated",
  "eventId": "90eaea3810-4bb4-946d-298ad53f27ba",
  "data": {
    "actionId": "bd06606e-90eaea3810-93-946d-298ad53f27ba",
    "groupId": "29b9bf47-9249-90eaea3810-97fa940a29f7",
    "validity": 1553505316037,
    "title": "Market Research Day",
    "visibility": "All",
    "questions": [
      {
        "title": "Will you be available? ",
        "type": "SingleOption",
        "options": [
          {
            "title": "Yes"
          },
          {
            "title": "No"
          },
          {
            "title": "Come late"
          }
        ]
      },
      {
        "isInvisible": true,
        "title": "ResponseTime",
        "type": "DateTime",
        "options": []
      },
      {
        "isInvisible": true,
        "title": "ResponseLocation",
        "type": "Location",
        "options": []
      }
    ],
    "properties": [
      {
        "name": "DateTime",
        "type": "Numeric",
        "value": "1"
      },
      {
        "name": "Location",
        "type": "Numeric",
        "value": "2"
      },
      {
        "name": "Description",
        "type": "Text",
        "value": "List of all members available "
      }
    ]
  },
  "context": "YOUR_CALLBACK_URL",
  "fromUser": "+2547xxxxxxxx",
  "fromUserId": "drt36972-32f6-84e1-91b6-53sdfe9403f6",
  "isBotfromUser": false,
  "fromUserName": "Samuel Wanjohi",
  "fromUserProfilePic": "",
  "groupId": "29b9bf47-9249-90eaea3810-97fa940a29f7",
  "sourceGroupId": "29b9bf47-9249-90eaea3810-97fa940a29f7"
}
```

---

## Contact

Microsoft AppFactory Nanyuki

> Alvaro Masitsa &nbsp;&middot;&nbsp;

> Software Developer Coach &nbsp;&middot;&nbsp;

> Mail [alvaro.masitsa@nanyukiappfactory.onmicrosoft.com]&nbsp;&middot;&nbsp;

---
