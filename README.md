# Kaizala Reporting using 3rd Party App

Kaizala is a mobile application that allows work get done within the context of a conversation / group using actions. While it provides a whole lot of functionalities that allow businesses and enterprises to get work done, it also supports extensiblity allowing building of powerful integrations with existing business processes / workflows.

### Getting Started...

### Project Setup

1.  Clone the repository

```sh
git clone https://github.com/nanyukiappfactory/kaizala-reporting.git

```

2. Delete the .git folder

3. Open sampleconfig.php file located in application/config/ and change base_url

```sh
for example
$config['base_url'] = 'https://YOUR_WEB_APP_NAME.azurewebsites.com/'
```

4. Open sampledatabase.php in the location: application/config/ and setup the connection to your database

```sh
	// -YOUR db --
	'hostname' => 'ENTER YOUR_HOST',
	'username' => 'ENTER YOUR_USRNAME_',
	'password' => 'ENTER YOUR_PASSWORD',
	'database' => 'ENTER YOUR_DATABASE_NAME'
```

5. Navigate to application/modules/models and open Kaizala_model.php
   Provide your applicationId, applicationSecret and refreshToken e.g

```sh
 public function __construct()
    {
        $this->application_id = "APPLICATION_ID HERE";
        $this->application_secret = "APPLICATION_SECRET HERE";
        $this->refresh_token = "REFRESH_TOKEN HERE";
    }
```

6. Deployment

```sh
If you are using Bitbucket:
Create a repository
git clone an empty folder
copy everything from the above project and paste to this empty folder with .git folder only

Open terminal and cd to this Bitbucket repository folder and type:
 - git add .  and click ENTER
 - git commit -m "Enter your message you want to commit"
 - git push
```

7. Making your bitbucket as deployment options for your azure webApp
   Assuming that at this point you have already created a Windows WebApp in Azure.

Go to azure portal, click **All resources**, find your webApp and click it. Select **Deployment center**
![image](https://user-images.githubusercontent.com/12447806/54524213-69bbc400-4982-11e9-8233-bdf3c78b3705.png)

Select **Bitbucket** and click **continue**
![image](https://user-images.githubusercontent.com/12447806/54524422-fc5c6300-4982-11e9-9803-197533a91960.png)

Choose Bitbucket **Team**, select the **repository** you push your codes to and a **branch** and click **continue** as shown below:
![image](https://user-images.githubusercontent.com/12447806/54524596-6b39bc00-4983-11e9-9c6b-e1dbf83ca847.png)

Confirm and click **Finish**

8. Setting up database, config and htaccess for the live system.

Copy your webApp base url and open it in new tab. Just before you click go, do the following:
for example: https://any_name.azurewebsites.net/ , add **scm** to the link to become:
https://any_name.scm.azurewebsites.net/ and click go.

This will direct you to a page below, select **CMD** and navigate to site/wwwroot/
![image](https://user-images.githubusercontent.com/12447806/54525450-4e05ed00-4985-11e9-9855-32d112e77cb5.png)

Type

```sh
cp sample.htaccess .htacess
```

and click enter

![image](https://user-images.githubusercontent.com/12447806/54525733-f025d500-4985-11e9-90ae-a792874199ac.png)

Navigate to site/wwwroot/application/config and type:

```sh
cp sampledatabase.php database.php
cp sampleconfig.php config.php
```

9. Running Migrations
   copy your base_url provided by azure under overview of your webApp and open it in a new tab. Add /migrate after it e.g.
   If your base_url is : **https://any_name.azurewebsites.net** add /migrate to become **https://any_name.azurewebsites.net/migrate** and click go.
   Continue with number 10 if migrations were successfully.

10) Reopen your WebApp
    Now reopen your webApp
    Click fetchGroups to get all your groups
    Click activate to register a webhook

### More On Integration

##### Webhooks

Webhooks allow you to build or integrate applications which subscribe to certain events on Kaizala. When one of those events is triggered, Kaizala service would send a HTTPS POST payload to the webhook’s configured URL. Webhooks can be used to listen to content being posted on the group and use that information to update your database, trigger workflows in your internal systems, etc.

###### To register a webhook and update database with content posted on group

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
