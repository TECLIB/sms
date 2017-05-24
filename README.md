# SMS Notifications GLPI plugin

A plugin to add SMS Notifications to GLPI.

Use core's config to add SMS notifications, set the template and the recipients.

Some things to get in mind setting up the plugin:
- for standard users, it will look for a mobile phone, a phone number then an alternative phone number. First found will be used;
- for entity administrators, we'll retrieve the phone number configured in entity address;
- for global admin, we take nothing right now. Maybe a parameter will be added in the notification configurations (just like emails).

## Contributing

* Open a ticket for each bug/feature so it can be discussed
* Follow [development guidelines](http://glpi-developer-documentation.readthedocs.io/en/latest/plugins/index.html)
* Refer to [GitFlow](http://git-flow.readthedocs.io/) process for branching
* Work on a new branch on your own fork
* Open a PR that will be reviewed by a developer
